<?php

// Regras do painel de usuários.

/** HTML de uma linha da tabela de usuários; usado na página e nas respostas da API. */
function render_linha_usuario(array $usuario, array $usuarioLogado): string
{
    return render('usuarios/_linha', [
        'item' => $usuario,
        'ehVoce' => (int) $usuario['id'] === $usuarioLogado['id'],
        'podePersonificar' => pode('usuarios.personificar', $usuarioLogado),
    ]);
}

/** Motivo pelo qual o administrador não pode personificar $alvo, ou null se pode. */
function motivo_nao_personificar(array $admin, array $alvo): ?string
{
    if ((int) $alvo['id'] === $admin['id']) {
        return 'Você já está na sua própria conta.';
    }
    if ($alvo['perfil'] === 'admin') {
        return 'Não é possível personificar outro administrador.';
    }

    return null;
}
