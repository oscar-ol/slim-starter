<?php

it('loads the home page successfully', function () {
    $response = get('/');

    expect($response->getStatusCode())->toBe(200);

    $body = (string)$response->getBody();
    expect($body)->toContain('Welcome to Slim Framework');
});
