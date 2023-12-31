<?php

/*
    COPYRIGHT DISCLAIMER:
    --------------------

    A lot of code in this project is modified from or directly inspired by
    the excellent example from Kevin Smith: https://github.com/kevinsmith/no-framework

    Here is the related article by Kevin Smith: https://kevinsmith.io/modern-php-without-a-framework/
 */


declare(strict_types=1);

// start new session
session_start();

use Dotenv\Dotenv;
use Ds7\Semestral\Application\App;

// include autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// create application instance
$app = new App();

// set up persistence
$db = $app->setupPersistence($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);

// set up templates processing engine
$templatesProcessor = $app->setupTemplatesProcessor(__DIR__ . '/../templates');

// set up response emitter
$responseEmitter = $app->setupResponseEmitter();

// set up DI container
$container = $app->setupContainer($templatesProcessor, $responseEmitter, $db);

// set up route dispatcher
$routes = $app->setupRouting(isDebugEnabled: true);

// set up request handling middleware
$requestHandler = $app->setupMiddleware($routes, $container);

// run the application
$app->run($requestHandler, $container);

$db->close();