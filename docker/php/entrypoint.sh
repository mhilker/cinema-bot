#!/bin/sh
set -e

if [ $(id -u) -eq 0 ]; then
   /usr/bin/env >> /etc/environment
fi

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
       set -- php-fpm "$@"
fi

exec "$@"
