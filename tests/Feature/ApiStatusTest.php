<?php

it('returns a successful response for /api/status', function () {
    $response = get('/api/status');

    expect($response->getStatusCode())->toBe(200);

    $body = (string)$response->getBody();
    expect($body)->toBeJson();

    $data = json_decode($body, true);
    expect($data)->not->toBeNull();
    expect($data)->toHaveKey('status', 'ok');
});
