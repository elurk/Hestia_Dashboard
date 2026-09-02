#!/bin/bash
# deploy-elurk-theme.sh <usuario_hestia>
#
# Aplica el tema Elurk (POC) por el MECANISMO NATIVO de Hestia: copia el CSS a
# la carpeta de temas y pone THEME='elurk' en la config del usuario, que es lo
# que Hestia lee para cargar el CSS. NO usa el theme-manager (que no aplica).
#
# Uso:   sudo bash deploy-elurk-theme.sh elurk
# (pon TU usuario admin de Hestia; en tu lab es 'elurk')
#
# Revertir: sudo bash deploy-elurk-theme.sh elurk --revert

set -u
USER_ARG="${1:-}"
REVERT="${2:-}"
THEMES_DIR="/usr/local/hestia/web/css/themes"
SRC_DIR="$(cd "$(dirname "$0")" && pwd)"
CONF="/usr/local/hestia/data/users/$USER_ARG/user.conf"

[ -n "$USER_ARG" ] || { echo "Uso: sudo bash deploy-elurk-theme.sh <usuario> [--revert]"; exit 1; }
[ -f "$CONF" ] || { echo "No existe $CONF (¿usuario correcto?)"; exit 1; }

if [ "$REVERT" = "--revert" ]; then
    sed -i "s/^THEME=.*/THEME='dark'/" "$CONF"
    grep -q "^THEME=" "$CONF" || echo "THEME='dark'" >> "$CONF"
    echo "Revertido a THEME='dark'. Recarga con Ctrl+Shift+R."
    exit 0
fi

# 1. Copiar el CSS del tema a la carpeta de temas de Hestia
cp "$SRC_DIR/elurk.css" "$THEMES_DIR/elurk.css"
chown hestiaweb:hestiaweb "$THEMES_DIR/elurk.css" 2>/dev/null || true
chmod 644 "$THEMES_DIR/elurk.css"
echo "CSS copiado a $THEMES_DIR/elurk.css"

# 2. Poner THEME='elurk' en la config del usuario (lo que Hestia lee)
if grep -q "^THEME=" "$CONF"; then
    sed -i "s/^THEME=.*/THEME='elurk'/" "$CONF"
else
    echo "THEME='elurk'" >> "$CONF"
fi
echo "THEME='elurk' aplicado al usuario $USER_ARG."
echo
echo "AHORA: recarga el panel con Ctrl+Shift+R (fuerza recarga del CSS)."
echo "Si no cambia, prueba tambien seleccionar 'elurk' en el desplegable de"
echo "tema del panel (icono usuario -> Editar -> Tema)."
