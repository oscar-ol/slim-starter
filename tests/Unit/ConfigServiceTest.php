<?php

use App\Services\Config;

beforeEach(function () {
    Config::getInstance([]);
});

it('gets config value', function () {
    Config::set('app.name', 'Slim App');
    expect(Config::get('app.name'))->toBe('Slim App');
});

it('gets default value when key does not exist', function () {
    expect(Config::get('nonexistent.key', 'default'))->toBe('default');
});

it('sets config value', function () {
    Config::set('app.name', 'New App');
    expect(Config::get('app.name'))->toBe('New App');
});

it('handles nested config values', function () {
    Config::set('database.connections.mysql.host', '127.0.0.1');
    expect(Config::get('database.connections.mysql.host'))->toBe('127.0.0.1');
});

it('returns default for nonexistent nested config', function () {
    expect(Config::get('database.connections.pgsql.host', 'default'))->toBe('default');
});

it('overrides existing config values', function () {
    Config::set('app.name', 'Original App');
    Config::set('app.name', 'Overridden App');
    expect(Config::get('app.name'))->toBe('Overridden App');
});

it('creates new nested config structure when setting value', function () {
    Config::set('new.nested.key', 'value');
    expect(Config::get('new.nested.key'))->toBe('value');
});
