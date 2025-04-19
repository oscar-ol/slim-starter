<?php

use App\Middlewares\AuthMiddleware;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

it('allows authenticated user', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['user_id'] = 1;

    $middleware = new AuthMiddleware();
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->method('handle')->willReturn(new Response(200));

    $response = $middleware($request, $handler);

    expect($response->getStatusCode())->toBe(200);

    unset($_SESSION['user_id']);
});

it('blocks unauthenticated user', function () {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    $middleware = new AuthMiddleware();
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/test');
    $handler = $this->createMock(RequestHandlerInterface::class);

    $response = $middleware($request, $handler);

    expect($response->getStatusCode())->toBe(401);
    expect((string)$response->getBody())->toBe('Unauthorized');
});
