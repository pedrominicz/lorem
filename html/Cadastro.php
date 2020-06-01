<?php

namespace App;

use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class Cadastro {

  private PDO $db;

  // Se este fosse um aplicativo maior valeria a pena criar alguma forma de
  // _dependency injection_.
  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=lorem', 'root', 'root');
    $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  }

  // Não é a interface mais elegante, mas compre seu propósito. Em um projeto
  // maior manteria a lógica de negócio separada do _controller_.
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
    $name  = $request->getQueryParams()['name'] ?? '';
    $email = $request->getQueryParams()['email'] ?? '';
    $phone = $request->getQueryParams()['phone'] ?? '';
    $age   = $request->getQueryParams()['age'] ?? '';

    if($this->validateParams($name, $email, $phone, $age)) {
      if($this->addCadastro($name, $email, $phone, $age)) {
        $alert = $this->createAlert($age);
      } else {
        $alert = [
          'style'   => 'danger',
          'title'   => 'Erro!',
          'message' => 'E-mail ou telefone já registrado.',
        ];
      }
    }

    $view = Twig::fromRequest($request);

    return $view->render($response, 'Cadastro.twig', [
      'title' => 'Cadastro',
      'alert' => $alert,
    ]);
  }

  private function validateParams(String $name, String $email, String $phone, String $age) {
    if(!preg_match('/^[ A-Za-z]+$/', $name) || preg_match('/  /', $name)) {
      return false;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }

    $phone = preg_replace('/[^0-9]/', '', $phone);
    $length = strlen($phone);
    if($length > 15 || $length < 8) {
      return false;
    }

    if(!preg_match('/^[0-9]{0,2}$/', $age)) {
      return false;
    }

    return true;
  }

  private function addCadastro(String $name, String $email, String $phone, String $age) {
    $stmt = $this->db->prepare('INSERT INTO cadastros(nome, email, telefone, idade) VALUES (:name, :email, :phone, :age)');

    return $stmt->execute([
      'name'  => $name,
      'email' => $email,
      'phone' => $phone,
      'age'   => $age,
    ]);
  }

  private function createAlert(int $age) {
    if($this->isPrime($age)) {
      return [
        'style'   => 'success',
        'title'   => 'Que legal!',
        'message' => 'Sua idade é um número primo.',
      ];
    }

    if($age % 2 === 0) {
      return [
        'style'   => 'success',
        'title'   => 'Que legal!',
        'message' => 'Sua idade é um número par.',
      ];
    }

    return [
      'style'   => 'danger',
      'title'   => 'Que pena...',
      'message' => 'Sua idade não é um número par.',
    ];
  }

  private function isPrime(int $number) {
    // Um número é primeo se não for divisível por todos os inteiros positivos
    // maiores que dois menores que sua raiz quadrada.
    for($i = 2; $i * $i < $number; ++$i) {
      if($number % $i === 0) {
        return false;
      }
    }

    return true;
  }

}
