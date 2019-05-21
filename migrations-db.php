<?php

return [
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => file_get_contents(getenv('DB_PASSWORD_FILE')),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'driver'   => 'pdo_mysql',
];
