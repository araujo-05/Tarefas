<?php

// Regras de domínio das tarefas.

const SITUACOES = [
    'pendente' => 'Pendente',
    'em_andamento' => 'Em andamento',
    'concluida' => 'Concluída',
];

function situacao_rotulo(string $situacao): string
{
    return SITUACOES[$situacao] ?? $situacao;
}

/** HTML de uma linha da tabela; usado na página e nas respostas da API. */
function render_linha_tarefa(array $tarefa, array $usuario): string
{
    return render('tarefas/_linha', [
        'tarefa' => $tarefa,
        'verTodas' => pode('tarefas.ver_todas', $usuario),
    ]);
}
