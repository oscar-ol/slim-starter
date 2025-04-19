<?php

declare(strict_types=1);

return [
    'host' => $_ENV['MAIL_HOST'] ?? '',
    'smtp_auth' => $_ENV['MAIL_SMTP_AUTH'] ?? true,
    'username' => $_ENV['MAIL_USERNAME'] ?? '',
    'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    'port' => (int)($_ENV['MAIL_PORT'] ?? 587),
    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? ($_ENV['APP_NAME'] ?? 'no-reply'),
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? '',
];
