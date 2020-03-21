#!/bin/sh
set -e

chown -R app:app $(dirname "$DB_PATH")

if [ $(id -u) -eq 0 ]; then
   env >> /etc/environment
fi

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
       set -- php-fpm "$@"
fi

exec "$@"
