<section class="card auth-card shadow-lg" aria-labelledby="login-title">
    <div class="card-body p-4 p-sm-5">
        <div class="text-center mb-4">
            <div class="auth-logo bg-primary-subtle text-primary rounded-circle mb-3" aria-hidden="true"><i class="bi bi-check2-square"></i></div>
            <h1 class="h3 fw-bold mb-2" id="login-title">Bem-vindo de volta</h1>
            <p class="text-secondary mb-0">Entre na sua conta para continuar</p>
        </div>

        <?php if ($cadastrado): ?>
            <div class="alert alert-success" role="alert">Conta criada! Entre com seu e-mail e senha.</div>
        <?php endif; ?>

        <form id="form-login">
            <div class="alert d-none" data-mensagem role="alert"></div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="voce@exemplo.com" autocomplete="email" required>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="senha" class="form-label">Senha</label>
                    <a href="#" class="small text-decoration-none">Esqueceu a senha?</a>
                </div>
                <input type="password" class="form-control form-control-lg" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" value="1" id="lembrar" name="lembrar">
                <label class="form-check-label" for="lembrar">Manter conectado</label>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
        </form>

        <p class="text-center text-secondary small mt-4 mb-0">Ainda não tem uma conta? <a href="cadastro.php" class="text-decoration-none">Cadastre-se</a></p>
    </div>
</section>
