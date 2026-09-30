<?php
require dirname(__DIR__) . '/src/bootstrap.php';

// Volta para a conta do administrador. Só por POST com token, como o logout.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valido()) {
    encerrar_personificacao();
    redirecionar('usuarios.php');
}

redirecionar('index.php');
