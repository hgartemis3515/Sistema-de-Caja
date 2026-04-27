#!/bin/bash
set -e
APP="/var/www/html/facturacionv8"
if [ -d "$APP" ]; then
  mkdir -p "$APP/var/sessions" "$APP/cache"
  chown -R www-data:www-data "$APP/var" "$APP/cache" 2>/dev/null || true
  chmod -R ug+rwX "$APP/var" "$APP/cache" 2>/dev/null || true
fi
exec "$@"
