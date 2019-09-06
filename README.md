# Create

```
docker-compose run crawler php /app/tools/doctrine-migrations migrations:generate
docker-compose run crawler php /app/bin/cli.php create-cinema --url="https://www.cinemotion-kino.de/hameln/kinoprogramm"
docker-compose run crawler php /app/bin/cli.php found-group --chatID="-377228860"
```