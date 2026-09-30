<?php if (empty($anexos)): ?>
    <p class="text-secondary mb-0">Nenhum anexo nesta tarefa.</p>
<?php else: ?>
    <ul class="lista-anexos">
        <?php foreach ($anexos as $anexo): ?>
            <li>
                <i class="bi <?= e(anexo_icone($anexo)) ?> lista-anexos__icone" aria-hidden="true"></i>
                <div class="lista-anexos__info">
                    <a href="anexo.php?id=<?= (int) $anexo['id'] ?>" class="lista-anexos__nome"><?= e($anexo['nome_original']) ?></a>
                    <span class="lista-anexos__meta">
                        <?= e(formatar_tamanho((int) $anexo['tamanho'])) ?> ·
                        <?= e(formatar_data($anexo['data_inclusao'])) ?>
                        <?php if ($anexo['enviado_por'] !== null): ?> · <?= e($anexo['enviado_por']) ?><?php endif; ?>
                    </span>
                </div>
                <a href="anexo.php?id=<?= (int) $anexo['id'] ?>" class="btn-acao" title="Baixar"><i class="bi bi-download" aria-hidden="true"></i></a>
                <?php if ($podeEditar): ?>
                    <button type="button" class="btn-acao btn-acao--perigo" data-excluir-anexo="<?= (int) $anexo['id'] ?>" data-nome="<?= e($anexo['nome_original']) ?>" title="Excluir"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
