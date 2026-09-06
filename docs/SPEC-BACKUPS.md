# Spec — Copias de seguridad tipo Plesk sobre HestiaCP

> **ESTADO 2026-09-05: construido v1 (wrapper `bin/v-elurk-backup`, endpoint `webpages/domain/action.php`, pestaña Copias en `list_domain.php`). Sin probar en el lab: ver `docs/DEPLOY.md` § Copias y WordPress Toolkit.**

Petición del usuario (2026-09-05, captura de "Restore the Backup" de Plesk):
copias de cada dominio a destino FTPS, y restauración PARCIAL desde una lista con
selector múltiple: dominio completo, buzones de correo sueltos, archivos sueltos,
o solo la configuración.

Todo lo de abajo está verificado contra el código fuente de Hestia (rama main,
bin/v-backup-user, bin/v-restore-user, func/backup.sh, bin/*-restic, plantillas
list_backup_detail*.php). Fecha de verificación: 2026-09-05.

## 1. Cómo hace las copias Hestia hoy (lo nativo)

### Unidad de copia = USUARIO, no dominio
`v-backup-users` (cron diario) llama a `v-backup-user USER` por cada usuario.
Produce UN tar por usuario: `/backup/USER.AAAA-MM-DD_HH-MM-SS.tar`. Retención =
campo BACKUPS del paquete del usuario. Cada copia es COMPLETA (no incremental).

Para que "copia por dominio" sea real hay que seguir la regla que ya usamos:
**1 cliente = 1 usuario Hestia = 1 suscripción Plesk.** Así el tar del usuario ES
la copia de esa suscripción, y con `v-backup-user-config`/exclusiones se afina.

### Qué hay dentro del tar (layout exacto)
```
web/DOMINIO/hestia/web.conf          # config del dominio en Hestia
web/DOMINIO/conf/                    # vhosts apache/nginx, ssl
web/DOMINIO/template/
web/DOMINIO/domain_data.tar.zst      # TODO public_html, private, stats… (zstd o gz)
mail/DOMINIO/hestia/                 # mail.conf + DOMINIO.conf (cuentas, alias, pass)
mail/DOMINIO/conf/                   # ficheros exim del dominio
mail/DOMINIO/accounts.tar.zst        # TODOS los buzones juntos (un dir por cuenta)
db/NOMBRE/hestia/db.conf
db/NOMBRE/NOMBRE.mysql.sql.zst       # volcado
dns/DOMINIO/hestia/ + conf/DOMINIO.db
cron/cron.conf + crontab
user_dir/CARPETA.tar.zst             # .ssh, tmp, etc. (una por carpeta)
```
Detalle importante: **los buzones NO van en tarballs separados**, van todos en
`accounts.tar.zst`. Pero dentro cada cuenta es un directorio de primer nivel, así
que se puede extraer UNA sola cuenta con tar (ver §3).

### Destinos remotos (BACKUP_SYSTEM en hestia.conf)
`local`, `ftp`, `sftp`, `b2`, `rclone`. Se configuran con
`v-add-backup-host TIPO HOST USUARIO PASS [RUTA] [PUERTO]`.

**FTP en Hestia es FTP PLANO**: `func/backup.sh` usa `/usr/bin/ftp -np`, sin TLS.
No existe opción FTPS. Solución soberana y sin tocar código:
- Crear un remoto rclone de tipo `ftp` con `explicit_tls = true` (FTPES, puerto 21)
  o `tls = true` (FTPS implícito, 990) en `/root/.config/rclone/rclone.conf`.
- `v-add-backup-host rclone NOMBRE_REMOTO` (Hestia solo comprueba que exista la
  sección `[NOMBRE_REMOTO]` en rclone.conf).
- rclone valida el certificado del servidor; si es autofirmado añadir
  `no_check_certificate = true` solo en el lab.
Alternativa igual de válida: `sftp` (SSH), soportado nativo y cifrado.

### Restauración nativa (lo que YA da la interfaz de Hestia)
Usuario → Copias → detalle de la copia (`list_backup_detail.php`): lista por
secciones (web, correo, DNS, BBDD, cron, carpetas) con **casilla por elemento y
acción en lote** (`/bulk/restore/`) o restauración individual
(`/schedule/restore/?backup=&type=&object=`). Por debajo:
```
v-restore-user USER COPIA [WEB] [DNS] [MAIL] [DB] [CRON] [UDIR] [NOTIFY]
```
Cada campo admite lista separada por comas, `*` (todo) o `no`.

Lo que NO hace lo nativo (y Plesk sí):
- Buzón individual: restaura el dominio de correo ENTERO (`accounts.tar.zst` completo).
- Archivos sueltos: restaura `domain_data.tar.zst` entero (excluye logs).
- "Solo configuración": no existe; siempre config + contenido.
- Copia incremental: solo en modo restic (ver §2).

## 2. Modo incremental (restic) — la mejor base para lo granular
Hestia trae copias incrementales con restic: se activa "Incremental Backups" en
el paquete del usuario, `v-add-backup-host-restic` define el repo, y
`v-backup-users-restic` hace snapshots deduplicados y CIFRADOS.
**La clave está en `/usr/local/hestia/data/users/USER/restic.conf`: sin ella la
copia es irrecuperable. Hay que guardarla fuera del servidor.**

Scripts: `v-list-user-backups-restic`, `v-list-user-backup-restic`,
`v-restore-user-restic USER SNAPSHOT WEB DNS MAIL DB CRON UDIR`,
`v-restore-web-domain-restic`, `v-restore-mail-domain-restic`,
`v-restore-database-restic`, `v-restore-cron-job-restic`,
`v-delete-user-backup-restic`, y sobre todo:
```
v-list-user-files-restic USER SNAPSHOT CARPETA   # restic ls --json
```
Ese último ya lista archivos de un snapshot. La UI nativa
(`list_backup_detail_incremental.php`) NO lo usa: sigue a nivel dominio/BD.
Pero restic permite `restore --include /ruta/exacta` y `dump snapshot /ruta`, o
sea: restaurar UN archivo, UNA carpeta o UN buzón
(`/home/USER/mail/DOMINIO/CUENTA`) es trivial en modo restic. En modo tar clásico
también se puede, pero leyendo el tar completo (ver §3).

Los snapshots restic pueden vivir en el mismo destino rclone FTPS (`rclone:` es
backend válido de restic) o en sftp. Recomendación: **tar clásico diario a FTPS
por rclone (portable, se abre con cualquier tar) + restic incremental para la
restauración granular.** Ambos coexisten en Hestia.

## 3. Qué construimos: pestaña "Copias" en la vista por dominio

Mismo patrón wrapper+pestaña que Security/WordPress. Nada de tocar el core.

### 3.1 Wrapper `bin/v-elurk-backup` (root vía sudoers, acciones acotadas)
Salida siempre JSON. Validar usuario/dominio/copia con regex estricta; el dominio
debe pertenecer al usuario (`v-search-domain-owner`). `export PATH` (gotcha sudo).
```
list           USER                                  # v-list-user-backups json + restic
detail         USER COPIA                            # v-list-user-backup json, filtrado a un dominio
mail-accounts  USER COPIA DOMINIO                    # tar -tf accounts.tar.zst → cuentas (nivel 1)
files          USER COPIA DOMINIO [SUBRUTA]          # índice de domain_data (cacheado) o restic ls
restore        USER COPIA --web a,b --mail c --db d --dns e [--config-only] [--notify]
restore-mail-account USER COPIA DOMINIO CUENTA[,CUENTA]
restore-files  USER COPIA DOMINIO RUTA[,RUTA]        # a un staging y luego al docroot
restore-config USER COPIA DOMINIO {web|mail|dns}
```
Implementación por tipo:
- **Objetos completos** → delegar en `v-restore-user` (tar) o
  `v-restore-user-restic` (snapshot). Ejecutar en segundo plano (nohup + log en
  `/var/log/hestia/elurk-restore.log`) igual que hace Hestia con su cola.
- **Buzón suelto (tar)**: `tar -xOf USER.fecha.tar mail/DOM/accounts.tar.zst |
  zstd -dc | tar -xp -C /home/USER/mail/DOM/ CUENTA` + chown + `v-rebuild-mail-domains`.
  Si la cuenta no existe en `mail/DOM.conf` del usuario, recrear la entrada desde
  `mail/DOM/hestia/DOM.conf` de la copia. (restic: `restore --include
  /home/USER/mail/DOM/CUENTA --target /`).
- **Archivos sueltos (tar)**: `tar -xOf … web/DOM/domain_data.tar.zst | zstd -dc |
  tar -x -C STAGING RUTAS` → copiar al docroot con `rsync -a --chown`. Coste: lee
  el tarball completo (18 GB ≈ minutos) → siempre en segundo plano con aviso.
  Índice de archivos: se genera una vez por copia (`tar -t` → JSON en
  `/backup/.elurk-index/USER.fecha.json`) por cron nocturno tras el backup, para
  que la UI liste al instante. (restic: `v-list-user-files-restic`, sin índice).
- **Solo configuración**: copiar `web/DOM/hestia/web.conf` a la línea del dominio
  en `$USER_DATA/web.conf` y `v-rebuild-web-domain`; para correo el `DOM.conf` +
  `v-rebuild-mail-domains`; DNS zona + `v-rebuild-dns-domains`. No se toca
  contenido. Hacer copia previa de los .conf sustituidos.

### 3.2 UI (calcada del diálogo de Plesk de la captura)
Pestaña **Copias** dentro de `/list/domain/?domain=X`:
1. Tabla de copias del usuario dueño: fecha, tamaño, tipo (completa/incremental),
   destino, botón Descargar (`v-download-backup`) y **Restaurar**.
2. Diálogo Restaurar:
   - Cabecera con fecha, tamaño, notas ("Programada. Completa" / "Incremental").
   - Radio: *Objetos seleccionados* / *Todo el dominio*.
   - Desplegable **Tipo de objeto**: Configuración del dominio · Sitio web (archivos)
     · Cuenta de correo · Base de datos · Zona DNS · Archivos sueltos.
   - **Doble lista con selector múltiple** (Disponibles → Seleccionados, con
     buscador y casilla "todos"), igual que Plesk. Para "Archivos sueltos" la lista
     de la izquierda es un árbol navegable del índice.
   - Opciones: *Configuración y contenido* / *Solo configuración*;
     "Avisar por correo al terminar" (correo del usuario Hestia).
   - Botón Restaurar → confirmación → tarea en segundo plano → banner con
     progreso leyendo el log; al terminar, aviso.
3. Ajustes (solo admin): destino remoto (sftp/rclone-FTPS) con "Probar conexión",
   retención, modo incremental sí/no, exclusiones del usuario.

### 3.3 Orden de trabajo
1. Lab: activar restic en el paquete + remoto rclone FTPS; verificar
   `v-list-user-files-restic` y un `restore --include` de un buzón a mano.
2. Wrapper `list/detail/mail-accounts/files` (solo lectura) + pestaña con tablas.
3. `restore` de objetos completos (delegado a Hestia) + doble lista.
4. Buzón suelto y archivos sueltos. 5. Solo configuración. 6. Ajustes admin.

### Riesgos
- Extraer del tar clásico es lento con copias grandes: siempre asíncrono.
- Restaurar un buzón "por encima" mezcla correo actual y antiguo (Maildir):
  ofrecer opción "restaurar en carpeta RESTAURADO/" dentro del buzón.
- Permisos: todo lo restaurado debe quedar del usuario del sitio, nunca root.
- Nunca restaurar config sin backup previo del .conf que se pisa.
