<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();
$tarefa = api_buscar_tarefa('excluir', $usuario);

tarefa_excluir((int) $tarefa['id']);

json_response(['ok' => true, 'msg' => 'Tarefa excluída.']);
