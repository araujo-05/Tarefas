<?php
// Popula o banco com usuários e tarefas de exemplo.
// Uso: php database/seed.php
// Pode ser executado mais de uma vez: e-mails e tarefas já existentes são ignorados.

if (PHP_SAPI !== 'cli') {
    exit('Execute pela linha de comando: php database/seed.php');
}

$_SERVER['REQUEST_METHOD'] = 'GET';
require dirname(__DIR__) . '/src/bootstrap.php';

const SENHA_PADRAO = '123456';

$usuarios = [
    ['nome' => 'Mariana Souza', 'email' => 'mariana@tarefas.local', 'perfil' => 'admin'],
    ['nome' => 'Carlos Lima', 'email' => 'carlos@tarefas.local', 'perfil' => 'usuario'],
    ['nome' => 'Fernanda Rocha', 'email' => 'fernanda@tarefas.local', 'perfil' => 'usuario'],
    ['nome' => 'Rafael Martins', 'email' => 'rafael@tarefas.local', 'perfil' => 'usuario'],
    ['nome' => 'Juliana Alves', 'email' => 'juliana@tarefas.local', 'perfil' => 'usuario'],
];

// [e-mail do dono, descrição, situação, dias atrás]
$tarefas = [
    ['mariana@tarefas.local', 'Revisar permissões dos usuários', 'concluida', 20],
    ['mariana@tarefas.local', 'Definir metas do trimestre', 'em_andamento', 6],
    ['mariana@tarefas.local', 'Aprovar orçamento de marketing', 'pendente', 2],
    ['mariana@tarefas.local', 'Agendar reunião com a diretoria', 'pendente', 0],

    ['carlos@tarefas.local', 'Corrigir bug no formulário de cadastro', 'concluida', 15],
    ['carlos@tarefas.local', 'Criar testes da API de tarefas', 'em_andamento', 5],
    ['carlos@tarefas.local', 'Atualizar dependências do projeto', 'pendente', 3],
    ['carlos@tarefas.local', 'Documentar endpoints no README', 'pendente', 1],

    ['fernanda@tarefas.local', 'Desenhar protótipo da tela de relatórios', 'concluida', 18],
    ['fernanda@tarefas.local', 'Ajustar layout para celular', 'em_andamento', 4],
    ['fernanda@tarefas.local', 'Escolher paleta de cores do dashboard', 'pendente', 2],

    ['rafael@tarefas.local', 'Configurar backup diário do banco', 'concluida', 25],
    ['rafael@tarefas.local', 'Migrar servidor para PHP 8.5', 'concluida', 12],
    ['rafael@tarefas.local', 'Monitorar uso de disco do servidor', 'em_andamento', 7],
    ['rafael@tarefas.local', 'Renovar certificado SSL', 'pendente', 1],

    ['juliana@tarefas.local', 'Responder e-mails de clientes', 'concluida', 9],
    ['juliana@tarefas.local', 'Preparar apresentação de resultados', 'em_andamento', 3],
    ['juliana@tarefas.local', 'Organizar treinamento da equipe', 'pendente', 0],
];

$pdo = db();
$pdo->beginTransaction();

$idsPorEmail = [];
$usuariosCriados = 0;

foreach ($usuarios as $u) {
    $existente = usuario_buscar_por_email($u['email']);
    if ($existente !== null) {
        $idsPorEmail[$u['email']] = (int) $existente['id'];
        continue;
    }

    $idsPorEmail[$u['email']] = usuario_criar($u['nome'], $u['email'], SENHA_PADRAO, $u['perfil']);
    $usuariosCriados++;
}

$existeTarefa = $pdo->prepare('SELECT 1 FROM tasks WHERE usuario = :usuario AND descricao = :descricao');
$inserirTarefa = $pdo->prepare('INSERT INTO tasks (descricao, situacao, usuario, data_inclusao)
    VALUES (:descricao, :situacao, :usuario, NOW() - INTERVAL :dias DAY)');
$tarefasCriadas = 0;

foreach ($tarefas as [$email, $descricao, $situacao, $dias]) {
    $dados = ['usuario' => $idsPorEmail[$email], 'descricao' => $descricao];

    $existeTarefa->execute($dados);
    if ($existeTarefa->fetchColumn()) {
        continue;
    }

    $inserirTarefa->execute($dados + ['situacao' => $situacao, 'dias' => $dias]);
    $tarefasCriadas++;
}

$pdo->commit();

echo "Usuários criados: {$usuariosCriados}\n";
echo "Tarefas criadas: {$tarefasCriadas}\n";
echo 'Senha de todos os usuários de exemplo: ' . SENHA_PADRAO . "\n";
