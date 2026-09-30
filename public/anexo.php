<?php
require dirname(__DIR__) . '/src/bootstrap.php';

// Download de um anexo: anexo.php?id=123

$usuario = exigir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$anexo = $id ? anexo_buscar($id) : null;
$tarefa = $anexo ? tarefa_buscar((int) $anexo['tarefa']) : null;
$caminho = $anexo ? anexo_caminho($anexo) : null;

if ($tarefa === null || !pode_ver_tarefa($tarefa, $usuario) || !is_file($caminho)) {
    http_response_code(404);
    exit('Anexo não encontrado.');
}

// Sempre como download e sem "adivinhar" o tipo, para o navegador não executar o conteúdo.
$nomeAscii = preg_replace('/[^A-Za-z0-9._-]/u', '_', $anexo['nome_original']);

header('Content-Type: ' . $anexo['mime']);
header('Content-Length: ' . filesize($caminho));
header("Content-Disposition: attachment; filename=\"{$nomeAscii}\"; filename*=UTF-8''" . rawurlencode($anexo['nome_original']));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');

readfile($caminho);
