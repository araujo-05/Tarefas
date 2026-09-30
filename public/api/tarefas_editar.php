<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();
$tarefa = api_buscar_tarefa('editar', $usuario);

$descricao = input('descricao');
$situacao = input('situacao');

if ($erro = validar_descricao_tarefa($descricao) ?? validar_situacao($situacao)) {
    json_erro($erro);
}

$tarefa = tarefa_atualizar((int) $tarefa['id'], $descricao, $situacao);

json_response([
    'ok' => true,
    'msg' => 'Tarefa atualizada.',
    'id' => (int) $tarefa['id'],
    'html' => render_linha_tarefa($tarefa, $usuario),
]);
