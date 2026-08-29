# Laboratorio HestiaCP — Runbook

Objetivo: HestiaCP limpio en una VM de Proxmox para probar el Dashboard Manager
antes de tocar el VPS de OVH del cliente.

## 1. Crear la VM en Proxmox

Requisitos: **VM completa, NO contenedor LXC** (Hestia necesita quota de disco y
módulos de kernel para fail2ban que en LXC dan problemas).

- SO: elige según lo que vayas a poner en el VPS de OVH (el lab debe replicarlo):
  - **Ubuntu 26.04 LTS** — soportado desde Hestia 1.9.7, consolidado en 1.10.0. Es la
    LTS más nueva; réplica ideal si producción va con 26.04. Aviso: soporte reciente,
    usa la nueva repo Sury para multi-PHP y requiere MariaDB >= 11.8 (lo hace el instalador).
  - **Debian 12 (Bookworm)** — la opción más rodada y estable. La que menos sorpresas da
    mientras desarrollas. Recomendada si prefieres que el SO no estorbe.
  - El desarrollo del fork (CSS + plantillas PHP) es agnóstico al SO: cambiar de base
    después no te hace perder trabajo.
- Recursos de prueba: 2 vCPU, 4 GB RAM, 40 GB disco.
- Red: IP accesible desde tu equipo (bridge). Anota la IP.
- Hostname con FQDN, p.ej. `lab.hestia.local` o un subdominio real de pruebas.

Vía interfaz Proxmox: subir ISO de Debian 12 → crear VM → instalación mínima
(solo "SSH server" y "standard utilities", sin entorno de escritorio).

## 2. Instalar HestiaCP

SSH a la VM como root y:

```bash
apt update && apt upgrade -y
wget https://raw.githubusercontent.com/hestiacp/hestiacp/release/install/hst-install.sh
bash hst-install.sh
```

El instalador pregunta por servicios. Para replicar el escenario del cliente
(webs WordPress + correo + DNS), acepta:
- Apache + Nginx (nginx como proxy), o Nginx solo si lo prefieres
- **Exim + Dovecot** (correo) → SÍ, necesitamos DKIM/SPF para las pruebas
- **BIND** (DNS) → SÍ
- **MariaDB** → SÍ
- **ClamAV + SpamAssassin** → opcional en lab (comen RAM; puedes decir NO para ahorrar)
- fail2ban → SÍ (lo vamos a tunear)

Al final da la URL del panel (`https://IP:8083`) y credenciales admin.

## 3. Snapshot inicial

En Proxmox, haz un **snapshot "hestia-limpio"** ANTES de instalar nada más.
Así cada prueba del Dashboard Manager parte de cero con un clic.

## 4. Datos de prueba

Crea desde el panel para tener con qué probar la UI:
- 1 usuario cliente
- 1 dominio web con WordPress (Quick Install App)
- 1 dominio de correo + 2 buzones
- 1 base de datos

## 5. Instalar el Dashboard Manager (nuestro fork)

Una vez tengamos el fork clonado, se copia a la VM y:

```bash
cd Hestia_Dashboard
sudo bash install.sh
```

Documentar con capturas qué se ve bien y qué se descuadra.
Snapshot "dashboard-instalado" para poder volver.

---
NOTA: este runbook es para el LAB. El deploy a OVH se hace solo cuando el fork
esté sólido, y con su propio checklist aparte.
