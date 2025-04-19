<?php

use App\Exceptions\Handler;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

it('handles not found exception', function () {
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/non-existent-route');
    $exception = new HttpNotFoundException($request);
    $responseFactory = new ResponseFactory();

    $response = Handler::handle($request, $exception, false, $responseFactory);

    expect($response->getStatusCode())->toBe(404);
    expect((string)$response->getBody())->toContain('Not Found');
});

it('handles generic exception with details', function () {
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/');
    $exception = new Exception('Test exception');
    $responseFactory = new ResponseFactory();

    $response = Handler::handle($request, $exception, true, $responseFactory);

    expect($response->getStatusCode())->toBe(500);
    expect((string)$response->getBody())->toContain('Test exception');
});

it('handles generic exception without details', function () {
    $request = (new ServerRequestFactory())->createServerRequest('GET', '/');
    $exception = new Exception('Test exception');
    $responseFactory = new ResponseFactory();

    $response = Handler::handle($request, $exception, false, $responseFactory);

    expect($response->getStatusCode())->toBe(500);
    expect((string)$response->getBody())->toContain('Internal Server Error');
    expect((string)$response->getBody())->not->toContain('Test exception');
});
