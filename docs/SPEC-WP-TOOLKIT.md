# Spec — WordPress Toolkit soberano (paridad con el de Plesk)

> **ESTADO 2026-09-05: construido v1 (motor `bin/hestia-wp-toolkit` (python3), `v-wp-manage` ampliado, pestaña WordPress en `list_domain.php`). Sin probar en el lab: ver `docs/DEPLOY.md` § Copias y WordPress Toolkit.**

Petición del usuario (2026-09-05, 3 capturas de "Security Status" de Plesk):
la pestaña WordPress no puede ser solo "Escanear / Endurecer / endurecido".
Debe mostrar la MISMA información que el original.

## 1. Qué muestra Plesk (inventario de las capturas)

Cabecera con 4 tarjetas:
1. **Riesgo de seguridad** puntuación 0–10 ("0.7/10").
2. **Protección** activada/desactivada (parcheo virtual de vulnerabilidades).
3. **Actualizaciones disponibles** ("Instalar 6 actualizaciones").
4. **Medidas de seguridad** pendientes ("3 medidas por aplicar", Aplicar todas).

Pestaña **Componentes vulnerables**: por cada plugin/tema/core: puntuación de
riesgo, nombre y versión, acciones *Actualizar a X.Y.Z* y *Desactivar plugin*.

Pestaña **Medidas de seguridad**: botones Asegurar · Comprobar · Revertir, fecha
de la última comprobación, lista con casilla, nombre, "(reversible)" y estado
(rojo crítico / naranja aviso / verde ok). Medidas de Plesk:
- Restringir acceso a archivos y directorios (permisos)
- Desactivar pingbacks
- Desactivar edición de archivos desde el escritorio (DISALLOW_FILE_EDIT)
- Configurar claves de seguridad (salts)
- Bloquear xmlrpc.php
- Bloquear listado de directorios
- Prohibir PHP en wp-includes
- Prohibir PHP en wp-content/uploads
- Bloquear wp-config.php
- Desactivar concatenación de scripts en el admin
- Desactivar lenguajes de script no usados (Python/Perl en .htaccess)
- Desactivar PHP en directorios de caché
- Cambiar prefijo de tablas por defecto (no reversible)
- Protección anti-bots
- Bloquear archivos sensibles (readme, license, wp-config-sample…)
- Bloquear archivos potencialmente sensibles (.log, .sql, .bak…)
- Bloquear .htaccess y .htpasswd
- Bloquear escaneo de autores (?author=N)
- Cambiar nombre del administrador por defecto "admin" (no reversible)

Panel lateral **Herramientas**: PHP (versión) · Depuración · Protección por
contraseña · Modo mantenimiento · Smart Update. **Rendimiento**: Indexación por
buscadores · Caché (nginx) · Tomar el control de wp-cron.php.

## 2. Cómo se obtiene cada dato sin nube (todo local)

Motor: **WP-CLI** ejecutado SIEMPRE como el usuario del sitio, nunca root:
`sudo -u USER wp --path=/home/USER/web/DOM/public_html --skip-plugins --skip-themes …`
(Hestia lo instala con `v-add-sys-wp-cli`; verificar en lab que existe el comando.)

| Dato | Fuente local |
|---|---|
| Versión core, plugins, temas, actualizaciones | `wp core version`, `wp core check-update --format=json`, `wp plugin list --format=json --fields=name,title,status,version,update,update_version`, `wp theme list …` |
| Vulnerabilidades por componente | Feed público **Wordfence Intelligence** (JSON, gratuito, sin API key): descarga diaria por cron a `/usr/local/hestia/data/elurk/wf-vulns.json`; cruce por slug + rango de versiones; riesgo = CVSS/10 → misma escala que Plesk |
| Riesgo global | máximo CVSS de componentes afectados (Plesk hace algo equivalente) |
| Medidas de seguridad | comprobaciones sobre archivos, wp-config y vhost (ver §3) |
| PHP | `v-list-web-domain USER DOM json` → BACKEND / template PHP |
| Depuración | `wp config get WP_DEBUG` |
| Modo mantenimiento | `wp maintenance-mode status` |
| Protección por contraseña | directorio protegido de Hestia sobre el docroot (htpasswd nativo) |
| Indexación | `wp option get blog_public` |
| Caché nginx | estado FastCGI cache del dominio en Hestia (`v-list-web-domain` campo FASTCGI_CACHE) |
| wp-cron | `wp config get DISABLE_WP_CRON` + existencia de cron Hestia (`v-list-cron-jobs`) que llame a `wp cron event run --due-now` |

Lo que NO replicamos: "Protección" (parcheo virtual de Patchstack, servicio de
pago en nube) y "Smart Update" (clona y prueba). Fuera de alcance: se sustituye
por actualizar con copia previa (`v-backup-user` antes de `wp plugin update`).

## 3. Medidas: cómo se comprueban y aplican (reutiliza hestia-wp-harden)
Cada medida = función `check_X` (devuelve ok/warn/crit) + `apply_X` + `revert_X`
si es reversible. Las 5 que ya hace `hestia-wp-harden` (propietario, permisos,
DISALLOW_FILE_EDIT/MODS, PHP en uploads, wp-config/xmlrpc) se refactorizan a
este esquema; el resto se añaden. Reglas de servidor: Hestia lleva Apache+nginx
o nginx solo → escribir `.htaccess` (Apache) y un `nginx.conf_*` por dominio con
las plantillas de Hestia (`/home/USER/conf/web/DOM/nginx.conf_elurk` +
`v-rebuild-web-domain`), nunca editar los vhosts generados a mano.
Salts: `wp config shuffle-salts`. Prefijo de tablas: `wp db prefix` (solo con
copia previa y confirmación explícita). Anti-bots y escaneo de autores: reglas
nginx/htaccess por User-Agent y `?author=`.

## 4. Wrapper y UI
`bin/v-wp-manage` crece con subcomandos JSON: `status USER DOM` (todo lo de la
tabla de §2 en una llamada, con caché de 10 min en `/tmp`), `measures USER DOM`,
`apply USER DOM MEDIDA[,…]`, `revert …`, `update USER DOM {core|plugin|theme} SLUG`,
`deactivate USER DOM SLUG`, `toggle USER DOM {debug|maintenance|indexing|cache|cron} on|off`,
`vulns-refresh` (descarga del feed, solo admin).

Pestaña WordPress en la vista por dominio (y la lista global admin):
- 4 tarjetas de cabecera como Plesk (riesgo, actualizaciones, medidas, PHP).
- Sub-pestañas *Componentes vulnerables* / *Medidas de seguridad* / *Herramientas*.
- Tabla de componentes con puntuación en pastilla gris, versión, acciones.
- Lista de medidas con casilla múltiple, estado en color, "(reversible)",
  botones Asegurar seleccionadas · Comprobar · Revertir, fecha de última comprobación.
- Herramientas: interruptores (toggle) que llaman a `toggle`.

Orden: 1) `status` con WP-CLI + tarjetas y tabla de componentes (sin vulns);
2) feed Wordfence + puntuaciones; 3) medidas check/apply/revert; 4) herramientas.
Cada paso se despliega en el lab y el usuario manda captura (<2000 px).
