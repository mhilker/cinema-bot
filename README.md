# Create

```
docker-compose run crawler php /app/bin/cli.php create-cinema --url="https://www.cinemotion-kino.de/hameln/kinoprogramm"
docker-compose run crawler php /app/bin/cli.php found-group --chatID="-377228860"
```

### Create Migration

``` bash
docker-compose run --user app --rm webhook /app/vendor/bin/doctrine-migrations migrations:generate --configuration /app/migrations.php --db-configuration /app/doctrine.php
```

### Migrate

``` bash
docker-compose run --user app --rm webhook /app/vendor/bin/doctrine-migrations migrations:migrate --configuration /app/migrations.php --db-configuration /app/doctrine.php --no-interaction latest
```
