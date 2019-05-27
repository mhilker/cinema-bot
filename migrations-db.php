<?php

$file = getenv('DB_PASSWORD_FILE');

if ($file === false) {
    $password = getenv('DB_PASSWORD');
} else {
    $password = file_get_contents($file);
}

return [
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => $password,
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'driver'   => 'pdo_mysql',
];
