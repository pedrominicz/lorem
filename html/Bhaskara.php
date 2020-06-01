<?php

namespace App;

use Parsedown;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class Bhaskara {

  private $a, $b, $c;

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
    $this->a = $request->getQueryParams()['a'] ?? '';
    $this->b = $request->getQueryParams()['b'] ?? '';
    $this->c = $request->getQueryParams()['c'] ?? '';

    if($this->validateParams()) {
      $answer = $this->solve();
    }

    $view = Twig::fromRequest($request);

    return $view->render($response, 'Bhaskara.twig', [
      'title' => 'Bhaskara',
      'answer' => $answer,
    ]);
  }

  private function validateParams() {
    $this->a = str_replace(',', '.', $this->a);
    $this->b = str_replace(',', '.', $this->b);
    $this->c = str_replace(',', '.', $this->c);

    if(!preg_match('/^[-0-9.]+$/', $this->a) || substr_count('.', $this->a) > 1) {
      return false;
    }

    if(!preg_match('/^[-0-9.]+$/', $this->b) || substr_count('.', $this->b) > 1) {
      return false;
    }

    if(!preg_match('/^[-0-9.]+$/', $this->c) || substr_count('.', $this->c) > 1) {
      return false;
    }

    $this->a = floatval($this->a);
    $this->b = floatval($this->b);
    $this->c = floatval($this->c);

    return true;
  }

  // A solução poderia ser calculada no cliente.
  private function solve() {
    if($this->a === 0.0) {
      return [
        'style'   => 'danger',
        'title'   => 'Erro!',
        'message' => 'Formula não é uma equação de segundo grau.',
      ];
    }

    $delta = $this->b * $this-> b - (4.0 * $this->a * $this->c);

    if($delta < 0.0) {
      return [
        'style' => 'success',
        'title' => 'Não há raiz!',
        'delta' => strval($delta),
      ];
    }

    if($delta === 0.0) {
      return [
        'style' => 'success',
        'title' => 'Há uma raiz!',
        'delta' => strval($delta),
        'x'     => strval(-$this->b / (2.0 * $this->a)),
      ];
    } else {
      var_dump((-$this->b + sqrt($delta)) / (2.0 * $this->a));
      var_dump((-$this->b - sqrt($delta)) / (2.0 * $this->a));
      return [
        'style' => 'success',
        'title' => 'Existem duas raizes!',
        'delta' => strval($delta),
        'x1'    => strval((-$this->b + sqrt($delta)) / (2.0 * $this->a)),
        'x2'    => strval((-$this->b - sqrt($delta)) / (2.0 * $this->a)),
      ];
    }
  }

}
