<?php
session_start();
require __DIR__ . '/forca.php';

$CATEGORIAS = [
    'Animais' => ['LEAO', 'GIRAFA', 'ELEFANTE', 'CAVALO', 'TUBARAO', 'PERIQUITO'],
    'Frutas' => ['ABACAXI', 'MORANGO', 'MELANCIA', 'LARANJA', 'GOIABA', 'MARACUJA'],
    'Países' => ['BRASIL', 'ARGENTINA', 'PORTUGAL', 'JAPAO', 'CANADA', 'EGITO'],
    'Tecnologia' => ['TECLADO', 'MONITOR', 'INTERNET', 'ALGORITMO', 'SERVIDOR', 'COMPILADOR'],
];

function novoJogo(array $categorias): void
{
    $categoria = array_rand($categorias);
    $palavras = $categorias[$categoria];
    $_SESSION['categoria'] = $categoria;
    $_SESSION['palavra'] = $palavras[array_rand($palavras)];
    $_SESSION['acertos'] = [];
    $_SESSION['erros'] = [];
    $_SESSION['computado'] = false;
}

if (!isset($_SESSION['placar'])) {
    $_SESSION['placar'] = ['vitorias' => 0, 'derrotas' => 0, 'sequencia' => 0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova'])) {
    novoJogo($CATEGORIAS);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['palavra'])) {
    novoJogo($CATEGORIAS);
}

$palavra = $_SESSION['palavra'];
$categoria = $_SESSION['categoria'];
$acertos = $_SESSION['acertos'];
$erros = $_SESSION['erros'];
$maxErros = 6;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['letra'])) {
    $letra = strtoupper($_POST['letra']);
    $jaUsada = in_array($letra, $acertos) || in_array($letra, $erros);
    $venceu = count(array_diff(str_split($palavra), $acertos)) === 0;
    $perdeu = count($erros) >= $maxErros;

    if (preg_match('/^[A-Z]$/', $letra) && !$jaUsada && !$venceu && !$perdeu) {
        if (strpos($palavra, $letra) !== false) {
            $_SESSION['acertos'][] = $letra;
        } else {
            $_SESSION['erros'][] = $letra;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$venceu = count(array_diff(str_split($palavra), $acertos)) === 0;
$perdeu = count($erros) >= $maxErros;

if (($venceu || $perdeu) && empty($_SESSION['computado'])) {
    if ($venceu) {
        $_SESSION['placar']['vitorias']++;
        $_SESSION['placar']['sequencia']++;
    } else {
        $_SESSION['placar']['derrotas']++;
        $_SESSION['placar']['sequencia'] = 0;
    }
    $_SESSION['computado'] = true;
}

$placar = $_SESSION['placar'];

$mascara = '';
foreach (str_split($palavra) as $c) {
    $mascara .= in_array($c, $acertos) ? $c : '_';
    $mascara .= ' ';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Forca</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main class="cartao">
        <h1>Jogo da Forca</h1>

        <div class="placar" aria-label="Placar">
            <div><span><?= $placar['vitorias'] ?></span>Vitorias</div>
            <div><span><?= $placar['derrotas'] ?></span>Derrotas</div>
            <div><span><?= $placar['sequencia'] ?></span>Sequencia</div>
        </div>

        <p class="dica">Categoria: <strong><?= $categoria ?></strong></p>
        <p class="vidas">Vidas restantes: <?= $maxErros - count($erros) ?></p>

        <?= desenhaForca(count($erros)) ?>

        <div class="palavra" aria-label="Palavra a adivinhar"><?= trim($mascara) ?></div>

        <div class="status-area" role="status" aria-live="polite">
            <?php if ($venceu): ?>
                <p class="status venceu">Voce venceu!</p>
            <?php elseif ($perdeu): ?>
                <p class="status perdeu">Voce perdeu! A palavra era: <?= $palavra ?></p>
            <?php endif; ?>
        </div>

        <form method="post" class="teclado" aria-label="Teclado virtual">
            <?php foreach (range('A', 'Z') as $tecla): ?>
                <?php
                $acertou = in_array($tecla, $acertos);
                $errou = in_array($tecla, $erros);
                $classe = $acertou ? ' acerto' : ($errou ? ' erro' : '');
                $bloqueada = $acertou || $errou || $venceu || $perdeu;
                $rotulo = 'Letra ' . $tecla . ($acertou ? ' (acerto)' : ($errou ? ' (erro)' : ''));
                ?>
                <button type="submit" name="letra" value="<?= $tecla ?>" class="tecla<?= $classe ?>" aria-label="<?= $rotulo ?>" <?= $bloqueada ? 'disabled' : '' ?>><?= $tecla ?></button>
            <?php endforeach; ?>
        </form>

        <form method="post">
            <button type="submit" name="nova" value="1" class="nova" aria-label="Sortear nova palavra">Nova palavra</button>
        </form>
    </main>
</body>
</html>
