<div class="pagina-topo">
    <h1><i class="bi bi-people" aria-hidden="true"></i> Usuários</h1>
    <button type="button" class="btn-app" id="btn-novo-usuario"><i class="bi bi-person-plus" aria-hidden="true"></i> Novo usuário</button>
</div>

<div id="mensagem-lista" class="alert d-none mensagem-pagina" role="alert"></div>

<div class="card-app card-app--largo">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Tarefas</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="lista-usuarios" data-usuario-logado="<?= (int) $usuarioLogado['id'] ?>">
                <?php foreach ($usuarios as $item): ?>
                    <?= render_linha_usuario($item, $usuarioLogado) ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<dialog id="dialog-usuario" class="dialog-app" aria-labelledby="dialog-usuario-titulo">
    <form id="form-usuario" class="card-app">
        <h2 class="card-app__titulo" id="dialog-usuario-titulo">Usuário</h2>
        <div class="card-app__corpo">
            <div class="alert d-none" data-mensagem role="alert"></div>
            <input type="hidden" name="id">

            <div class="mb-3">
                <label for="usuario-nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="usuario-nome" name="nome" maxlength="<?= TEXTO_MAXIMO ?>" required>
            </div>
            <div class="mb-3">
                <label for="usuario-email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="usuario-email" name="email" maxlength="<?= TEXTO_MAXIMO ?>" required>
            </div>
            <div class="mb-3">
                <label for="usuario-perfil" class="form-label">Perfil</label>
                <select class="form-select" id="usuario-perfil" name="perfil">
                    <?php foreach (PERFIS as $valor => $rotulo): ?>
                        <option value="<?= e($valor) ?>"><?= e($rotulo) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text" data-aviso-perfil hidden>Você não pode alterar o seu próprio perfil.</div>
            </div>
            <div>
                <label for="usuario-senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="usuario-senha" name="senha" autocomplete="new-password" minlength="<?= SENHA_MINIMO ?>" maxlength="<?= SENHA_MAXIMO ?>">
                <div class="form-text" data-ajuda-senha
                     data-novo="Mínimo de <?= SENHA_MINIMO ?> caracteres."
                     data-edicao="Deixe em branco para manter a senha atual."></div>
            </div>
        </div>
        <div class="card-app__rodape d-flex justify-content-end gap-2">
            <button type="button" class="btn-app btn-app--secundario" data-fechar>Cancelar</button>
            <button type="submit" class="btn-app"><i class="bi bi-check-lg" aria-hidden="true"></i> Salvar</button>
        </div>
    </form>
</dialog>
