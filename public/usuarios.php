<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$usuario = exigir_permissao('usuarios.gerenciar');

view('usuarios/index', [
    'titulo' => 'Usuários',
    'usuarios' => usuarios_listar(),
    'usuarioLogado' => $usuario,
    'scripts' => ['usuarios.js'],
]);
