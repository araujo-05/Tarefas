<section class="card-app card-app--estreito" aria-labelledby="senha-title">
    <h1 class="card-app__titulo" id="senha-title">Trocar senha</h1>

    <form id="form-senha">
        <div class="card-app__corpo">
            <div class="alert d-none" data-mensagem role="alert"></div>

            <div class="mb-3">
                <label for="senha_atual" class="form-label">Senha atual</label>
                <input type="password" class="form-control" id="senha_atual" name="senha_atual" autocomplete="current-password" required>
            </div>
            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" autocomplete="new-password" minlength="<?= SENHA_MINIMO ?>" maxlength="<?= SENHA_MAXIMO ?>" required>
                <div class="form-text">Mínimo de <?= SENHA_MINIMO ?> caracteres.</div>
            </div>
            <div>
                <label for="confirmar_senha" class="form-label">Confirmar nova senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" autocomplete="new-password" required>
            </div>
        </div>
        <div class="card-app__rodape">
            <button type="submit" class="btn-app w-100">Salvar nova senha</button>
        </div>
    </form>
</section>
