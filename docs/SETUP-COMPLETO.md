# Montaje completo, de cero a funcionando

De un Ubuntu 26.04 recién instalado hasta HestiaCP con el Dashboard Manager
(fork Elurk) y sus pestañas. Todo como **root** en la máquina Hestia.

---

## Paso 1 — La VM (Proxmox)

1. Crea una **VM** (no LXC) en Proxmox.
2. SO: **Ubuntu Server 26.04 LTS** (ISO). Instalación mínima: solo "OpenSSH server".
3. Recursos: 2 vCPU, 4 GB RAM, 40 GB disco.
4. Red en bridge con IP accesible desde tu equipo. Anótala.
5. Hostname con FQDN, p.ej. `panel.tudominio-lab.com`.

> Alternativa a Ubuntu 26.04 si quieres máxima estabilidad mientras aprendes:
> Debian 12. El fork funciona igual en ambos.

## Paso 2 — Instalar HestiaCP

SSH a la VM como root:

```bash
apt update && apt upgrade -y
wget https://raw.githubusercontent.com/hestiacp/hestiacp/release/install/hst-install.sh
bash hst-install.sh
```

En las preguntas del instalador, para replicar el escenario real (webs + correo
+ DNS), di **SÍ** a: nginx+apache (o solo nginx), **Exim + Dovecot**, **BIND**,
**MariaDB**. ClamAV/SpamAssassin puedes decir NO en el lab (ahorran RAM).

Al terminar te da la URL del panel: `https://IP:8083` y las credenciales admin.
Entra y comprueba que ves el panel estándar de Hestia.

> **Snapshot en Proxmox ahora**, llamado `hestia-limpio`. Cada prueba parte de aquí.

## Paso 3 — Datos de prueba (para ver algo en las pestañas)

Desde el panel, crea:
- 1 usuario cliente.
- 1 dominio web y, con **Quick Install App**, un WordPress (para la pestaña WP).
- 1 dominio de correo + 2 buzones (para probar fail2ban).

## Paso 4 — Desplegar el fork

```bash
apt install -y git
git clone https://github.com/elurk/Hestia_Dashboard.git
cd Hestia_Dashboard
git checkout elurk/soberania
bash install.sh
```

Instala: dashboard con menú lateral, 4 temas, **fuentes autoalojadas**, CLI
`hestia-theme`, **pestañas Seguridad y WordPress**, **protección anti-updates**,
y los wrappers de backend.

## Paso 5 — Activar el dashboard nuevo

```bash
v-change-sys-config-value ALT_DASHBOARD true
```

Recarga el panel: deberías aterrizar en el dashboard con widgets y ver en el
menú lateral (como admin) las entradas **Security** y **WordPress**.

## Paso 6 — Configurar fail2ban (anti-baneo de clientes)

```bash
cd ~/Hestia_Dashboard
cp fail2ban/zzz-elurk-overrides.local /etc/fail2ban/jail.d/
nano /etc/fail2ban/jail.d/zzz-elurk-overrides.local   # pon tus IPs en 'ignoreip'
fail2ban-client -t          # PRUEBA la config antes; si da error, NO reinicies
systemctl restart fail2ban
fail2ban-client status      # los jails de Hestia se llaman *-iptables
```

Ahora la pestaña **Security** del panel lista las IPs baneadas y permite
desbanear / whitelist / blacklist con checkboxes.

## Paso 7 — Verificar todo

```bash
# Fuentes locales, cero Google:
grep -rl googleapis /usr/local/hestia/web/css/themes/custom/ ; echo "(vacío = OK)"
ls /usr/local/hestia/web/css/themes/custom/fonts/ | head

# Pestañas desplegadas:
ls /usr/local/hestia/web/list/security/ /usr/local/hestia/web/list/wp/

# Wrappers y sudoers:
ls -l /usr/local/hestia/bin/v-fail2ban-action /usr/local/hestia/bin/v-wp-manage
sudo -l -U hestiaweb | grep -E "fail2ban|wp-manage"

# WordPress hardening en seco (sin cambiar nada):
hestia-wp-harden --user <usuario> --domain <dominio> --dry-run
```

Entra al panel y **haz capturas** de las dos pestañas nuevas y del dashboard.
Con eso iteramos el CSS y el layout.

## Paso 8 — Probar que sobrevive a un update

```bash
# Fuerza una diferencia y reaplica a mano:
/usr/local/hestia/bin/hestia-theme-reapply
cat /var/log/hestia/theme-reapply.log
# Tras un 'apt upgrade' real de Hestia, el hook lo hace solo.
```

## Rollback total

```bash
cd ~/Hestia_Dashboard && bash uninstall.sh
```

Restaura los archivos core, borra dashboard, temas, fuentes, pestañas, CLI,
hook y wrappers. Respeta tus reglas de firewall y tu whitelist.

---

## Qué mirar en las capturas (para pulir)

1. **Dashboard**: ¿se descuadra el menú sobre las tarjetas? ¿textos cortados?
2. **Security**: ¿la tabla y la barra de acciones se ven bien? ¿los checkboxes?
3. **WordPress**: ¿detecta tu WP? ¿los botones responden?
4. **Fuentes**: ¿el texto se ve en Ubuntu (no en fuente de sistema genérica)?

Mándame esas 3-4 capturas y ajusto el CSS y lo que haga falta.
