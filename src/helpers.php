<?php

function config(string $chave, mixed $padrao = null): mixed
{
    static $config = null;
    $config ??= require BASE_PATH . '/config/config.php';

    return $config[$chave] ?? $padrao;
}

/** Escapa um valor para exibição segura no HTML. */
function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

function redirecionar(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/** Lê um campo enviado via POST como string. */
function input(string $campo, bool $trim = true): string
{
    $valor = $_POST[$campo] ?? '';
    if (!is_string($valor)) {
        return '';
    }

    return $trim ? trim($valor) : $valor;
}

function pagina_atual(): string
{
    return basename($_SERVER['SCRIPT_NAME'] ?? '');
}

function formatar_data(string $data): string
{
    return date('d/m/Y', strtotime($data));
}

/** Renderiza uma view e devolve o HTML. As chaves de $dados viram variáveis na view. */
function render(string $__view, array $dados = []): string
{
    extract($dados, EXTR_SKIP);

    ob_start();
    require BASE_PATH . "/views/{$__view}.php";

    return ob_get_clean();
}

/** Renderiza uma view dentro de um layout e envia para o navegador. */
function view(string $__view, array $dados = [], string $__layout = 'app'): void
{
    $conteudo = render($__view, $dados);

    extract($dados, EXTR_SKIP);
    require BASE_PATH . "/views/layouts/{$__layout}.php";
}
