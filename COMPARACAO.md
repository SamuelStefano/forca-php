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

## Minha conclusão

Fazendo os dois lado a lado, o que mais me chamou atenção foi como a mesma ideia simples muda de cara dependendo de onde a lógica roda.

No JavaScript a sensação é melhor na hora de jogar. Clico numa letra e responde na hora, sem tela piscando. E publicar é ridículo de fácil: são três arquivos, abro em qualquer lugar. Pra um jogo da forca, que ninguém liga se o jogador "trapacear", essa seria a escolha que eu faria sem pensar muito.

Mas foi justamente fazendo a versão PHP que caiu a ficha do porquê o server-side existe. Quando abri o console na versão JS, a palavra sorteada estava ali, de bandeja — dava pra ganhar sem jogar. Na versão PHP isso não acontece, porque a palavra nunca sai do servidor. Aí faz sentido aguentar o "peso" de recarregar a página a cada jogada: você troca um pouco de velocidade por confiança. Se isso aqui fosse um jogo valendo nota, dinheiro ou ranking, eu não pensaria duas vezes em deixar a regra no servidor.

No fim achei que não é bem "um é melhor que o outro". É mais sobre em quem dá pra confiar. Se dá pra confiar no navegador, o JS entrega uma experiência bem melhor. Se não dá, o servidor precisa ser o dono da verdade. Não é à toa que os sistemas grandes acabam usando os dois juntos — o servidor guarda o que importa e o JavaScript cuida de deixar a tela rápida e gostosa de usar.
