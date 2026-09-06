# Plesk → Hestia: inventario de funciones y feasibilidad

Qué tiene Plesk y hasta dónde podemos replicarlo en el fork sobre Hestia.
Leyenda: ✅ nativo Hestia · 🔶 construible (mapeo/custom) · ⚠️ parcial · ❌ no factible / otro proyecto · 🔒 choca con arquitectura Hestia

## Sitios web y dominios
- Añadir dominio / subdominio / alias — ✅
- Config de hosting por dominio (docroot, PHP, etc.) — ✅
- Selector de versión PHP + ajustes PHP por dominio — ✅
- SSL/TLS Let's Encrypt (incl. wildcard) — ✅
- Apache & nginx (proxy) — ✅
- DNS (zona por dominio) — ✅
- Directorios protegidos con contraseña — 🔶 (Hestia lo hace por .htpasswd, envolver)
- Redirecciones / preview del sitio — ✅
- Importación de sitios web (migrar de otro hosting) — ⚠️ (Hestia tiene migración parcial por CLI)

## Correo
- Cuentas de correo por dominio (crear/borrar/cuota/pass) — ✅
- Alias, reenvío, autorespuesta — ✅
- DKIM/SPF/DMARC — ✅ (automático)
- Webmail (Roundcube) — ✅
- Antispam / antivirus — ✅ (SpamAssassin/ClamAV)
- Seguimiento de envío de emails (Track Email Delivery) — 🔶 (parsear log Exim)
- Limitar correos salientes por buzón — ⚠️ (Exim ratelimit, envolver)
- Comprobar configuración de email — 🔶 (script de diagnóstico)

## Archivos
- File Manager por dominio — 🔶 (deep-link al /fm/ scoped al docroot)
- FTP — ✅
- Subir por drag&drop — ⚠️ (verificar en lab; si no, FM alternativo = proyecto)
- Comprimir/descomprimir zip — ✅ (el FM de Hestia lo trae)

## Bases de datos
- Crear/borrar BBDD MySQL/PostgreSQL — ✅
- phpMyAdmin — ✅ (integrado)
- Usuarios de BD + roles (Read/Write, Read Only...) — ⚠️ (Hestia gestiona usuarios de BD, roles granulares limitados)
- Exportar/importar volcado, copiar BD — 🔶 (envolver mysqldump/CLI)
- Asociar BD ↔ dominio — 🔒 (Hestia asocia BD al USUARIO, no al dominio → mapeo)

## Copias de seguridad
- Backup y restauración por suscripción — ✅ (Hestia backups por usuario; 1 cliente = 1 usuario)
- Backup a destino remoto (SFTP/S3/B2) — ✅ · **FTPS** — ✅ vía remoto rclone (el FTP nativo de Hestia es plano)
- Programación de backups — ✅ · Incrementales — ✅ (restic, por paquete)
- Restaurar objetos sueltos con doble lista (web/correo/DNS/BBDD) — ✅ nativo + pestaña Copias del fork
- Restaurar un **buzón** suelto — ✅ construido en el fork (`v-elurk-backup restore-mail-account`)
- Restaurar **archivos** sueltos — ✅ construido en el fork (índice del tar o `restic ls`)
- Restaurar **solo configuración** — ✅ construido en el fork (`restore-config web|mail|dns`)

## WordPress (WP Toolkit)
- Instalar WP (Quick Install) — ✅
- Hardening de permisos/config — 🔶 (ya hecho: hestia-wp-harden)
- Escaneo de vulnerabilidades — 🔶 (WordFence CLI, integrar)
- Gestión (plugins/temas/updates) desde panel — ⚠️ (parcial vía wp-cli, construible)

## Herramientas de desarrollo
- Registros / Log Browser (Apache/PHP/...) — 🔶 (ya en roadmap Fase 2)
- Tareas programadas (Cron) — ✅
- Git — ❌ (no nativo; proyecto aparte)
- PHP Composer — ❌ (no nativo)
- Performance Booster (caché bundle) — ❌ (Plesk propietario; en nginx se hace a mano)

## Seguridad
- Certificados SSL/TLS — ✅
- Firewall — ✅
- Fail2ban (IP banning) — ✅ (+ nuestra pestaña con whitelist/blacklist)
- WAF (ModSecurity) — 🔶 (no nativo nginx; construible, el más costoso)
- Imunify360 / Advisor — ❌ (Imunify es de pago de terceros; Advisor es propietario Plesk)
- IP Access Restriction — ✅ (firewall)

## Usuarios y permisos (MATIZADO — mejor de lo que parecía)
- **Usuario con acceso SOLO a su dominio** — ✅ NATIVO. El modelo de Hestia ES este:
  creas una cuenta de usuario, le asignas su(s) dominio(s), y al entrar SOLO ve lo
  suyo, aislado por usuario del sistema. Cubre el caso de negocio principal (un dev
  monta una web en tu hosting y solo toca su dominio). Aquí ya se ahorra Plesk.
- Clientes / Revendedores — ⚠️ (Hestia: usuarios; reseller limitado)
- Suscripciones / Planes de servicio — 🔶 (Hestia: packages ≈ planes)
- **Roles granulares** (Admin/WebMaster/Application User/Contable/Limited Webmaster
  con permisos distintos sobre el MISMO dominio) — 🔶 NO nativo, pero CONSTRUIBLE en
  el fork: como controlamos la UI, añadimos una capa de "rol" que muestra/oculta
  pestañas y acciones según el rol asignado, encima del aislamiento real de Hestia.
  No tan profundo como Plesk a nivel seguridad, pero cubre lo práctico. Medio esfuerzo.
- Roles de usuario de BD — ⚠️

## Administración del servidor
- Herramientas y configuración (panel central) — 🔶 (landing tipo Tools&Settings)
- Estadísticas / Info del servidor — ✅
- Services Management (start/stop/restart) — 🔶 (roadmap Fase 2)
- Lista de procesos / Task Manager — 🔶 (envolver `ps`/htop-like)
- Extensiones (marketplace) — ❌ (ecosistema Plesk)
- Actualizaciones del sistema — ✅ (apt / Hestia updater)
- Migration & Transfer Manager — ⚠️

## Apariencia / marca
- Branding / Custom URL / Custom Buttons — 🔶 (tema Elurk + personalización)
- Idiomas — ✅ (Hestia multiidioma)

---

## Resumen honesto — "hasta dónde podemos llegar"

- **~70% de Plesk es ✅ nativo o 🔶 construible con nuestro patrón** (vista por
  dominio, correo, archivos, BBDD, WP, logs, backups, SSL, fail2ban). Ahí llegamos.
- **~15% es ⚠️ parcial** (roles de BD, importación, gestión WP completa, resellers).
- **~15% es ❌/🔒 difícil o no factible**: Git/Composer/Performance Booster/Imunify/
  Advisor/Extensiones (ecosistema propietario), y sobre todo **usuarios adicionales
  con roles por suscripción** (choca con la arquitectura de Hestia).

Conclusión: podemos hacer un panel que **se sienta como Plesk en el 85% del uso
diario** (vista por dominio + pestañas + tema). Lo que no vamos a igualar son las
piezas propietarias de Plesk y su modelo de subusuarios-con-roles.

## Cómo trabajamos las capturas
- NO puedo capturar un Plesk en vivo (sin acceso, y es su panel licenciado).
- El usuario ENVÍA capturas de las pestañas/funciones que quiere → las más útiles,
  porque construimos lo que necesita, no todo Plesk. Pedirlas tab a tab al construir.
