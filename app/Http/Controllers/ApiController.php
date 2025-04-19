<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    public function status(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['status' => 'ok']) ?: '');

        return $response->withHeader('Content-Type', 'application/json');
    }
}
