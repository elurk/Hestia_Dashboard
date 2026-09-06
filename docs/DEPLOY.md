# Despliegue manual — Dashboard Manager (fork Elurk)

Guía para aplicar el fork en el laboratorio (o en el VPS) paso a paso. Todo se
ejecuta como **root** en la máquina Hestia. Claude NO se conecta a esa máquina;
esto lo ejecutas tú.

Requisito previo: HestiaCP ya instalado y funcionando (ver `LAB-SETUP.md`).

---

## 1. Llevar el fork a la máquina

Desde tu equipo, o clonando en la VM:

```bash
git clone https://github.com/elurk/Hestia_Dashboard.git
cd Hestia_Dashboard
git checkout elurk/soberania      # la rama con las mejoras
```

## 2. Instalar

```bash
sudo bash install.sh
```

Esto instala: dashboard con menú lateral, los 4 temas, **fuentes autoalojadas**
(sin Google), el CLI `hestia-theme`, la **protección anti-updates** y el resto.
También deja el **tema Elurk** en `css/themes/custom/elurk.css`, la **vista por
dominio** (`/list/domain/`), las pestañas Seguridad/WordPress, el sidebar con
el logo, el botón claro/oscuro y el gestor de archivos en claro.

Para actualizar un servidor que ya tiene el fork basta con repetir estos dos
pasos (`git pull` + `sudo bash install.sh`) y recargar con Ctrl+Shift+R.

## 2b. Activar el tema Elurk

El CSS ya está copiado, pero Hestia carga el tema que tenga cada usuario en su
configuración. Para el usuario admin (en el lab, `elurk`):

```bash
sudo bash themes-elurk/deploy-elurk-theme.sh <usuario_admin>
```

Equivale a elegir "elurk" en el panel: icono de usuario → Editar → Tema.
Para que sea el tema por defecto de **todos** los usuarios nuevos, en el panel:
Ajustes del servidor (engranaje) → Apariencia → Tema → `elurk`. Un usuario que
tenga su propio tema elegido conserva el suyo.

Comprobación: el sidebar oscuro con el logo arriba, y la entrada "Dominios"
llevando a la vista por dominio. Si ves el menú horizontal de Hestia, el tema
no está activo para ese usuario.

## 3. Activar el dashboard alternativo

Por defecto el panel sigue aterrizando en la lista de usuarios. Para activar el
dashboard nuevo, como admin en el panel: preferencias → activar "Alt Dashboard"
(toggle), o por CLI:

```bash
v-change-sys-config-value ALT_DASHBOARD true
```

## 4. Verificar

```bash
# ¿Cero llamadas a Google en los CSS instalados?
grep -rl googleapis /usr/local/hestia/web/css/themes/custom/ ; echo "(vacío = OK)"

# ¿Las fuentes están desplegadas localmente?
ls /usr/local/hestia/web/css/themes/custom/fonts/ | head

# ¿El dashboard detecta bien los servicios (PHP con su versión real)?
# -> entra al panel y mira las tarjetas de estado / System Status
```

Abre el panel en el navegador y comprueba el aspecto. **Aquí es donde toca
anotar los descuadres de CSS** (si aparecen) para arreglarlos viendo el render.

## 5. Fail2ban: evitar baneos de clientes

```bash
cp fail2ban/zzz-elurk-overrides.local /etc/fail2ban/jail.d/
# EDITA el archivo y pon en 'ignoreip' las IPs fijas de tus clientes y tu oficina
nano /etc/fail2ban/jail.d/zzz-elurk-overrides.local
fail2ban-client -t          # PRUEBA la config; si da error, NO reinicies
systemctl restart fail2ban

# Comprobar (los jails de Hestia llevan sufijo -iptables)
fail2ban-client status
fail2ban-client status dovecot-iptables            # ver baneados del correo
fail2ban-client set dovecot-iptables unbanip <IP>  # desbanear a mano
```

Correo indulgente (6 intentos, baneo 10 min), SSH y panel duros (3 intentos, 2h).

## 6. Hardening de WordPress

**Siempre en dry-run primero** para ver qué haría:

```bash
hestia-wp-harden --user <usuario> --domain <dominio.com> --dry-run
```

Si te convence, aplícalo de verdad (sin `--dry-run`):

```bash
hestia-wp-harden --user <usuario> --domain <dominio.com>
```

Con `--scan` lanza además wordfence CLI si está instalado. Corrige propietario,
permisos, bloquea PHP en uploads y endurece `wp-config.php`.

## 7. Probar la protección anti-updates

El punto que rompía el proyecto original. Para verificar que sobrevive:

```bash
# Simular que un update revirtió un archivo core:
cp /usr/local/hestia/plugins/theme-manager/reapply-src/patch_files/main.php /tmp/nuestro-main.php
# (fuerza una diferencia tocando el core a mano, o espera a un update real)

# Ejecutar el reapply manualmente:
/usr/local/hestia/bin/hestia-theme-reapply
cat /var/log/hestia/theme-reapply.log

# Tras un 'apt upgrade' que actualice Hestia, el hook lo ejecuta solo.
# Revisa post-update-stock/ por si el update cambió archivos core:
ls /usr/local/hestia/plugins/theme-manager/post-update-stock/
```

## 8. Rollback

```bash
sudo bash uninstall.sh
```

Restaura los archivos core originales (desde los backups), borra dashboard,
temas, fuentes, CLI, hook de APT y script de reapply. Deja el sistema como antes.

---

## Estado de las mejoras de este fork

