<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

/*
$name = 'Pedro Minicz';
$email = 'pedrominiczexampl.com';
$phone = '1234567';
$age = 21;

$stmt = $db->prepare('INSERT INTO cadastros(nome, email, telefone, idade) VALUES (:nome, :email, :telefone, :idade)');
$stmt->execute([
  'nome'     => $name,
  'email'    => $email,
  'telefone' => $phone,
  'idade'    => $age,
]);
echo $db->lastInsertId() . '<br>';
*/

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$twig = Twig::create(__DIR__ . '/templates');
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', App\Respostas::class);
$app->get('/respostas', App\Respostas::class);
$app->get('/bhaskara', App\Bhaskara::class);
$app->get('/cadastro', App\Cadastro::class);

$app->run();
