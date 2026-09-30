<?php
// Copie este arquivo para config/config.php e preencha com os dados do seu ambiente.
// O config.php não deve ser versionado (está no .gitignore).

return [
    'debug' => true,

    'db' => [
        'host' => 'localhost',
        'nome' => 'tarefas',
        'usuario' => 'seu_usuario',
        'senha' => 'sua_senha',
        'charset' => 'utf8mb4',
    ],

    // Pasta dos anexos (opcional; padrão storage/anexos/). O Apache precisa poder gravar nela.
    // 'anexos_dir' => '/var/lib/tarefas/anexos',
];
