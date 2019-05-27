FROM nginx:1.11-alpine

COPY ./docker/nginx/entrypoint.sh /etc/nginx/entrypoint.sh
COPY ./docker/nginx/nginx.tmpl.conf /etc/nginx/default.tmpl.conf

ENTRYPOINT ["/etc/nginx/entrypoint.sh"]
