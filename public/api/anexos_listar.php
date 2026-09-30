<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();
$tarefa = api_buscar_tarefa('ver', $usuario);

json_response([
    'ok' => true,
    'html' => render_anexos($tarefa, $usuario),
    'podeEditar' => pode_alterar_tarefa('editar', $tarefa, $usuario),
]);
