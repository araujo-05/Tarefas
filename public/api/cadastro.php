<?php
require dirname(__DIR__, 2) . '/src/bootstrap.php';

iniciar_api(exigeLogin: false);

$nome = input('nome');
$email = input('email');
$senha = input('senha', trim: false);

if ($erro = validar_cadastro($nome, $email, $senha)) {
    json_erro($erro);
}

if (usuario_buscar_por_email($email) !== null) {
    json_erro('Já existe uma conta com este e-mail.', 409);
}

usuario_criar($nome, $email, $senha);

json_response(['ok' => true, 'msg' => 'Conta criada com sucesso.'], 201);
