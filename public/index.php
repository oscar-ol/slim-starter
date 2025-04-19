<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../helpers/helpers.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

loadEnvironmentVariables(__DIR__ . '/../');

$app = initializeApp();
configureErrorHandling($app);
configureDatabase();
registerRoutes($app);

if (config('app.env') !== 'testing') {
    $app->run();
}

return $app;
