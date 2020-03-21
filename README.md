# Cinema Bot

```
echo "YOUR_TELEGRAM_TOKEN" > telegram-token.local
```

### Create Migration

``` bash
docker-compose run --user app --rm webhook /app/vendor/bin/doctrine-migrations migrations:generate --configuration /app/migrations.php --db-configuration /app/doctrine.php
```

### Migrate

``` bash
docker-compose run --user app --rm webhook /app/vendor/bin/doctrine-migrations migrations:migrate --configuration /app/migrations.php --db-configuration /app/doctrine.php --no-interaction latest
```
