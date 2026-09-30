<?php $bloqueado = $personificando ? 'disabled' : ''; ?>
<div class="pagina-topo">
    <h1><i class="bi bi-person-circle" aria-hidden="true"></i> Minha conta</h1>
</div>

<?php if ($personificando): ?>
    <div class="alert alert-warning mensagem-pagina" role="alert">
        <i class="bi bi-lock" aria-hidden="true"></i>
        Durante a personificação não é possível alterar os dados nem a senha deste usuário.
    </div>
<?php endif; ?>

<div class="conta-grid">
    <section class="card-app" aria-labelledby="dados-title">
        <h2 class="card-app__titulo" id="dados-title"><i class="bi bi-person-vcard" aria-hidden="true"></i> Meus dados</h2>

        <form id="form-dados">
            <fieldset <?= $bloqueado ?>>
                <div class="card-app__corpo">
                    <div class="alert d-none" data-mensagem role="alert"></div>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= e($usuario['nome']) ?>" autocomplete="name" maxlength="<?= TEXTO_MAXIMO ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($usuario['email']) ?>" autocomplete="email" maxlength="<?= TEXTO_MAXIMO ?>" required>
                    </div>
                    <div>
                        <span class="form-label d-block">Perfil</span>
                        <span class="perfil perfil--<?= e($usuario['perfil']) ?>"><?= e(PERFIS[$usuario['perfil']]) ?></span>
                    </div>
                </div>
                <div class="card-app__rodape">
                    <button type="submit" class="btn-app w-100"><i class="bi bi-check-lg" aria-hidden="true"></i> Salvar dados</button>
                </div>
            </fieldset>
        </form>
    </section>

    <section class="card-app" aria-labelledby="senha-title">
        <h2 class="card-app__titulo" id="senha-title"><i class="bi bi-key" aria-hidden="true"></i> Trocar senha</h2>

        <form id="form-senha">
            <fieldset <?= $bloqueado ?>>
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
                    <button type="submit" class="btn-app w-100"><i class="bi bi-shield-check" aria-hidden="true"></i> Salvar nova senha</button>
                </div>
            </fieldset>
        </form>
    </section>
</div>
