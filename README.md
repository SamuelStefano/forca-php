# Jogo da Forca (PHP)

Um jogo da Forca clássico feito em **PHP puro**, sem banco de dados e sem JavaScript.
O estado da partida vive no **servidor** (`$_SESSION`) e cada jogada é um POST que o
servidor processa e re-renderiza a página.

Este projeto é a versão PHP de um experimento comparativo: existe uma versão irmã em
JavaScript com o mesmo design, pra comparar de forma justa as duas abordagens.

## Como rodar

Precisa do PHP 8.x instalado. Na pasta do projeto:

```bash
php -S localhost:8082 -t .
```

Depois abra <http://localhost:8082> no navegador.

## Features

- Palavra mascarada que vai sendo revelada a cada acerto.
- Teclado virtual A-Z clicável; letras usadas ficam desabilitadas e coloridas
  (verde = acerto, vermelho = erro).
- Boneco da forca em SVG que progride a cada erro (6 erros = enforcado).
- Categorias como dica (Animais, Frutas, Países, Tecnologia).
- Botão "Nova palavra" e contador de vidas restantes.
- Placar de vitórias, derrotas e sequência, guardado na sessão do servidor.
- Tema escuro, responsivo (mobile) e com acessibilidade (aria-labels).

## Entregas de valor (versões)

| Versão  | Entrega                                                              |
| ------- | ------------------------------------------------------------------- |
| v0.1.0  | Jogo mínimo jogável: sessão, palavra mascarada, teclado, fim de jogo |
| v0.2.0  | Boneco da forca em SVG progressivo                                   |
| v0.3.0  | Teclado virtual estilizado, letras usadas coloridas/desabilitadas   |
| v0.4.0  | Categorias/dica, botão "Nova palavra", vidas restantes              |
| v0.5.0  | Placar (vitórias/derrotas/sequência) no `$_SESSION`                 |
| v1.0.0  | Polish de produção: responsivo, acessibilidade, animações, docs     |

### Por que versionamento semântico de produção

O versionamento segue o padrão **SemVer** (`MAJOR.MINOR.PATCH`):

- **MAJOR** (`1.x.x`): muda algo que quebra compatibilidade.
- **MINOR** (`x.1.x`): adiciona uma funcionalidade nova.
- **PATCH** (`x.x.1`): corrige um bug.

A parte "de produção" é o ponto principal: cada versão é uma **entrega de valor**,
ou seja, um estado do jogo que já poderia ir ao ar e ser usado pelo jogador. Não são
commits de rascunho ("wip", "ajustei o css"), e sim incrementos completos — a pergunta
em cada release é "isso entrega valor pro usuário?". Por isso a v0.1.0 já é um jogo
jogável, e cada versão seguinte soma uma funcionalidade utilizável, até a v1.0.0
pronta para produção.

## Arquitetura: por que server-side

Diferente da versão JavaScript, aqui **nada de lógica roda no navegador**. Todo o
estado do jogo — a palavra sorteada, as letras já tentadas e o placar — fica
guardado no servidor, dentro de `$_SESSION`.

Cada vez que clico numa letra, o navegador manda um **POST**, o PHP atualiza a sessão,
faz um redirect e devolve o HTML novo já com o jogo no estado atualizado. Ou seja:
cada jogada é um **round-trip HTTP** completo (ida e volta ao servidor).

Esse é justamente o contraste com a versão JS, onde o estado vive no `localStorage`
do navegador e tudo acontece na própria página. Em PHP, o servidor é a fonte da
verdade; se eu apagar o cookie de sessão, o jogo recomeça. É mais "pesado" em
requisições, mas mostra bem como aplicações web funcionavam (e muitas ainda funcionam)
antes do JavaScript tomar conta do front.
