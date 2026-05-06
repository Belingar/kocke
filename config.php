<?php
session_start();

define('DICE_BASE_URL', 'http://193.2.139.22/dice/');
define('NUM_PLAYERS', 3);
define('NUM_DICE', 3);
define('REDIRECT_SECONDS', 10);

/**
 * Vrne URL slike kocke za dano vrednost.
 */
function diceImg(int $value): string {
    return DICE_BASE_URL . $value . '.png';
}

/**
 * Preusmeri na podano stran.
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Počisti in vrne vrednost iz POST.
 */
function post(string $key): string {
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}
