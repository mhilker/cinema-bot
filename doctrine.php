<?php

declare(strict_types=1);

return [
    'driver'        => 'pdo_sqlite',
    'path'          => getenv('DB_PATH'),
    'driverOptions' => [
        \PDO::ATTR_EMULATE_PREPARES   => false,
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ],
];
