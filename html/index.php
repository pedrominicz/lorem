<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$twig = Twig::create(__DIR__ . '/templates');
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', App\Respostas::class);

$app->run();
