<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$usuario = exigir_login();
$verTodas = pode('tarefas.ver_todas', $usuario);

view('tarefas/index', [
    'titulo' => $verTodas ? 'Todas as tarefas' : 'Minhas tarefas',
    'tarefas' => $verTodas ? tarefas_listar_todas() : tarefas_listar_por_usuario($usuario['id']),
    'verTodas' => $verTodas,
    'scripts' => ['tarefas.js'],
]);
