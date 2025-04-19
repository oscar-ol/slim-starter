<?php

declare(strict_types=1);

return [
    'default' => 'mysql',
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => base_path() . '/' . (is_string($_ENV['DB_DATABASE'] ?? null) ? $_ENV['DB_DATABASE'] : ''),
            'prefix' => '',
            'options' => [
                'journal_mode' => 'WAL',
            ],
        ],
        'mysql' => [
            'driver' => 'mysql',
            'host' => is_string($_ENV['DB_HOST'] ?? null) ? $_ENV['DB_HOST'] : '',
            'port' => is_string($_ENV['DB_PORT'] ?? null) ? $_ENV['DB_PORT'] : '',
            'database' => is_string($_ENV['DB_DATABASE'] ?? null) ? $_ENV['DB_DATABASE'] : '',
            'username' => is_string($_ENV['DB_USERNAME'] ?? null) ? $_ENV['DB_USERNAME'] : '',
            'password' => is_string($_ENV['DB_PASSWORD'] ?? null) ? $_ENV['DB_PASSWORD'] : '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ],
];
