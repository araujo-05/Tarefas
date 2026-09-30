<?php

function csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

/** Aceita o token pelo cabeçalho X-CSRF-Token (fetch) ou pelo campo csrf_token (form). */
function csrf_valido(): bool
{
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';

    return is_string($token) && hash_equals(csrf_token(), $token);
}
