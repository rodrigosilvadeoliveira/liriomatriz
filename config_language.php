<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'pt';
}

$lang = require __DIR__ . "/languages/" . $_SESSION['lang'] . ".php";

function __($key) {
    global $lang;
    return $lang[$key] ?? $key;
}