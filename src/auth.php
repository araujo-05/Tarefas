<?php

/**
 * Usuário da sessão, recarregado do banco uma vez por requisição
 * (assim mudanças de perfil valem na hora e usuários removidos perdem o acesso).
 * Durante uma personificação, é o usuário personificado.
 */
function usuario_logado(): ?array
{
    static $carregado = false;
    static $usuario = null;

    if (!$carregado) {
        $carregado = true;
        $id = $_SESSION['usuario']['id'] ?? null;
        $banco = $id !== null ? usuario_buscar_por_id((int) $id) : null;

        if ($banco === null || (isset($_SESSION['personificador_id']) && personificador() === null)) {
            unset($_SESSION['usuario'], $_SESSION['personificador_id']);
        } else {
            $usuario = $_SESSION['usuario'] = dados_sessao($banco);
        }
    }

    return $usuario;
}

function dados_sessao(array $usuario): array
{
    return [
        'id' => (int) $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'perfil' => $usuario['perfil'],
    ];
}

/** Garante que há um usuário logado (páginas); caso contrário, manda para o login. */
function exigir_login(): array
{
    return usuario_logado() ?? redirecionar('login.php');
}

/** Para páginas: exige login e a permissão; sem ela, mostra a página 403. */
function exigir_permissao(string $permissao): array
{
    $usuario = exigir_login();

    if (!pode($permissao, $usuario)) {
        http_response_code(403);
        view('erros/403', ['titulo' => 'Acesso negado']);
        exit;
    }

    return $usuario;
}

function redirecionar_se_logado(): void
{
    if (usuario_logado() !== null) {
        redirecionar('index.php');
    }
}

/** Guarda na sessão apenas os dados necessários (nunca o hash da senha). */
function autenticar(array $usuario): void
{
    session_regenerate_id(true);
    unset($_SESSION['personificador_id']);

    $_SESSION['usuario'] = dados_sessao($usuario);
}

function encerrar_sessao(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
}

// ---------- Personificação ----------
// O administrador passa a navegar como outro usuário. O id dele fica em
// $_SESSION['personificador_id'] para poder voltar à própria conta.

/** Administrador que está personificando, ou null. Deixa de valer se ele perder a permissão. */
function personificador(): ?array
{
    static $carregado = false;
    static $personificador = null;

    if (!$carregado) {
        $carregado = true;
        $id = $_SESSION['personificador_id'] ?? null;
        $banco = $id !== null ? usuario_buscar_por_id((int) $id) : null;

        if ($banco !== null && pode('usuarios.personificar', dados_sessao($banco))) {
            $personificador = dados_sessao($banco);
        }
    }

    return $personificador;
}

function esta_personificando(): bool
{
    return personificador() !== null;
}

function iniciar_personificacao(array $admin, array $alvo): void
{
    session_regenerate_id(true);
    $_SESSION['personificador_id'] = $admin['id'];
    $_SESSION['usuario'] = dados_sessao($alvo);

    error_log("Personificação: usuário {$admin['id']} ({$admin['email']}) entrou como {$alvo['id']} ({$alvo['email']})");
}

function encerrar_personificacao(): void
{
    $admin = personificador();
    if ($admin === null) {
        return;
    }

    session_regenerate_id(true);
    unset($_SESSION['personificador_id']);
    $_SESSION['usuario'] = $admin;

    error_log("Personificação: usuário {$admin['id']} voltou à própria conta");
}
