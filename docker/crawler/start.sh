#!/usr/bin/env sh

wait-for-it ${DB_HOST}:${DB_PORT} --timeout=60 --strict -- /app/bin/cli.php crawl-cinema
