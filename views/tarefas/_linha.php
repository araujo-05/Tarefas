<tr data-id="<?= (int) $tarefa['id'] ?>" data-descricao="<?= e($tarefa['descricao']) ?>" data-situacao="<?= e($tarefa['situacao']) ?>"
    data-usuario="<?= (int) $tarefa['usuario'] ?>" data-responsavel="<?= e($tarefa['responsavel']) ?>" data-data="<?= e(date('Y-m-d', strtotime($tarefa['data_inclusao']))) ?>">
    <td><?= (int) $tarefa['id'] ?></td>
    <td class="tarefa-descricao"><?= e($tarefa['descricao']) ?></td>
    <td><span class="situacao situacao--<?= e($tarefa['situacao']) ?>"><?= e(situacao_rotulo($tarefa['situacao'])) ?></span></td>
    <?php if ($verTodas): ?>
        <td><?= e($tarefa['responsavel']) ?></td>
    <?php endif; ?>
    <td><?= e(formatar_data($tarefa['data_inclusao'])) ?></td>
    <td>
        <button type="button" class="btn-acao" data-acao="anexos" title="Anexos">
            <i class="bi bi-paperclip" aria-hidden="true"></i> <span data-total-anexos><?= (int) $tarefa['total_anexos'] ?></span>
        </button>
    </td>
    <td class="acoes">
        <?php if (pode_alterar_tarefa('editar', $tarefa)): ?>
            <button type="button" class="btn-acao" data-acao="editar" title="Editar"><i class="bi bi-pencil" aria-hidden="true"></i> Editar</button>
        <?php endif; ?>
        <?php if (pode_alterar_tarefa('excluir', $tarefa)): ?>
            <button type="button" class="btn-acao btn-acao--perigo" data-acao="excluir" title="Excluir"><i class="bi bi-trash" aria-hidden="true"></i> Excluir</button>
        <?php endif; ?>
    </td>
</tr>
