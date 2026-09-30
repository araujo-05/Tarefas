<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();

$descricao = input('descricao');

if ($erro = validar_descricao_tarefa($descricao)) {
    json_erro($erro);
}

// O dono da tarefa vem da sessão, nunca do formulário.
$tarefa = tarefa_criar($usuario['id'], $descricao);

json_response([
    'ok' => true,
    'msg' => 'Tarefa adicionada.',
    'html' => render_linha_tarefa($tarefa, $usuario),
], 201);
