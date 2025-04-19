<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class AuthMiddleware
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $authenticated = $this->isUserAuthenticated();

        if (!$authenticated) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write('Unauthorized');

            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }

    private function isUserAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }
}
