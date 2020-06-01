<?php

namespace App;

use Parsedown;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class Bhaskara {

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'Respostas.twig', [
      'title' => 'Respostas',
    ]);
  }

}
