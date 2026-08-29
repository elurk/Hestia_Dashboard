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
systemctl restart fail2ban

# Comprobar
fail2ban-client status
fail2ban-client status dovecot     # ver baneados del correo
fail2ban-client set dovecot unbanip <IP>   # desbanear a mano
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
