<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$admin = iniciar_api();
api_exigir_permissao('usuarios.gerenciar', $admin);

$alvo = api_buscar_usuario();

if ((int) $alvo['id'] === $admin['id']) {
    json_erro('Você não pode excluir a sua própria conta.', 403);
}

usuario_excluir((int) $alvo['id']);

json_response(['ok' => true, 'msg' => 'Usuário excluído, junto com as tarefas e anexos dele.']);
