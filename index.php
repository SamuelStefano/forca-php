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
    <h1>Jogo da Forca</h1>

    <p class="dica">Categoria: <strong><?= $categoria ?></strong></p>
    <p class="vidas">Vidas restantes: <?= $maxErros - count($erros) ?></p>

    <?= desenhaForca(count($erros)) ?>

    <div class="palavra"><?= trim($mascara) ?></div>

    <?php if ($venceu): ?>
        <p class="status venceu">Voce venceu!</p>
    <?php elseif ($perdeu): ?>
        <p class="status perdeu">Voce perdeu! A palavra era: <?= $palavra ?></p>
    <?php endif; ?>

    <form method="post" class="teclado">
        <?php foreach (range('A', 'Z') as $tecla): ?>
            <?php
            $acertou = in_array($tecla, $acertos);
            $errou = in_array($tecla, $erros);
            $classe = $acertou ? ' acerto' : ($errou ? ' erro' : '');
            $bloqueada = $acertou || $errou || $venceu || $perdeu;
            ?>
            <button type="submit" name="letra" value="<?= $tecla ?>" class="tecla<?= $classe ?>" <?= $bloqueada ? 'disabled' : '' ?>><?= $tecla ?></button>
        <?php endforeach; ?>
    </form>

    <form method="post">
        <button type="submit" name="nova" value="1" class="nova">Nova palavra</button>
    </form>
</body>
</html>
