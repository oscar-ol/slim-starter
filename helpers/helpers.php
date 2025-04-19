<?php

use App\Services\Config;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/../' . $path;
    }
}

if (!function_exists('database_path')) {
    function database_path(string $path = ''): string
    {
        return __DIR__ . '/../database' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config_path')) {
    function config_path(string $path = ''): string
    {
        return __DIR__ . '/../config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('loadEnvironmentVariables')) {
    function loadEnvironmentVariables(string $path): void
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }
}

if (!function_exists('initializeApp')) {
    /**
     * @return App<DI\Container>
     */
    function initializeApp(): App
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);

        $container = $builder->build();
        $app = AppFactory::createFromContainer($container);

        $csrf = new Guard($app->getResponseFactory());
        $app->add($csrf);

        return $app;
    }
}

if (!function_exists('configureErrorHandling')) {
    /**
     * @param  App<DI\Container>  $app
     */
    function configureErrorHandling(App $app): void
    {
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        $errorMiddleware->setDefaultErrorHandler(function (
            Request $request,
            Throwable $exception,
            bool $displayErrorDetails
        ) use ($app): ResponseInterface {
            return \App\Exceptions\Handler::handle(
                $request,
                $exception,
                $displayErrorDetails,
                $app->getResponseFactory()
            );
        });
    }
}

if (!function_exists('configureDatabase')) {
    function configureDatabase(): void
    {
        $capsule = new Capsule();
        $config = require __DIR__ . '/../config/database.php';
        $capsule->addConnection($config['connections'][$config['default']]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

if (!function_exists('registerRoutes')) {
    /**
     * @param  App<DI\Container>  $app
     */
    function registerRoutes(App $app): void
    {
        if (file_exists(__DIR__ . '/../routes/web.php')) {
            $routesWeb = require __DIR__ . '/../routes/web.php';
            $routesWeb($app);
        }

        if (file_exists(__DIR__ . '/../routes/api.php')) {
            $routesApi = require __DIR__ . '/../routes/api.php';
            $routesApi($app);
        }
    }
}

if (!function_exists('renderView')) {
    /**
     * @param  array<string, mixed>  $data
     */
    function renderView(string $view, array $data = []): string
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader(base_path('resources/views'));
            $twig = new Environment($loader, [
                'cache' => base_path('storage/cache/views'),
                'debug' => true,
            ]);
        }

        return $twig->render("$view.twig", $data);
    }
}

if (!function_exists('route')) {
    /**
     * @param  array<string, string>  $params
     */
    function route(string $name, array $params = []): string
    {
        $app = initializeApp();
        $routeParser = $app->getRouteCollector()->getRouteParser();

        return $routeParser->urlFor($name, $params);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('dd')) {
    /**
     * @param  mixed  ...$vars
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        exit(1);
    }
}
