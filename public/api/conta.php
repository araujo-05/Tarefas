<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

// Atualiza nome e e-mail do próprio usuário.

$usuario = iniciar_api();
api_bloquear_personificacao();

$nome = input('nome');
$email = input('email');

if ($erro = validar_dados_usuario($nome, $email)) {
    json_erro($erro);
}
if (usuario_email_em_uso($email, $usuario['id'])) {
    json_erro('Já existe uma conta com este e-mail.', 409);
}

usuario_atualizar($usuario['id'], $nome, $email, $usuario['perfil']);

json_response(['ok' => true, 'msg' => 'Dados atualizados.', 'nome' => $nome]);
