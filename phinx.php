<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeders',
    ],
    'environments' => [
        'default_environment' => $_ENV['DB_CONNECTION'],
        'sqlite' => [
            'adapter' => 'sqlite',
            'name' => __DIR__ . '/database/database.sqlite',
        ],
        'mysql' => [
            'adapter' => 'mysql',
            'name' => $_ENV['DB_DATABASE'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASSWORD'],
        ],
    ],
];
