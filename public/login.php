<?php
require dirname(__DIR__) . '/src/bootstrap.php';

redirecionar_se_logado();

view('auth/login', [
    'titulo' => 'Entrar',
    'cadastrado' => isset($_GET['cadastrado']),
    'scripts' => ['login.js'],
], 'auth');
