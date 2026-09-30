<?php

// Acesso à tabela tasks.

const TAREFA_SELECT = 'SELECT t.id, t.descricao, t.situacao, t.usuario, t.data_inclusao, u.nome AS responsavel,
        (SELECT COUNT(*) FROM task_anexos a WHERE a.tarefa = t.id) AS total_anexos
    FROM tasks t
    INNER JOIN users u ON u.id = t.usuario';

function tarefas_listar_por_usuario(int $usuarioId): array
{
    $stmt = db()->prepare(TAREFA_SELECT . ' WHERE t.usuario = :usuario ORDER BY t.id');
    $stmt->execute(['usuario' => $usuarioId]);

    return $stmt->fetchAll();
}

function tarefas_listar_todas(): array
{
    return db()->query(TAREFA_SELECT . ' ORDER BY t.id')->fetchAll();
}

function tarefa_buscar(int $id): ?array
{
    $stmt = db()->prepare(TAREFA_SELECT . ' WHERE t.id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

function tarefa_criar(int $usuarioId, string $descricao): array
{
    $stmt = db()->prepare('INSERT INTO tasks (descricao, usuario, data_inclusao) VALUES (:descricao, :usuario, CURRENT_TIMESTAMP())');
    $stmt->execute([
        'descricao' => $descricao,
        'usuario' => $usuarioId,
    ]);

    return tarefa_buscar((int) db()->lastInsertId());
}

function tarefa_atualizar(int $id, string $descricao, string $situacao): array
{
    $stmt = db()->prepare('UPDATE tasks SET descricao = :descricao, situacao = :situacao WHERE id = :id');
    $stmt->execute([
        'descricao' => $descricao,
        'situacao' => $situacao,
        'id' => $id,
    ]);

    return tarefa_buscar($id);
}

/** Exclui a tarefa; os anexos saem do banco por cascade e os arquivos são apagados aqui. */
function tarefa_excluir(int $id): void
{
    $anexos = anexos_listar_por_tarefa($id);

    $stmt = db()->prepare('DELETE FROM tasks WHERE id = :id');
    $stmt->execute(['id' => $id]);

    anexos_apagar_arquivos($anexos);
}
