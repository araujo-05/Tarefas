<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php require BASE_PATH . '/views/partials/head.php'; ?>
</head>
<body class="page-app">
    <?php require BASE_PATH . '/views/partials/personificacao.php'; ?>
    <?php require BASE_PATH . '/views/partials/navbar.php'; ?>

    <main>
        <?= $conteudo ?>
    </main>

    <?php require BASE_PATH . '/views/partials/scripts.php'; ?>
</body>
</html>
