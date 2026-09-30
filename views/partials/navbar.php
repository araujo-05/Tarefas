<?php
$usuarioNavbar = usuario_logado();
$links = [
    ['index.php', 'bi-list-check', pode('tarefas.ver_todas') ? 'Todas as tarefas' : 'Minhas tarefas', true],
    ['usuarios.php', 'bi-people', 'Usuários', pode('usuarios.gerenciar')],
    ['conta.php', 'bi-person-circle', 'Minha conta', true],
];
?>
<header class="app-header">
    <a class="brand" href="index.php">
        <span class="brand-logo" aria-hidden="true"><i class="bi bi-check2-square"></i></span>
        Tarefas
    </a>
    <nav>
        <?php foreach ($links as [$href, $icone, $rotulo, $visivel]): ?>
            <?php if ($visivel): ?>
                <a href="<?= e($href) ?>" class="<?= pagina_atual() === $href ? 'active' : '' ?>">
                    <i class="bi <?= e($icone) ?>" aria-hidden="true"></i> <?= e($rotulo) ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <div class="user">
        <span class="user-name">Olá, <span data-nome-usuario><?= e($usuarioNavbar['nome'] ?? '') ?></span></span>
        <?php if (eh_admin($usuarioNavbar)): ?>
            <span class="badge-perfil"><?= e(PERFIS['admin']) ?></span>
        <?php endif; ?>
        <form method="post" action="logout.php" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <button type="submit" class="btn btn-danger btn-sair"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Sair</button>
        </form>
    </div>
</header>
