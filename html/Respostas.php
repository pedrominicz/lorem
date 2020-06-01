<?php

// Está é a página de respostas para o pequenho aplicativo criado para o
// processo de seleção. O aplicativo usa Slim 4, um micro framework de PHP que,
// de forma similar ao Node.js, permite que o programador escolha como
// estruturar os diretórios. Enquanto a hierarquia criada por outros frameworks
// é de extrema utilidade para projetos grandes, fica mais elegante usar o Slim
// e dividir tudo entre as pastas `html` e `php`.

namespace App;

use Parsedown;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

// A classe `Respostas` contém apenas o conteúdo das perguntas e respostas. A
// classe usa o template `Respostas.twig` para renderizar o HTML. Note que as
// perguntas e respostas estão escritas em Markdown e são convertidas para HTML
// antes de serem enviadas ao Twig.
final class Respostas {

  private $questions = [
    'Em ambiente profissional, seu desempenho é melhor trabalhando com autonomia ou sobre orientação constante?',
    'Conte sobre algum objetivo que perseguiu. O que sacrificou? Conseguiu o esperado?',
    'Como será o mundo pós-coronavírus? Quais ganhos nossa sociedade pode ter?',
    'Qual seu grau de conhecimento do sistema operacional Microsoft Windows?',
    'Qual seu grau de conhecimento do sistema operacional Linux?',
    'Qual seu grau de conhecimento de redes TCP/IP?',
    'Qual seu grau de conhecimento de _cloud computing_?',
    'Por que usar `++$i` é mais eficiente que usar `$i++` em certas situações?',
  ];

  private $answers = [
    'Gosto de ter metas e prazos bem definidos. Trabalho bem a sós, precisando de poucas orientações após compreender o objetivo. Naturalmente procuro _feedback_ constante, mesmo que não seja exigido, tanto do supervisor quanto dos demais membros da equipe.

Já trabalhei sem um objetivo ou prazo bem definido. Apesar que meu supervisor na época apreciou meu trabalho, não tive uma boa experiência. Senti-me perdido em trivialidades por não ter um objetivo bem traçado.',
    'O maior objetivo de vida que realizei foi aprender japonês. Comecei a estudar em meados de 2015 e não parei desde então. Sacrifiquei incontáveis estudando. Comecei a estudar por causa de uma paixão: assistir anime, mas com o passar dos anos deixei-la para trás, ficando somente com o idioma. Tive a oportunidade de ler ótimos livros graças a saber japonês ([estes aqui][1]).

Com certeza o resultado desta jornada foi diferente do inicialmente esperado. Não assisto mais anime, mas tive a oportunidade de receber uma bolsa para estudar no Japão que infelizmente não fui por conta da pandemia do coronavírus. Atualmente falta apenas um nível do _Japanese-Language Proficiency Test_ para conquistar. Ver meu progresso tem sido uma das experiências mais satisfatórias da minha vida.

[1]: https://pedrominicz.github.io/freedom',
    'O coronavírus força, pela primeira vez, que muitos seguimentos normalmente presenciais fiquem a distância. Destes, creio que dois teram maior impacto em longo prazo: educação e trabalho a distância.

Aprender é algo que pode ser feito de forma independente _online_. Já fazem vários anos que este é o caso. Agora, escolas são forçadas a explorar esta possibilidade. Ao longo do tempo os alunos se adaptarão (antes mesmos que muitas escolas) e ficará claro que educação a distância é uma opção viável a todos. Mas de longe, o aprendizado mais importante, que provavelmente só virá depois do amadurecimento de uma geração estudando _online_, será: aprender como avaliar de forma eficiente o aluno que estuda sozinho. Esta é uma questão ainda mal resolve, e consequentemente leva a baixa confiança ao ensido a distância. Agora seremos forçados a responde-lá, e só temos a ganhar com isso.

Trabalhar é outra coisa que, para diversas áreas, pode ser feita a distância. Creio que o meior impacto não virá de dentro do nosso país, mas sim de países de primeiro mundo. Empresas perceberão que muitos trabalhos de desenvolvimento, por exemplo, antes prezos ao vale do silício estaram abertos para todo o mundo. Será mais fácil estas empresas discobrirem talentos em países como o Brasil. Uma nova onde de oportunidades se abrirá aos melhores de todos os países onde certas indústrias não são tão potentes.',
    'Tenho experiência limitada com Microsoft Windows. Como usuário, pode-se dizer que sou avançado. Sei usar o sistema de forma efetiva e realizar uma série de configurações cotidianas.',
    'Uso Linux desde criança. Tive a oportunidade de instalar dezenas de sistemas, em máquinas físicas e virtuais. Tenho profunda familiaridade com a linha de comando. Uso diversas ferramentas, como `sed`, `grep`, `git`, `vi` e `find`, no meu dia-a-dia. Tenho experiência com distribuições baseadas em Debian (e.g. Ubuntu) e Arch Linux. Conheço também sobre: particionamento, configuração de diversas partes do sistema, configurar redes, criação de usuários e gerenciamento de permissões.',
    'Conheço o suficiente de redes para configurar estações de trabalho. Entendo sobre IP, máscara de redes, DHCP, gateways e DNS.',
    'Por nome, conheço diversos fornecedores de _cloud computing_, porém tenho limitada experiência somente com o Google Cloud.',
    '`++$i` incrementa a variável `$i` e retorna o valor incrementado, enquanto `$i++` retorna o valor da variável e posteriormente incrementa seu valor. Para que o valor não incrementado possa ser utilizado, `$i++` efetivamente cria uma variável temporária em diversas situações (apesar que um interpretador inteligente otimizaria isto). Ou seja, se o valor não incrementado da variável não for ser utilizado na espressão a melhor opção é usar `++$i`.

Por exemplo, considere os seguintes _loops_:

    for($i = 0; $i < 10; $i++);

    for($i = 0; $i < 10; ++$i);

No primeiro, a cada iteração o interpretador criará uma variável temporária e (mesmo que só por uma fração de segundo) terá os valores `$i` e `$i + 1` em memória simultaneamente. Já o segundo _loop_ não encontrará este problema.',
  ];

  private $parser;

  public function __construct() {
    $this->parser = new Parsedown();
  }

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
    $view = Twig::fromRequest($request);

    // `array_map` é uma excelente função. Nada a dizer, só gosto dela mesmo.
    $questions = array_map(array($this->parser, 'line'), $this->questions);
    $answers = array_map(array($this->parser, 'text'), $this->answers);

    return $view->render($response, 'Respostas.twig', [
      'title' => 'Respostas',
      'questions' => array_combine($questions, $answers),
    ]);
  }

}
