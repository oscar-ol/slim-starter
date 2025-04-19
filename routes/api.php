<?php

declare(strict_types=1);

use App\Http\Controllers\ApiController;
use Slim\App;

return function (App $app): void {
    $app->get('/api/status', ApiController::class . ':status')
        ->setName('api.status');
};
