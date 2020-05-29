<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class Respostas {

  private $questions = [
    'Em ambiente profissional, seu desempenho é melhor trabalhando com autonomia ou sobre orientação constante?',
    'Conte sobre algum objetivo que perseguiu. O que sacrificou? Conseguiu o esperado?',
    'Como será o mundo pós-coronavírus? Quais ganhos nossa sociedade pode ter?',
    'Qual seu grau de conhecimento do sistema operacional Microsoft Windows?',
    'Qual seu grau de conhecimento do sistema operacional Linux?',
    'Qual seu grau de conhecimento de redes TCP/IP?',
    'Qual seu grau de conhecimento de cloud computing?',
  ];

  private $answers = [
    '',
    '',
    '',
    '',
    '',
    '',
    '',
  ];

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, 'Respostas.twig', [
      'title' => 'Respostas',
      'questions' => array_combine($this->questions, $this->answers),
    ]);
  }

}
