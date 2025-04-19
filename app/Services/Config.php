<?php

declare(strict_types=1);

namespace App\Services;

class Config
{
    private static ?Config $instance = null;

    /** @var array<string, mixed> */
    private array $configs = [];

    private function __construct()
    {
        $files = glob(config_path() . '/*.php') ?: [];
        foreach ($files as $file) {
            $this->configs[basename($file, '.php')] = require $file;
        }
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function getInstance(array $config = []): Config
    {
        if (!self::$instance instanceof \App\Services\Config || $config !== []) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::getInstance()->getInstanceValue($key, $default);
    }

    public static function set(string $key, mixed $value): void
    {
        self::getInstance()->setInstanceValue($key, $value);
    }

    private function getInstanceValue(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);

        if (!isset($this->configs[$file])) {
            return $default;
        }

        $value = $this->configs[$file];
        foreach ($keys as $segment) {
            if (!array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private function setInstanceValue(string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);

        if (!isset($this->configs[$file])) {
            $this->configs[$file] = [];
        }

        $config = &$this->configs[$file];
        foreach ($keys as $segment) {
            if (!isset($config[$segment]) || !is_array($config[$segment])) {
                $config[$segment] = [];
            }
            $config = &$config[$segment];
        }

        $config = $value;
    }
}
