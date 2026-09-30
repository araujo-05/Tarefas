<?php

/** Retorna a conexão PDO, criada uma única vez por requisição. */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $db = config('db');
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $db['host'], $db['nome'], $db['charset']);

        $pdo = new PDO($dsn, $db['usuario'], $db['senha'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}
