<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

iniciar_api(exigeLogin: false);

$email = input('email');
$senha = input('senha', trim: false);

if ($email === '' || $senha === '') {
    json_erro('Informe e-mail e senha.');
}

$usuario = usuario_buscar_por_email($email);

// Mesma mensagem nos dois casos para não revelar quais e-mails estão cadastrados.
if ($usuario === null || !password_verify($senha, $usuario['senha'])) {
    json_erro('E-mail ou senha inválidos.', 401);
}

if (password_needs_rehash($usuario['senha'], PASSWORD_DEFAULT)) {
    usuario_atualizar_senha((int) $usuario['id'], $senha);
}

autenticar($usuario);

json_response(['ok' => true, 'msg' => 'Login realizado.']);
