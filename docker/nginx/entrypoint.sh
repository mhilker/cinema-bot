#!/bin/sh

cp /etc/nginx/default.tmpl.conf /etc/nginx/conf.d/default.conf
sed -i "s/PHP_HOSTNAME/${PHP_HOSTNAME}/" /etc/nginx/conf.d/default.conf

nginx -g "daemon off;"
