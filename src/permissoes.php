<?php

// Permissões por perfil. Para liberar algo a um perfil, basta incluir a permissão na lista dele.
// Sem permissão extra, cada usuário só vê e altera as próprias tarefas.

const PERFIS = [
    'usuario' => 'Usuário',
    'admin' => 'Administrador',
];

const PERMISSOES = [
    'admin' => [
        'tarefas.ver_todas',
        'tarefas.editar_todas',
        'tarefas.excluir_todas',
        'usuarios.gerenciar',
        'usuarios.personificar',
    ],
    'usuario' => [],
];

function pode(string $permissao, ?array $usuario = null): bool
{
    $perfil = ($usuario ?? usuario_logado())['perfil'] ?? 'usuario';

    return in_array($permissao, PERMISSOES[$perfil] ?? [], true);
}

function eh_admin(?array $usuario = null): bool
{
    return (($usuario ?? usuario_logado())['perfil'] ?? null) === 'admin';
}

function eh_dono_tarefa(array $tarefa, ?array $usuario): bool
{
    return $usuario !== null && (int) $tarefa['usuario'] === $usuario['id'];
}

function pode_ver_tarefa(array $tarefa, ?array $usuario = null): bool
{
    $usuario ??= usuario_logado();

    return eh_dono_tarefa($tarefa, $usuario) || pode('tarefas.ver_todas', $usuario);
}

/** $acao: 'editar' ou 'excluir'. O dono sempre pode; os demais precisam da permissão "_todas". */
function pode_alterar_tarefa(string $acao, array $tarefa, ?array $usuario = null): bool
{
    $usuario ??= usuario_logado();

    return eh_dono_tarefa($tarefa, $usuario) || pode("tarefas.{$acao}_todas", $usuario);
}
