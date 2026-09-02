# Roadmap — Dashboard Manager (fork Elurk)

Objetivo: panel HestiaCP limpio, moderno y soberano, diferenciado por FUNCIÓN.

## DECISIÓN 2026-08-29 (tras probar en el lab)

El Dashboard Manager solo reestiliza la portada; las páginas interiores siguen
siendo Hestia (y el tema hasta las rompe un poco). Parecerse a Plesk de verdad
(vista por dominio con pestañas + reestilizar cada página) es Nivel 3: cientos de
horas + mantenimiento perpetuo. **Descartado el clon de Plesk.**

Dirección elegida: **tema cohesivo + valor**. Reestilizar las páginas interiores
con CSS para un aspecto limpio, moderno y con marca Elurk (cohesivo, no Plesk
idéntico), y diferenciarse por lo que Plesk NO da: pestañas de valor (Seguridad,
WordPress, Logs, Seguimiento de correo). Diferenciar por función, no por chrome.

---

## Fase 1 — HECHO (rama elurk/soberania)

- [x] Fuentes Ubuntu autoalojadas (cero Google Fonts).
- [x] Dashboard: detección dinámica de servicios (PHP 8.5 ok) + `$user` escapado.
- [x] Protección anti-updates (reapply idempotente + hook APT + salvaguarda).
- [x] Fail2ban: plantilla anti-baneo de clientes (whitelist + umbrales).
- [x] Hardening WordPress: `hestia-wp-harden` (con --dry-run).
- [x] Pestaña gráfica **Seguridad/Fail2ban** (checkboxes + Desbanear/Whitelist/Blacklist).
- [x] Pestaña gráfica **WordPress** (detecta WP, Endurecer, Escanear).

Pendiente de rematar en el LAB con capturas: lint PHP, pulir CSS/layout,
verificar `v-add-firewall-rule` (blacklist).

---

## >>> PRIORIDAD Nº1 (decisión del usuario): VISTA POR DOMINIO <<<

Lo que MÁS le molesta de Hestia y más quiere de Plesk. Hestia es centrado en el
SERVICIO (vas a Correo → ves todos los dominios mezclados); Plesk es centrado en
el DOMINIO (eliges un dominio → sus pestañas Web/DNS/Correo/BBDD). Esto es lo que
de verdad hace que Plesk "se sienta" mejor.

- [ ] **Página "vista de dominio"**: eliges un dominio y arriba pestañas
      Web · DNS · Correo · BBDD · **Archivos** · WordPress que cargan datos de ESE
      dominio. Lee v-list-web-domain, v-list-dns-records, v-list-mail-accounts...
      filtrado. Mismo patrón wrapper+pestaña ya probado, a mayor escala.
- [ ] Pestaña **Archivos**: deep-link al File Manager de Hestia (/fm/) POSICIONADO
      en el public_html del dominio (el FM acepta ruta de inicio). Reutiliza el FM
      de Hestia, scoped al dominio (modelo Plesk). El usuario cliente solo ve su árbol.
      VERIFICAR EN LAB: ¿el FM de Hestia soporta drag-and-drop para subir? (zip/comprimir
      y extraer SÍ los trae). Si no hay drag-drop, sustituir el FM sería proyecto aparte.
- WRINKLE: Hestia no asocia BBDD a dominios (van por usuario) → mapear por usuario
  del dominio o por convención de nombres.
- Es la razón estratégica para quedarse en Hestia: ningún panel FOSS da vista por
  dominio + correo + DNS (CloudPanel no tiene correo/DNS). Solo se logra aquí.

---

## Fase 2 — Tras el lab (victorias rápidas, mismo patrón wrapper+pestaña)

Primero rematar Fase 1 en el lab (CSS, lint PHP), luego estas, que son las
más baratas y de uso diario:

- [ ] **Visor de logs multi-servicio**: desplegable Apache / Nginx / PHP-FPM /
      Exim / Fail2ban con filtro y búsqueda rápida. Wrapper que lee los logs.
- [ ] **Services Management**: start/stop/restart de servicios desde el panel
      (`v-restart-service`), con estado en vivo.
- [ ] **Diagnose & Repair / Salud del servidor**: envuelve los `v-check-*` de Hestia.

### Backlog menor (cuando encaje)
- [ ] Acceso directo a phpMyAdmin (el integrado, sin exponer root) desde el dashboard.
- [ ] Auto-hardening de WordPress tras Quick Install (hook al flujo de Hestia).
- [ ] Helper de despliegue de **Mautic** (`hestia-deploy-mautic`) + pestaña "Apps".

### Descartado (peso enterprise sin retorno)
- Advisor, Mass Email, Mailing Lists, Event Manager, Task Manager.

---

## Fase FINAL — Los grandes (decisión del usuario: al final)

Bloque de cierre, lo más vistoso y costoso, se hace cuando el resto esté sólido:

- [ ] **Seguimiento de correo** (equivalente a "Track Email Delivery" de Plesk).
      Parsea el log de Exim (`mainlog`): estado por mensaje (Sent/Deferred/Failed),
      remitente, destinatario, tamaño, detalle de entrega. Acciones: reenviar
      (`exim -M`), borrar (`exim -Mrm`). NO confundir con Mail Queue (solo atascados).
- [ ] **Mail Queue**: cola de Exim (`exim -bp` / `exiqgrep`), reintentar/vaciar.
- [ ] **ModSecurity WAF** en nginx: el gap de seguridad real. On/off + OWASP CRS.
      El más costoso (hay que compilar/activar el conector en nginx de Hestia).
- [ ] **Vista por dominio con pestañas** (Mail / Files / Databases / Statistics /
      WordPress...) al estilo Plesk/cPanel.
- [ ] **Temas Plesk y cPanel intercambiables a un clic**: NO es un desarrollo
      aparte — se añaden como dos temas más al selector que YA tiene el Dashboard
      Manager (junto a glass, panel...). El usuario elige look Plesk o look cPanel
      con un clic, como hoy cambia de color. Familiar, no clon (legal+mantenimiento).
      La landing "Herramientas" (rejilla Tools & Settings) y la vista por dominio
      son parte de estos temas.

## Fase publicar

- [ ] README con capturas, licencia, y (opcional) PR de mejoras selectas a upstream.
- Objetivo: uso propio + GitHub si queda pulido. Diferencial: WP Toolkit soberano.
