<?php

// Funções usadas pelos endpoints em public/api/, que sempre respondem JSON.

function eh_requisicao_api(): bool
{
    return str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/api/');
}

function json_response(array $dados, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_erro(string $mensagem, int $status = 422): never
{
    json_response(['ok' => false, 'msg' => $mensagem], $status);
}

/**
 * Validações comuns a todo endpoint: método POST, token CSRF e, opcionalmente, login.
 * Retorna o usuário logado quando $exigeLogin for true.
 */
function iniciar_api(bool $exigeLogin = true): ?array
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        json_erro('Método não permitido.', 405);
    }

    // Quando o corpo passa do post_max_size, o PHP descarta $_POST e $_FILES.
    if (empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        json_erro('O conteúdo enviado é grande demais.', 413);
    }

    if (!csrf_valido()) {
        json_erro('Sessão expirada. Recarregue a página e tente novamente.', 403);
    }

    if (!$exigeLogin) {
        return null;
    }

    return usuario_logado() ?? json_erro('Sessão expirada. Faça login novamente.', 401);
}

function api_exigir_permissao(string $permissao, array $usuario): void
{
    if (!pode($permissao, $usuario)) {
        json_erro('Você não tem permissão para esta ação.', 403);
    }
}

/** Bloqueia ações sobre a própria conta (senha, e-mail...) enquanto um admin personifica alguém. */
function api_bloquear_personificacao(): void
{
    if (esta_personificando()) {
        json_erro('Esta ação não é permitida enquanto você personifica outro usuário.', 403);
    }
}

/** Lê um id inteiro do POST. */
function input_id(string $campo = 'id'): ?int
{
    $id = filter_var(input($campo), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    return $id === false ? null : $id;
}

/**
 * Carrega a tarefa do campo $campo e garante que o usuário pode executar $acao nela.
 * $acao: 'ver', 'editar' ou 'excluir'.
 */
function api_buscar_tarefa(string $acao, array $usuario, string $campo = 'id'): array
{
    $id = input_id($campo);
    $tarefa = $id ? tarefa_buscar($id) : null;

    // Quem não pode ver a tarefa recebe "não encontrada", para não revelar que ela existe.
    if ($tarefa === null || !pode_ver_tarefa($tarefa, $usuario)) {
        json_erro('Tarefa não encontrada.', 404);
    }
    if ($acao !== 'ver' && !pode_alterar_tarefa($acao, $tarefa, $usuario)) {
        json_erro('Você não tem permissão para alterar esta tarefa.', 403);
    }

    return $tarefa;
}

function api_buscar_usuario(string $campo = 'id'): array
{
    $id = input_id($campo);

    return ($id ? usuario_buscar_por_id($id) : null) ?? json_erro('Usuário não encontrado.', 404);
}
