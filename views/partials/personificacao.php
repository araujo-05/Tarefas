<?php $adminPersonificando = personificador(); ?>
<?php if ($adminPersonificando !== null): ?>
    <div class="banner-personificacao" role="status">
        <span>
            <i class="bi bi-incognito" aria-hidden="true"></i>
            Você (<?= e($adminPersonificando['nome']) ?>) está navegando como <strong><?= e(usuario_logado()['nome']) ?></strong>.
        </span>
        <form method="post" action="personificar_sair.php" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <button type="submit"><i class="bi bi-arrow-return-left" aria-hidden="true"></i> Voltar para minha conta</button>
        </form>
    </div>
<?php endif; ?>
