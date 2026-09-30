<?php
declare(strict_types=1);

// Ponto de entrada comum: todo arquivo em public/ começa carregando este.

define('BASE_PATH', dirname(__DIR__));

if (!is_file(BASE_PATH . '/config/config.php')) {
    http_response_code(500);
    exit('Arquivo config/config.php não encontrado. Copie config/config.example.php e preencha os dados.');
}

require BASE_PATH . '/src/helpers.php';
require BASE_PATH . '/src/database.php';
require BASE_PATH . '/src/csrf.php';
require BASE_PATH . '/src/auth.php';
require BASE_PATH . '/src/permissoes.php';
require BASE_PATH . '/src/api.php';
require BASE_PATH . '/src/tarefas.php';
require BASE_PATH . '/src/anexos.php';
require BASE_PATH . '/src/usuarios.php';
require BASE_PATH . '/src/validacao.php';
require BASE_PATH . '/src/repositorios/usuarios.php';
require BASE_PATH . '/src/repositorios/tarefas.php';
require BASE_PATH . '/src/repositorios/anexos.php';

date_default_timezone_set('America/Sao_Paulo');
error_reporting(E_ALL);
// Na API os avisos só vão para o log, para não corromper o JSON.
ini_set('display_errors', config('debug') && !eh_requisicao_api() ? '1' : '0');
// O Apache desta máquina não permite o JIT do PCRE (gera warning em filter_var/preg_*).
ini_set('pcre.jit', '0');

set_exception_handler(function (Throwable $erro): void {
    error_log((string) $erro);
    $mensagem = config('debug') ? $erro->getMessage() : 'Erro interno. Tente novamente mais tarde.';

    if (eh_requisicao_api()) {
        json_erro($mensagem, 500);
    }

    http_response_code(500);
    echo '<h1>Erro interno</h1><p>' . e($mensagem) . '</p>';
});

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}
