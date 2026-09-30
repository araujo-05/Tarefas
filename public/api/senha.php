<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

$usuario = iniciar_api();
api_bloquear_personificacao();

$senhaAtual = input('senha_atual', trim: false);
$novaSenha = input('nova_senha', trim: false);
$confirmacao = input('confirmar_senha', trim: false);

if ($senhaAtual === '' || $novaSenha === '' || $confirmacao === '') {
    json_erro('Preencha todos os campos.');
}
if ($erro = validar_senha($novaSenha)) {
    json_erro($erro);
}
if ($novaSenha !== $confirmacao) {
    json_erro('A confirmação não confere com a nova senha.');
}

$usuarioBanco = usuario_buscar_por_id($usuario['id']);

if ($usuarioBanco === null || !password_verify($senhaAtual, $usuarioBanco['senha'])) {
    json_erro('Senha atual incorreta.');
}
if (password_verify($novaSenha, $usuarioBanco['senha'])) {
    json_erro('A nova senha deve ser diferente da atual.');
}

usuario_atualizar_senha($usuario['id'], $novaSenha);
session_regenerate_id(true);

json_response(['ok' => true, 'msg' => 'Senha alterada com sucesso.']);
