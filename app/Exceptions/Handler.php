<?php

declare(strict_types=1);

namespace App\Exceptions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Throwable;

class Handler
{
    public static function handle(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        ResponseFactoryInterface $responseFactory
    ): ResponseInterface {
        if ($exception instanceof HttpNotFoundException) {
            $response = $responseFactory->createResponse();
            $response->getBody()->write(renderView('errors/404', [
                'title' => 'Page Not Found',
            ]));

            return $response->withStatus(404);
        }

        $response = $responseFactory->createResponse();
        $response->getBody()->write(renderView('errors/500', [
            'title' => 'Internal Server Error',
            'message' => config('app.env') !== 'production' && $displayErrorDetails ? $exception->getMessage() : 'Internal Server Error',
            'trace' => config('app.env') !== 'production' && $displayErrorDetails ? $exception->getTraceAsString() : '',
        ]));

        return $response->withStatus(500);
    }
}
