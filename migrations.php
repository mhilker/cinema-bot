<?php

require __DIR__ . '/vendor/autoload.php';

return [
    'name'                 => 'Cinema-Bot Migrations',
    'migrations_namespace' => 'CinemaBot\Infrastructure\Migrations',
    'table_name'           => 'doctrine_migration_versions',
    'migrations_directory' => __DIR__ . '/src/Infrastructure/Migrations/',
];
