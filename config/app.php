<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'Slim Framework Boilerplate',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
];
