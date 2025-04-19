<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Slim\App;

return function (App $app): void {
    $app->get('/', HomeController::class . ':index')
        ->setName('home.index');
};
