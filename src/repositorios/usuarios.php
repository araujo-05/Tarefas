<?php

// Acesso à tabela users.

function usuario_buscar_por_id(int $id): ?array
{
    $stmt = db()->prepare('SELECT id, nome, email, senha, perfil FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

function usuario_buscar_por_email(string $email): ?array
{
    $stmt = db()->prepare('SELECT id, nome, email, senha, perfil FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);

    return $stmt->fetch() ?: null;
}

/** Lista para o painel de administração, com o total de tarefas de cada usuário. */
function usuarios_listar(): array
{
    return db()->query('SELECT u.id, u.nome, u.email, u.perfil, COUNT(t.id) AS total_tarefas
        FROM users u
        LEFT JOIN tasks t ON t.usuario = u.id
        GROUP BY u.id, u.nome, u.email, u.perfil
        ORDER BY u.id')->fetchAll();
}

/** Mesmo formato de usuarios_listar(), para um único usuário. */
function usuario_buscar_para_listagem(int $id): ?array
{
    $stmt = db()->prepare('SELECT u.id, u.nome, u.email, u.perfil, COUNT(t.id) AS total_tarefas
        FROM users u
        LEFT JOIN tasks t ON t.usuario = u.id
        WHERE u.id = :id
        GROUP BY u.id, u.nome, u.email, u.perfil');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

/** true se o e-mail já pertence a outro usuário (diferente de $ignorarId). */
function usuario_email_em_uso(string $email, ?int $ignorarId = null): bool
{
    $usuario = usuario_buscar_por_email($email);

    return $usuario !== null && (int) $usuario['id'] !== $ignorarId;
}

function usuario_criar(string $nome, string $email, string $senha, string $perfil = 'usuario'): int
{
    $stmt = db()->prepare('INSERT INTO users (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)');
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'senha' => password_hash($senha, PASSWORD_DEFAULT),
        'perfil' => $perfil,
    ]);

    return (int) db()->lastInsertId();
}

function usuario_atualizar(int $id, string $nome, string $email, string $perfil): void
{
    $stmt = db()->prepare('UPDATE users SET nome = :nome, email = :email, perfil = :perfil WHERE id = :id');
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'perfil' => $perfil,
        'id' => $id,
    ]);
}

function usuario_atualizar_senha(int $id, string $novaSenha): void
{
    $stmt = db()->prepare('UPDATE users SET senha = :senha WHERE id = :id');
    $stmt->execute([
        'senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
        'id' => $id,
    ]);
}

/** Exclui o usuário junto com as tarefas dele e os arquivos anexados a elas. */
function usuario_excluir(int $id): void
{
    $pdo = db();
    $anexos = anexos_listar_por_dono_da_tarefa($id);

    $pdo->beginTransaction();
    try {
        $pdo->prepare('DELETE FROM tasks WHERE usuario = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $id]);
        $pdo->commit();
    } catch (Throwable $erro) {
        $pdo->rollBack();
        throw $erro;
    }

    anexos_apagar_arquivos($anexos);
}
