#!/bin/sh
set -e

chown -R app:app $(dirname "$DB_PATH")

env >> /etc/environment

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
       set -- php-fpm "$@"
fi

exec "$@"
