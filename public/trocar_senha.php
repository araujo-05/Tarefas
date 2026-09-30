<?php
require dirname(__DIR__) . '/src/bootstrap.php';

// Página antiga: a troca de senha agora fica em "Minha conta".
redirecionar('conta.php');
