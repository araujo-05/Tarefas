<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$usuario = exigir_login();

view('conta/index', [
    'titulo' => 'Minha conta',
    'usuario' => $usuario,
    'personificando' => esta_personificando(),
    'scripts' => ['conta.js'],
]);
