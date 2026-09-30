<tr data-id="<?= (int) $item['id'] ?>" data-nome="<?= e($item['nome']) ?>" data-email="<?= e($item['email']) ?>" data-perfil="<?= e($item['perfil']) ?>">
    <td><?= (int) $item['id'] ?></td>
    <td>
        <?= e($item['nome']) ?>
        <?php if ($ehVoce): ?><span class="text-secondary small">(você)</span><?php endif; ?>
    </td>
    <td><?= e($item['email']) ?></td>
    <td><span class="perfil perfil--<?= e($item['perfil']) ?>"><?= e(PERFIS[$item['perfil']] ?? $item['perfil']) ?></span></td>
    <td><?= (int) $item['total_tarefas'] ?></td>
    <td class="acoes">
        <button type="button" class="btn-acao" data-acao="editar" title="Editar"><i class="bi bi-pencil" aria-hidden="true"></i> Editar</button>
        <?php if ($podePersonificar && !$ehVoce && $item['perfil'] !== 'admin'): ?>
            <button type="button" class="btn-acao" data-acao="personificar" title="Navegar como este usuário"><i class="bi bi-incognito" aria-hidden="true"></i> Personificar</button>
        <?php endif; ?>
        <?php if (!$ehVoce): ?>
            <button type="button" class="btn-acao btn-acao--perigo" data-acao="excluir" title="Excluir"><i class="bi bi-trash" aria-hidden="true"></i> Excluir</button>
        <?php endif; ?>
    </td>
</tr>
