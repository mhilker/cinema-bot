# Cinema Bot

## Run

``` bash
echo "YOUR_TELEGRAM_TOKEN" > telegram-token.local
docker-compose up
```

## Create Migration

``` bash
docker-compose run --rm php /app/vendor/bin/doctrine-migrations migrations:generate --configuration /app/migrations.php --db-configuration /app/doctrine.php
```

## Migrate

``` bash
docker-compose run --rm php /app/vendor/bin/doctrine-migrations migrations:migrate --configuration /app/migrations.php --db-configuration /app/doctrine.php --no-interaction latest
```
