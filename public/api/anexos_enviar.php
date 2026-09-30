<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();
$tarefa = api_buscar_tarefa('editar', $usuario);

$arquivo = $_FILES['arquivo'] ?? null;

if ($erro = validar_upload($arquivo)) {
    json_erro($erro);
}

anexo_salvar_upload($arquivo, (int) $tarefa['id'], $usuario['id']);

json_response([
    'ok' => true,
    'msg' => 'Anexo enviado.',
    'html' => render_anexos($tarefa, $usuario),
    'total' => count(anexos_listar_por_tarefa((int) $tarefa['id'])),
], 201);
