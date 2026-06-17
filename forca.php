<?php

function desenhaForca(int $erros): string
{
    $cor = '#e94560';
    $traco = '#a7a7c5';
    $partes = '';

    $partes .= '<line x1="20" y1="230" x2="160" y2="230" stroke="' . $traco . '" stroke-width="6"/>';
    $partes .= '<line x1="60" y1="230" x2="60" y2="20" stroke="' . $traco . '" stroke-width="6"/>';
    $partes .= '<line x1="60" y1="20" x2="150" y2="20" stroke="' . $traco . '" stroke-width="6"/>';
    $partes .= '<line x1="150" y1="20" x2="150" y2="50" stroke="' . $traco . '" stroke-width="6"/>';

    if ($erros >= 1) {
        $partes .= '<circle cx="150" cy="70" r="20" stroke="' . $cor . '" stroke-width="5" fill="none"/>';
    }
    if ($erros >= 2) {
        $partes .= '<line x1="150" y1="90" x2="150" y2="150" stroke="' . $cor . '" stroke-width="5"/>';
    }
    if ($erros >= 3) {
        $partes .= '<line x1="150" y1="105" x2="120" y2="130" stroke="' . $cor . '" stroke-width="5"/>';
    }
    if ($erros >= 4) {
        $partes .= '<line x1="150" y1="105" x2="180" y2="130" stroke="' . $cor . '" stroke-width="5"/>';
    }
    if ($erros >= 5) {
        $partes .= '<line x1="150" y1="150" x2="125" y2="195" stroke="' . $cor . '" stroke-width="5"/>';
    }
    if ($erros >= 6) {
        $partes .= '<line x1="150" y1="150" x2="175" y2="195" stroke="' . $cor . '" stroke-width="5"/>';
    }

    return '<svg viewBox="0 0 200 250" class="forca" aria-hidden="true">' . $partes . '</svg>';
}
