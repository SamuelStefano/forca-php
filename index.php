<?php
session_start();
require __DIR__ . '/forca.php';

$PALAVRAS = ['LEAO', 'GIRAFA', 'ELEFANTE', 'CAVALO', 'TUBARAO', 'PERIQUITO'];

function novoJogo(array $palavras): void
{
    $_SESSION['palavra'] = $palavras[array_rand($palavras)];
    $_SESSION['acertos'] = [];
    $_SESSION['erros'] = [];
}

if (!isset($_SESSION['palavra'])) {
    novoJogo($PALAVRAS);
}

$palavra = $_SESSION['palavra'];
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
    <style>
        body { font-family: sans-serif; background: #1a1a2e; color: #eee; text-align: center; padding: 2rem; }
        .palavra { font-size: 2rem; letter-spacing: .5rem; margin: 2rem 0; }
        button { font-size: 1rem; padding: .5rem 1rem; margin: .2rem; cursor: pointer; }
        .status { font-size: 1.2rem; margin: 1rem 0; }
        .venceu { color: #4ade80; }
        .perdeu { color: #f87171; }
        .forca { width: 200px; height: 250px; }
    </style>
</head>
<body>
    <h1>Jogo da Forca</h1>

    <p>Erros: <?= count($erros) ?> / <?= $maxErros ?></p>

    <?= desenhaForca(count($erros)) ?>

    <div class="palavra"><?= trim($mascara) ?></div>

    <?php if ($venceu): ?>
        <p class="status venceu">Voce venceu!</p>
    <?php elseif ($perdeu): ?>
        <p class="status perdeu">Voce perdeu! A palavra era: <?= $palavra ?></p>
    <?php else: ?>
        <div>
            <?php foreach (range('A', 'Z') as $tecla): ?>
                <?php $usada = in_array($tecla, $acertos) || in_array($tecla, $erros); ?>
                <form method="post" style="display:inline">
                    <button type="submit" name="letra" value="<?= $tecla ?>" <?= $usada ? 'disabled' : '' ?>><?= $tecla ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
