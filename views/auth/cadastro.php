<section class="card auth-card shadow-lg" aria-labelledby="cadastro-title">
    <div class="card-body p-4 p-sm-5">
        <div class="text-center mb-4">
            <h1 class="h3 fw-bold mb-2" id="cadastro-title">Crie sua conta</h1>
            <p class="text-secondary mb-0">Preencha seus dados para começar</p>
        </div>

        <form id="form-cadastro">
            <div class="alert d-none" data-mensagem role="alert"></div>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control form-control-lg" id="nome" name="nome" placeholder="Seu nome completo" autocomplete="name" maxlength="<?= TEXTO_MAXIMO ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="voce@exemplo.com" autocomplete="email" maxlength="<?= TEXTO_MAXIMO ?>" required>
            </div>
            <div class="mb-4">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control form-control-lg" id="senha" name="senha" placeholder="Crie uma senha" autocomplete="new-password" minlength="<?= SENHA_MINIMO ?>" maxlength="<?= SENHA_MAXIMO ?>" required>
                <div class="form-text">Mínimo de <?= SENHA_MINIMO ?> caracteres.</div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">Cadastrar</button>
        </form>

        <p class="text-center text-secondary small mt-4 mb-0">Já tem uma conta? <a href="login.php" class="text-decoration-none">Entrar</a></p>
    </div>
</section>
