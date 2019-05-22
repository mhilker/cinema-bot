#!/usr/bin/env bash

wait-for-it ${DB_HOST}:${DB_PORT} --timeout=60 --strict -- /app/bin/cli.php crawl-site
