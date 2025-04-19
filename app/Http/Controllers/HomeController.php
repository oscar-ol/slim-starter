<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write(renderView('index', [
            'title' => 'Slim Framework Boilerplate',
            'content_title' => 'Welcome to Slim Framework',
            'content' => 'This is a Laravel-inspired Slim Framework boilerplate.',
        ]));

        return $response;
    }
}
