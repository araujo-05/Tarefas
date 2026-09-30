<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();

$id = input_id();
$anexo = $id ? anexo_buscar($id) : null;
$tarefa = $anexo ? tarefa_buscar((int) $anexo['tarefa']) : null;

if ($tarefa === null || !pode_ver_tarefa($tarefa, $usuario)) {
    json_erro('Anexo não encontrado.', 404);
}
if (!pode_alterar_tarefa('editar', $tarefa, $usuario)) {
    json_erro('Você não tem permissão para alterar esta tarefa.', 403);
}

anexo_excluir($anexo);

json_response([
    'ok' => true,
    'msg' => 'Anexo excluído.',
    'html' => render_anexos($tarefa, $usuario),
    'total' => count(anexos_listar_por_tarefa((int) $tarefa['id'])),
]);
