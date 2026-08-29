# Roadmap — Dashboard Manager (fork Elurk)

Objetivo: panel HestiaCP con la comodidad de Plesk/cPanel, 100% FOSS y soberano.
Que un usuario que viene de Plesk o cPanel se sienta en casa, sin items fantasma
ni clon legal — arquitectura y vocabulario familiares sobre funciones reales de Hestia.

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

## Fase 2 — Tras el lab (paridad Plesk, por valor)

Todas siguen el mismo patrón probado: wrapper CLI acotado + pestaña gráfica.

### Alta prioridad (uso diario, muy pedido)
- [ ] **Seguimiento de correo** (equivalente a "Track Email Delivery" de Plesk).
      Parsea el log de Exim (`mainlog`): estado por mensaje (Sent/Deferred/Failed),
      remitente, destinatario, tamaño, detalle de entrega. Acciones: reenviar
      (`exim -M`), borrar (`exim -Mrm`). NO confundir con Mail Queue (solo atascados).
- [ ] **Visor de logs multi-servicio**: desplegable Apache / Nginx / PHP-FPM /
      Exim / Fail2ban con filtro y búsqueda rápida. Wrapper que lee los logs.
- [ ] **Vista por dominio con pestañas** (Mail / Files / Databases / Statistics /
      WordPress...) al estilo Plesk/cPanel. Núcleo del "tema familiar".

### Media prioridad
- [ ] **Mail Queue**: cola de Exim (`exim -bp` / `exiqgrep`), reintentar/vaciar.
- [ ] **Services Management**: start/stop/restart de servicios desde el panel
      (`v-restart-service`), con estado en vivo.
- [ ] **Diagnose & Repair / Salud del servidor**: envuelve los `v-check-*` de Hestia.
- [ ] **ModSecurity WAF** en nginx: el gap de seguridad real. On/off + OWASP CRS.
      El más costoso (hay que compilar/activar el conector en nginx de Hestia).

### Baja prioridad / backlog
- [ ] Acceso directo a phpMyAdmin (el integrado, sin exponer root) desde el dashboard.
- [ ] Auto-hardening de WordPress tras Quick Install (hook al flujo de Hestia).
- [ ] Helper de despliegue de **Mautic** (`hestia-deploy-mautic`) + pestaña "Apps"
      que detecte Mautic además de WordPress.

### Descartado (peso enterprise sin retorno)
- Advisor, Mass Email, Mailing Lists, Event Manager, Task Manager.

---

## Fase 3 — Tema "familiar Plesk/cPanel"

- [ ] Landing "Herramientas" tipo Tools & Settings: rejilla de tarjetas agrupadas
      (Seguridad / Correo / Dominios / Bases de datos / Servidor) con vocabulario
      reconocible, enlazando a funciones reales de Hestia y a nuestras pestañas.
- [ ] Vista por dominio con pestañas (ver Fase 2).
- Sin clonar la marca de Plesk/cPanel (legal + mantenimiento): familiar, no copia.

## Fase 4 — Publicar

- [ ] README con capturas, licencia, y (opcional) PR de mejoras selectas a upstream.
- Objetivo: uso propio + GitHub si queda pulido. Diferencial: WP Toolkit soberano.
