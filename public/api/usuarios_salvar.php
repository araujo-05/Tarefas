<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

// Cria (sem "id") ou atualiza (com "id") um usuário pelo painel de administração.
// No cadastro a senha é obrigatória; na edição, em branco mantém a atual.

$admin = iniciar_api();
api_exigir_permissao('usuarios.gerenciar', $admin);

$id = input_id();
$existente = $id !== null ? api_buscar_usuario() : null;

$nome = input('nome');
$email = input('email');
$perfil = input('perfil');
$senha = input('senha', trim: false);

if ($erro = validar_dados_usuario($nome, $email) ?? validar_perfil($perfil)) {
    json_erro($erro);
}
if ($existente === null || $senha !== '') {
    if ($erro = validar_senha($senha)) {
        json_erro($erro);
    }
}
if (usuario_email_em_uso($email, $id)) {
    json_erro('Já existe uma conta com este e-mail.', 409);
}
if ($existente !== null && (int) $existente['id'] === $admin['id'] && $perfil !== $existente['perfil']) {
    json_erro('Você não pode alterar o seu próprio perfil.', 403);
}

if ($existente === null) {
    $id = usuario_criar($nome, $email, $senha, $perfil);
    $msg = 'Usuário criado.';
} else {
    usuario_atualizar($id, $nome, $email, $perfil);
    if ($senha !== '') {
        usuario_atualizar_senha($id, $senha);
    }
    $msg = 'Usuário atualizado.';
}

json_response([
    'ok' => true,
    'msg' => $msg,
    'id' => $id,
    'html' => render_linha_usuario(usuario_buscar_para_listagem($id), $admin),
], $existente === null ? 201 : 200);
