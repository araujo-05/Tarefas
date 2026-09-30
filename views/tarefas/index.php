<?php $colunas = $verTodas ? 7 : 6; ?>
<div id="mensagem-lista" class="alert d-none mensagem-pagina" role="alert"></div>

<div class="card-app card-app--largo">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Situação</th>
                    <?php if ($verTodas): ?>
                        <th>Responsável</th>
                    <?php endif; ?>
                    <th>Data</th>
                    <th>Anexos</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="lista-tarefas">
                <tr data-vazio <?= empty($tarefas) ? '' : 'hidden' ?>>
                    <td colspan="<?= $colunas ?>" class="text-secondary">Nenhuma tarefa cadastrada.</td>
                </tr>
                <?php foreach ($tarefas as $tarefa): ?>
                    <?php require __DIR__ . '/_linha.php'; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="<?= $colunas ?>">
                        <form id="form-tarefa">
                            <div class="alert d-none" data-mensagem role="alert"></div>
                            <div class="form-linha">
                                <label for="descricao">Descrição:</label>
                                <input type="text" class="form-control" name="descricao" id="descricao" placeholder="O que precisa ser feito?" maxlength="<?= TEXTO_MAXIMO ?>" required>
                                <button type="submit" class="btn-app"><i class="bi bi-plus-lg" aria-hidden="true"></i> Adicionar</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<dialog id="dialog-editar" class="dialog-app" aria-labelledby="dialog-editar-titulo">
    <form id="form-editar" class="card-app">
        <h2 class="card-app__titulo" id="dialog-editar-titulo">Editar tarefa</h2>
        <div class="card-app__corpo">
            <div class="alert d-none" data-mensagem role="alert"></div>
            <input type="hidden" name="id">

            <div class="mb-3">
                <label for="editar-descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" id="editar-descricao" name="descricao" maxlength="<?= TEXTO_MAXIMO ?>" required>
            </div>
            <div>
                <label for="editar-situacao" class="form-label">Situação</label>
                <select class="form-select" id="editar-situacao" name="situacao">
                    <?php foreach (SITUACOES as $valor => $rotulo): ?>
                        <option value="<?= e($valor) ?>"><?= e($rotulo) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-app__rodape d-flex justify-content-end gap-2">
            <button type="button" class="btn-app btn-app--secundario" data-fechar>Cancelar</button>
            <button type="submit" class="btn-app"><i class="bi bi-check-lg" aria-hidden="true"></i> Salvar</button>
        </div>
    </form>
</dialog>

<dialog id="dialog-anexos" class="dialog-app dialog-app--largo" aria-labelledby="dialog-anexos-titulo">
    <div class="card-app">
        <h2 class="card-app__titulo" id="dialog-anexos-titulo"><i class="bi bi-paperclip" aria-hidden="true"></i> Anexos · <span data-titulo-tarefa></span></h2>
        <div class="card-app__corpo">
            <div class="alert d-none" data-mensagem-anexos role="alert"></div>
            <div data-lista-anexos></div>
        </div>
        <form id="form-anexo" class="card-app__rodape">
            <div class="alert d-none" data-mensagem role="alert"></div>
            <input type="hidden" name="id">
            <div class="form-linha">
                <input type="file" class="form-control" name="arquivo" required
                       accept="<?= e(implode(',', array_map(fn($ext) => '.' . $ext, ANEXO_EXTENSOES))) ?>">
                <button type="submit" class="btn-app"><i class="bi bi-upload" aria-hidden="true"></i> Enviar</button>
            </div>
            <div class="form-text mt-2">Até <?= e(formatar_tamanho(ANEXO_TAMANHO_MAXIMO)) ?>. Tipos: <?= e(implode(', ', ANEXO_EXTENSOES)) ?>.</div>
        </form>
        <div class="card-app__rodape d-flex justify-content-end">
            <button type="button" class="btn-app btn-app--secundario" data-fechar>Fechar</button>
        </div>
    </div>
</dialog>
