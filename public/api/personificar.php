<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$admin = iniciar_api();
api_exigir_permissao('usuarios.personificar', $admin);

$alvo = api_buscar_usuario();

if ($motivo = motivo_nao_personificar($admin, $alvo)) {
    json_erro($motivo, 403);
}

iniciar_personificacao($admin, $alvo);

json_response(['ok' => true, 'msg' => "Você agora está navegando como {$alvo['nome']}."]);
