<?php

// Acesso à tabela task_anexos.

const ANEXO_SELECT = 'SELECT a.id, a.tarefa, a.usuario, a.nome_original, a.arquivo, a.mime, a.tamanho, a.data_inclusao,
        u.nome AS enviado_por
    FROM task_anexos a
    LEFT JOIN users u ON u.id = a.usuario';

function anexos_listar_por_tarefa(int $tarefaId): array
{
    $stmt = db()->prepare(ANEXO_SELECT . ' WHERE a.tarefa = :tarefa ORDER BY a.id');
    $stmt->execute(['tarefa' => $tarefaId]);

    return $stmt->fetchAll();
}

/** Anexos de todas as tarefas de um usuário (para apagar os arquivos ao excluí-lo). */
function anexos_listar_por_dono_da_tarefa(int $usuarioId): array
{
    $stmt = db()->prepare(ANEXO_SELECT . ' INNER JOIN tasks t ON t.id = a.tarefa WHERE t.usuario = :usuario');
    $stmt->execute(['usuario' => $usuarioId]);

    return $stmt->fetchAll();
}

function anexo_buscar(int $id): ?array
{
    $stmt = db()->prepare(ANEXO_SELECT . ' WHERE a.id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

function anexo_criar(int $tarefaId, int $usuarioId, string $nomeOriginal, string $arquivo, string $mime, int $tamanho): array
{
    $stmt = db()->prepare('INSERT INTO task_anexos (tarefa, usuario, nome_original, arquivo, mime, tamanho)
        VALUES (:tarefa, :usuario, :nome_original, :arquivo, :mime, :tamanho)');
    $stmt->execute([
        'tarefa' => $tarefaId,
        'usuario' => $usuarioId,
        'nome_original' => $nomeOriginal,
        'arquivo' => $arquivo,
        'mime' => $mime,
        'tamanho' => $tamanho,
    ]);

    return anexo_buscar((int) db()->lastInsertId());
}

function anexo_excluir(array $anexo): void
{
    $stmt = db()->prepare('DELETE FROM task_anexos WHERE id = :id');
    $stmt->execute(['id' => $anexo['id']]);

    anexos_apagar_arquivos([$anexo]);
}
