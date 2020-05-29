<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write("hello world");

    return $response;
});

$app->run();
