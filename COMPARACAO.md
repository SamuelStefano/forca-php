# Comparação: Forca em JavaScript vs Forca em PHP

O mesmo jogo da forca foi feito de duas formas para comparar as abordagens:

- **JavaScript (client-side):** roda inteiro no navegador — [forca-js](https://github.com/SamuelStefano/forca-js)
- **PHP (server-side):** a lógica roda no servidor — [forca-php](https://github.com/SamuelStefano/forca-php)

Os dois têm as mesmas funcionalidades (categorias, boneco em SVG, teclado virtual, placar) de propósito, para a diferença ficar só na arquitetura.

## Onde fica o estado do jogo

Essa é a maior diferença.

- **JS:** o estado (palavra sorteada, letras certas, erros) fica em variáveis na memória do navegador. O placar fica no `localStorage`. Nada sai do computador do jogador.
- **PHP:** o estado fica no servidor, dentro da `$_SESSION`. O navegador só guarda um cookie com o ID da sessão.

## Como uma jogada acontece

- **JS:** o jogador clica numa letra, uma função JavaScript atualiza as variáveis e redesenha a tela na hora, sem recarregar a página. É instantâneo.
- **PHP:** o jogador clica numa letra, o navegador envia um POST para o servidor, o PHP processa, atualiza a sessão e devolve a página inteira de novo. É um "round-trip" (ida e volta) HTTP a cada letra.

## Tabela comparativa

| Aspecto | JavaScript (client-side) | PHP (server-side) |
|--------|--------------------------|-------------------|
| Onde a lógica roda | No navegador | No servidor |
| Onde fica o estado | Memória do navegador + localStorage | `$_SESSION` no servidor |
| A cada jogada | Atualiza o DOM, sem recarregar | POST → recarrega a página |
| Velocidade de resposta | Instantânea | Depende da rede |
| Funciona offline | Sim | Não |
| Precisa de servidor | Não (arquivos estáticos) | Sim (PHP rodando) |
| Dá pra "trapacear"? | Sim, o estado está visível no navegador | Não, o estado fica escondido no servidor |
| Custo de hospedagem | Muito baixo | Maior (precisa de PHP no ar) |

## Vantagens e desvantagens

**JavaScript**
- A favor: resposta instantânea, funciona offline, fácil e barato de hospedar (só arquivos estáticos).
- Contra: tudo fica exposto no navegador. Como a palavra está na memória, dá pra abrir o console e "colar".

**PHP**
- A favor: o estado fica seguro no servidor, longe do jogador. É o modelo certo quando não dá pra confiar no cliente (ex: um jogo valendo nota ou dinheiro).
- Contra: cada jogada recarrega a página e depende da rede, então parece mais lento. Precisa de um servidor PHP no ar.

## Conclusão

Para um joguinho simples, o JavaScript ganha em experiência: é mais rápido e mais fácil de publicar. O PHP brilha quando a regra do jogo precisa ser protegida — aí faz sentido pagar o custo do round-trip para manter o estado no servidor. Na prática, sistemas grandes misturam os dois: o servidor guarda o que é importante e o JavaScript cuida da parte visual para a tela responder rápido.
