<?php
require dirname(__DIR__) . '/src/bootstrap.php';

redirecionar_se_logado();

view('auth/cadastro', [
    'titulo' => 'Criar conta',
    'scripts' => ['cadastro.js'],
], 'auth');
