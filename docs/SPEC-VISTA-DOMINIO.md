# Spec — Experiencia tipo Plesk sobre HestiaCP (tema Elurk)

Objetivo definido por el usuario con capturas de Plesk (2026-08-29). Este es el
plano de la pieza grande del fork. Se construye por partes, tab a tab.

## 1. Layout: menú en BARRA LATERAL

Como Plesk: menú vertical a la izquierda (no el menú horizontal de Hestia).
Secciones globales: Inicio, Clientes, Dominios, Suscripciones, Planes, y
Administración del servidor (Herramientas, Estadísticas, Extensiones, etc.).

- Reestructurar el layout del panel a sidebar. Es cambio de `panel.php` (patched)
  + CSS del tema Elurk. Riesgo medio (layout), pero acotado.

## 2. VISTA POR DOMINIO con pestañas (el corazón)

Eliges un dominio/suscripción y arriba pestañas de ESE dominio (modelo Plesk).
Pestañas objetivo (de las capturas de Plesk):

- **Panel de información**: rejilla de tarjetas agrupadas:
  - Archivos y BBDD: Info de conexión (FTP/DB), Archivos, Bases de datos, FTP,
    Backup y restauración, Copia de sitio web.
  - Herramientas de desarrollo: PHP (versión), Registros/logs, Tareas programadas,
    Git, PHP Composer, Gestión de WordPress, SEO, Importación de sitios.
  - Seguridad: Certificados SSL/TLS, Firewall app web (WAF), Advisor, Directorios
    protegidos con contraseña.
- **Hosting y DNS**: Config de hosting, Apache y nginx, DNS (zona del dominio).
- **Correo**: Cuentas de correo (de ESE dominio), Config de correo, Limitar
  salientes, Seguimiento de envío (Track Email Delivery), Importación, Comprobar config.
- **Archivos**: File Manager posicionado en el public_html del dominio.
- **Bases de datos**: BBDD del dominio, con acciones por BD (phpMyAdmin, info
  conexión, copiar, exportar/importar volcado, verificar/reparar).
- **Estadísticas**: uso disco/tráfico del dominio.
- **WordPress / SEO**: gestión WP (ya tenemos motor) + SEO.

Panel lateral derecho: accesos rápidos (Backup, BBDD, Tareas programadas, Control
correo saliente, WordPress, Registro de acciones) + info de la suscripción
(disco, tráfico).

### Mapeo a Hestia (feasibilidad)
- Web/DNS/Correo/Archivos/Estadísticas/SSL/PHP/Cron: DIRECTO, leyendo los v-list-*
  filtrados por dominio. El File Manager acepta ruta de inicio (scoped).
- **BBDD ↔ dominio**: Hestia asocia BBDD al USUARIO, no al dominio (Plesk sí las
  asocia a la suscripción). Necesita mapeo por usuario del dominio / convención de
  nombres. Salvable pero no 1:1.
- Git / PHP Composer / Advisor / Performance Booster / Importación de sitios:
  NO nativos en Hestia. Se omiten o se construyen aparte (backlog).

## 3. USUARIOS Y PERMISOS (el punto MÁS difícil — aviso honesto)

Plesk: usuarios adicionales con ROL (Administrador, WebMaster, Application User,
Contable, Limited Webmaster) y acceso solo a las SUSCRIPCIONES asignadas.

**Choque de arquitectura**: Hestia NO tiene este modelo. En Hestia un "usuario" ES
la cuenta que posee sus dominios; solo hay admin vs user, sin subusuarios con roles
granulares dentro de los dominios de otro. Replicar el modelo Plesk (dar a alguien
rol "webmaster" solo sobre 2 dominios) **no es nativo** y sería construir una capa
de permisos propia encima — grande y peleada con la arquitectura de Hestia.

Opciones a decidir la próxima sesión:
- (a) Aceptar el modelo de Hestia (1 usuario = 1 cuenta con sus dominios).
- (b) Construir una capa de subusuarios+roles propia (proyecto gordo, frágil).
- (c) Híbrido: roles simples a nivel de acceso al panel, sin la granularidad Plesk.
Es el item de mayor riesgo/incertidumbre de toda la spec.

## 4. Tema Elurk = lo más parecido a Plesk

Estética Plesk (sidebar, fondo claro, tarjetas limpias, tipografía sobria) con
paleta Elurk 2026. Base: el elurk.css que ya creamos, evolucionado hacia el look
Plesk (claro, ordenado, mucho aire).

---

## Plan de construcción (incremental, tab a tab)

1. Layout sidebar + tema Elurk estilo Plesk (base visual).
2. Vista por dominio: esqueleto + pestaña Panel de información (rejilla de tarjetas).
3. Pestañas una a una: Hosting/DNS → Correo → Archivos → BBDD → Estadísticas → WP/SEO.
4. Usuarios y permisos: decidir modelo (a/b/c) antes de tocar código.

PENDIENTE del usuario: ofrece pasar CAPTURAS de cada pestaña de Plesk — SÍ, muy
útiles para construir cada una fiel. Pedirlas tab a tab según se construyan.

NOTA de magnitud: esto es el "Nivel 3" (frontend tipo Plesk sobre Hestia) que se
advirtió: es grande y hay que darle mantenimiento. La decisión ya está tomada
(el usuario lo quiere); se hace incremental para ver valor desde la primera pestaña.