| Mejora | Estado |
|--------|--------|
| Fuentes Ubuntu autoalojadas (sin Google Fonts) | ✅ Hecho, verificable |
| Dashboard: servicios dinámicos + `$user` escapado | ✅ Hecho |
| Protección anti-updates (reapply + hook APT + salvaguarda) | ✅ Hecho |
| Fail2ban: whitelist clientes + umbrales correo/SSH | ✅ Plantilla lista |
| Hardening WordPress (script `hestia-wp-harden`) | ✅ Hecho, con dry-run |
| Descuadres de CSS | ⏳ Requiere lab para ver el render |
| Pestaña WordPress visual en el panel | ⏳ Requiere lab (el motor ya existe) |

Las dos pendientes necesitan ver el panel renderizado; se rematan en el lab.


---

## Copias y WordPress Toolkit (pestañas de la vista por dominio)

Ambas se instalan con `install.sh` (wrappers `v-elurk-backup`, `v-wp-manage`,
`hestia-wp-toolkit` en `/usr/local/hestia/bin`, sudoers para `hestiaweb`, endpoint
`/list/domain/action.php`). Requisitos en el servidor: `python3` (viene con Ubuntu),
`zstd` (Hestia lo instala si BACKUP_MODE=zstd), `rsync`, `flock`.

### Copias
1. Comprueba que el usuario tiene copias: Usuario → Copias, o `v-list-user-backups USER`.
2. Abre `/list/domain/?domain=X#backup`. Debe listar las copias y, en "Este dominio",
   marcar web/correo/DNS si el dominio está dentro.
3. Prueba de restauración parcial SIN riesgo: Restaurar → tipo "Cuentas de correo" →
   elige un buzón → opción "carpeta RESTAURADO-fecha" → Restaurar. Sigue el progreso
   en el recuadro; el registro completo está en `/var/log/hestia/elurk-restore.USER.log`.
   En el webmail del buzón aparece la carpeta RESTAURADO-…
4. "Archivos sueltos": la primera vez genera un índice del tar (lee todo el sitio
   comprimido, minutos); se guarda en `/backup/.elurk-index/` y las siguientes son inmediatas.
5. "Solo configuración" deja copia del .conf pisado en `/backup/.elurk-index/conf-bak/`.

FTPS (Plesk lo trae; el FTP de Hestia es plano). Sin tocar código, con rclone:
```bash
apt install rclone
rclone config create nasftps ftp host=nas.ejemplo.com user=copias pass="$(rclone obscure 'LaClave')" explicit_tls=true   # FTPES puerto 21
# (implícito, puerto 990: tls=true port=990)
rclone lsd nasftps:                                   # prueba
v-add-backup-host rclone nasftps                      # Hestia solo mira que exista [nasftps] en /root/.config/rclone/rclone.conf
grep BACKUP_SYSTEM /usr/local/hestia/conf/hestia.conf # debe incluir rclone
```
Incrementales (restic): activa "Incremental Backups" en el paquete del usuario y
crea el repositorio con `v-add-backup-host-restic` (puede ser el mismo remoto rclone).
**Guarda fuera del servidor** `/usr/local/hestia/data/users/USER/restic.conf` (clave).
La pestaña Copias mezcla ambas: las incrementales salen como "Incremental (restic)" y
permiten restaurar archivos y buzones sin índice previo.

### WordPress Toolkit
1. WP-CLI por usuario: `v-add-user-wp-cli USER` (o el botón "Instalar WP-CLI" que sale
   en la pestaña si eres admin). Sin WP-CLI solo se ven núcleo y medidas de archivos.
2. Abre `/list/domain/?domain=X#wp`. La primera comprobación tarda (WP-CLI + consulta
   de vulnerabilidades por plugin a wpvulnerability.net; caché 24 h en
   `/usr/local/hestia/data/elurk/wpt/vulns/`, estado 10 min en `.../cache/`).
3. Medidas: marca y "Asegurar seleccionadas". Las de servidor escriben
   `/home/USER/conf/web/DOMINIO/nginx.conf_elurkwpt` (+ `.ssl.`) y un bloque
   `# BEGIN ELURK-WPT` en el `.htaccess` del docroot; se valida con `nginx -t` antes de
   recargar y se retiran si falla. Las de wp-config llevan el comentario `// ELURK-WPT`.
   Pingbacks/autores/mantenimiento usan el mu-plugin `wp-content/mu-plugins/elurk-wp-toolkit.php`.
4. Herramientas: caché FastCGI solo con nginx+PHP-FPM (sin Apache). "Tomar el control
   de wp-cron" crea una tarea cron de Hestia cada 5 min con el PHP del dominio.

Si algo no responde: `sudo -u hestiaweb sudo /usr/local/hestia/bin/v-elurk-backup list USER`
y `... v-wp-manage status USER DOMINIO --refresh` en la consola muestran el JSON o el error.

### Retención, borrado y programación (pestaña Copias, solo admin)
- **Conservar**: Hestia borra las copias antiguas según BACKUPS del usuario. "Todas las
  copias" pone BACKUPS=999 en `user.conf` (no en el paquete): nunca borra, las quitas tú
  con el botón Borrar de cada fila. Si reasignas el paquete al usuario, vuelve el valor
  del paquete. Cada copia completa ocupa el tamaño entero del usuario: vigila el disco.
- **Copia programada**: crea una tarea cron en el usuario administrador (ROOT_USER) con
  `sudo /usr/local/hestia/bin/v-backup-user USUARIO` (o `-restic`). Hestia mantiene además
  su copia global diaria de todos los usuarios (`v-backup-users` en el Cron del admin);
  desactívala ahí si solo quieres las programadas por usuario.
