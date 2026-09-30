<?php
require dirname(__DIR__) . '/src/bootstrap.php';

// Logout só por POST com token, para que links de terceiros não deslogem o usuário.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valido()) {
    encerrar_sessao();
}

redirecionar('login.php');
