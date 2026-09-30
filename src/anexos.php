<?php

// Anexos das tarefas. Os arquivos ficam em storage/anexos/ (fora de public/)
// com nome aleatório; o download passa por public/anexo.php, que checa a permissão.

const ANEXO_TAMANHO_MAXIMO = 5 * 1024 * 1024; // 5 MB
const ANEXO_EXTENSOES = ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'txt', 'csv', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip'];

/** Pasta dos arquivos (config "anexos_dir"; padrão storage/anexos/). O Apache precisa poder gravar nela. */
function anexos_diretorio(): string
{
    return rtrim(config('anexos_dir', BASE_PATH . '/storage/anexos'), '/');
}

function anexo_caminho(array $anexo): string
{
    return anexos_diretorio() . '/' . $anexo['arquivo'];
}

function formatar_tamanho(int $bytes): string
{
    if ($bytes < 1024) {
        return $bytes . ' B';
    }
    if ($bytes < 1024 * 1024) {
        return number_format($bytes / 1024, 0, ',', '.') . ' KB';
    }

    return number_format($bytes / 1024 / 1024, 1, ',', '.') . ' MB';
}

/** Valida um item de $_FILES. Retorna a mensagem de erro, ou null se estiver ok. */
function validar_upload(mixed $arquivo): ?string
{
    if (!is_array($arquivo) || !isset($arquivo['error']) || is_array($arquivo['error'])) {
        return 'Selecione um arquivo.';
    }

    $limite = formatar_tamanho(ANEXO_TAMANHO_MAXIMO);

    switch ($arquivo['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return 'Selecione um arquivo.';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "O arquivo deve ter no máximo {$limite}.";
        default:
            return 'Falha no envio do arquivo. Tente novamente.';
    }

    if ($arquivo['size'] === 0) {
        return 'O arquivo está vazio.';
    }
    if ($arquivo['size'] > ANEXO_TAMANHO_MAXIMO) {
        return "O arquivo deve ter no máximo {$limite}.";
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, ANEXO_EXTENSOES, true)) {
        return 'Tipo de arquivo não permitido. Aceitos: ' . implode(', ', ANEXO_EXTENSOES) . '.';
    }

    return null;
}

/** Move o upload para storage/anexos/ e registra no banco. Chame validar_upload() antes. */
function anexo_salvar_upload(array $arquivo, int $tarefaId, int $usuarioId): array
{
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $extensao;
    $destino = anexos_diretorio() . '/' . $nomeArquivo;

    // Nome exibido ao usuário: sem caminho e sem caracteres de controle.
    $nomeOriginal = mb_substr(preg_replace('/[\x00-\x1F\x7F]/u', '', basename($arquivo['name'])), 0, 255);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($arquivo['tmp_name']) ?: 'application/octet-stream';

    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        throw new RuntimeException('Não foi possível salvar o arquivo. O servidor precisa de permissão de escrita em ' . anexos_diretorio() . '.');
    }

    try {
        return anexo_criar($tarefaId, $usuarioId, $nomeOriginal, $nomeArquivo, $mime, (int) $arquivo['size']);
    } catch (Throwable $erro) {
        @unlink($destino);
        throw $erro;
    }
}

/** Remove do disco os arquivos dos anexos informados (depois de apagados do banco). */
function anexos_apagar_arquivos(array $anexos): void
{
    foreach ($anexos as $anexo) {
        $caminho = anexo_caminho($anexo);
        if (is_file($caminho)) {
            @unlink($caminho);
        }
    }
}

function anexo_icone(array $anexo): string
{
    $extensao = strtolower(pathinfo($anexo['nome_original'], PATHINFO_EXTENSION));

    return match ($extensao) {
        'pdf' => 'bi-file-earmark-pdf',
        'png', 'jpg', 'jpeg', 'gif', 'webp' => 'bi-file-earmark-image',
        'doc', 'docx', 'txt' => 'bi-file-earmark-text',
        'xls', 'xlsx', 'csv' => 'bi-file-earmark-spreadsheet',
        'ppt', 'pptx' => 'bi-file-earmark-slides',
        'zip' => 'bi-file-earmark-zip',
        default => 'bi-file-earmark',
    };
}

/** HTML da lista de anexos de uma tarefa (usado no diálogo de anexos). */
function render_anexos(array $tarefa, array $usuario): string
{
    return render('tarefas/_anexos', [
        'anexos' => anexos_listar_por_tarefa((int) $tarefa['id']),
        'podeEditar' => pode_alterar_tarefa('editar', $tarefa, $usuario),
    ]);
}
