<?php

// Cada função retorna a mensagem de erro, ou null quando o valor é válido.

const SENHA_MINIMO = 6;
const SENHA_MAXIMO = 72; // limite do bcrypt
const TEXTO_MAXIMO = 100; // tamanho das colunas VARCHAR(100)

function validar_senha(string $senha): ?string
{
    if (mb_strlen($senha) < SENHA_MINIMO) {
        return 'A senha deve ter pelo menos ' . SENHA_MINIMO . ' caracteres.';
    }
    if (strlen($senha) > SENHA_MAXIMO) {
        return 'A senha deve ter no máximo ' . SENHA_MAXIMO . ' caracteres.';
    }

    return null;
}

function validar_dados_usuario(string $nome, string $email): ?string
{
    if ($nome === '' || $email === '') {
        return 'Preencha nome e e-mail.';
    }
    if (mb_strlen($nome) > TEXTO_MAXIMO) {
        return 'O nome deve ter no máximo ' . TEXTO_MAXIMO . ' caracteres.';
    }
    if (mb_strlen($email) > TEXTO_MAXIMO || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Informe um e-mail válido.';
    }

    return null;
}

function validar_cadastro(string $nome, string $email, string $senha): ?string
{
    if ($nome === '' || $email === '' || $senha === '') {
        return 'Preencha todos os campos.';
    }

    return validar_dados_usuario($nome, $email) ?? validar_senha($senha);
}

function validar_perfil(string $perfil): ?string
{
    return array_key_exists($perfil, PERFIS) ? null : 'Perfil inválido.';
}

function validar_descricao_tarefa(string $descricao): ?string
{
    if ($descricao === '') {
        return 'Informe a descrição da tarefa.';
    }
    if (mb_strlen($descricao) > TEXTO_MAXIMO) {
        return 'A descrição deve ter no máximo ' . TEXTO_MAXIMO . ' caracteres.';
    }

    return null;
}

function validar_situacao(string $situacao): ?string
{
    return array_key_exists($situacao, SITUACOES) ? null : 'Situação inválida.';
}
