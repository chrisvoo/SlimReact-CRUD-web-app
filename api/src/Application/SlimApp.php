<?php

namespace App\Application;

use DI\Container;
use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

class SlimApp
{
    /**
     * The DI Container.
     *
     * @var Container
     */
    private $container;

    public function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        if ($_ENV["APP_ENV"] === "production") { // Should be set to true in production
            $containerBuilder->enableCompilation(__DIR__ . '/../../var/cache');
        }

        // Set up settings
        $settings = require __DIR__ . '/../../app/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../app/dependencies.php';
        $dependencies($containerBuilder);

        // Set up repositories
        $repositories = require __DIR__ . '/../../app/repositories.php';
        $repositories($containerBuilder);

        // Build PHP-DI Container instance
        $this->container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($this->container);
        $app = AppFactory::create();
        $app->setBasePath($_ENV["APP_BASE_PATH"]);

        // Register middleware
        $middleware = require __DIR__ . '/../../app/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../../app/routes.php';
        $routes($app);

        return $app;
    }
}