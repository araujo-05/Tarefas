// Funções compartilhadas por todas as páginas.

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function mostrarMensagem(elemento, texto, tipo = 'danger') {
    elemento.textContent = texto;
    elemento.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info');
    elemento.classList.add('alert', `alert-${tipo}`);
}

function ocultarMensagem(elemento) {
    elemento.textContent = '';
    elemento.classList.add('d-none');
}

/** POST para a API com o token CSRF; dados é um FormData. */
async function enviar(url, dados) {
    const response = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
        body: dados,
    });

    try {
        return await response.json();
    } catch {
        return { ok: false, msg: 'Resposta inválida do servidor.' };
    }
}

function enviarFormulario(url, form) {
    return enviar(url, new FormData(form));
}

/** Envia um único campo "id" para a API (exclusões, personificação...). */
function enviarId(url, id) {
    const dados = new FormData();
    dados.append('id', id);
    return enviar(url, dados);
}

// Janelas (<dialog class="dialog-app">): fecham pelo botão [data-fechar] ou clicando no fundo.
document.querySelectorAll('dialog.dialog-app').forEach((dialog) => {
    dialog.querySelectorAll('[data-fechar]').forEach((botao) => {
        botao.addEventListener('click', () => dialog.close());
    });
    dialog.addEventListener('click', (evento) => {
        if (evento.target === dialog) {
            dialog.close();
        }
    });
});

/** Converte o HTML de uma <tr> vindo da API em elemento. */
function criarLinha(html) {
    const tbody = document.createElement('tbody');
    tbody.innerHTML = html.trim();
    return tbody.firstElementChild;
}

/**
 * Liga um <form> a um endpoint da API.
 * - validar(form): opcional, retorna uma mensagem de erro para impedir o envio.
 * - aoSucesso(dados, form, mensagem): chamado quando a API responde ok.
 * O elemento [data-mensagem] dentro do form exibe os erros.
 */
function configurarFormulario(form, { url, validar, aoSucesso }) {
    const mensagem = form.querySelector('[data-mensagem]');
    const botao = form.querySelector('[type="submit"]');

    form.addEventListener('submit', async (evento) => {
        evento.preventDefault();
        ocultarMensagem(mensagem);

        const erro = validar?.(form);
        if (erro) {
            mostrarMensagem(mensagem, erro, 'warning');
            return;
        }

        botao.disabled = true;
        try {
            const dados = await enviarFormulario(url, form);
            if (dados.ok) {
                aoSucesso(dados, form, mensagem);
            } else {
                mostrarMensagem(mensagem, dados.msg ?? 'Não foi possível concluir a operação.');
            }
        } catch {
            mostrarMensagem(mensagem, 'Falha de conexão com o servidor.');
        } finally {
            botao.disabled = false;
        }
    });
}
