const listaTarefas = document.getElementById('lista-tarefas');
const linhaVazia = listaTarefas.querySelector('[data-vazio]');
const linhaSemResultado = listaTarefas.querySelector('[data-sem-resultado]');
const formFiltro = document.getElementById('form-filtro');
const mensagemLista = document.getElementById('mensagem-lista');
const dialogEditar = document.getElementById('dialog-editar');
const formEditar = document.getElementById('form-editar');
const dialogAnexos = document.getElementById('dialog-anexos');
const formAnexo = document.getElementById('form-anexo');
const listaAnexos = dialogAnexos.querySelector('[data-lista-anexos]');
const mensagemAnexos = dialogAnexos.querySelector('[data-mensagem-anexos]');

function atualizarLinhaVazia() {
    linhaVazia.hidden = listaTarefas.querySelector('tr[data-id]') !== null;
    aplicarFiltro();
}

function linhaDaTarefa(id) {
    return listaTarefas.querySelector(`tr[data-id="${id}"]`);
}

// ---------- Filtro ----------

/** Minúsculas e sem acentos, para a busca ignorar maiúsculas e acentuação. */
function normalizar(texto) {
    return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
}

// Filtro em uso: só muda ao clicar em Pesquisar (ou Limpar), não a cada digitação.
let filtro = {};

function lerFiltro() {
    const campo = (nome) => formFiltro.elements.namedItem(nome)?.value ?? '';
    filtro = {
        texto: normalizar(campo('texto').trim()),
        situacao: campo('situacao'),
        usuario: campo('usuario'),
        data: campo('data'),
    };
}

function aplicarFiltro() {
    const { texto, situacao, usuario, data } = filtro;

    let visiveis = 0;
    listaTarefas.querySelectorAll('tr[data-id]').forEach((linha) => {
        const { id, descricao, responsavel } = linha.dataset;
        const visivel = (!texto || normalizar(`${id} ${descricao} ${responsavel}`).includes(texto))
            && (!situacao || linha.dataset.situacao === situacao)
            && (!usuario || linha.dataset.usuario === usuario)
            && (!data || linha.dataset.data === data);
        linha.hidden = !visivel;
        visiveis += visivel ? 1 : 0;
    });

    linhaSemResultado.hidden = visiveis > 0 || !linhaVazia.hidden;
}

formFiltro.addEventListener('submit', (evento) => {
    evento.preventDefault();
    lerFiltro();
    aplicarFiltro();
});
// O reset limpa os campos só depois do evento, por isso o filtro roda no próximo ciclo.
formFiltro.addEventListener('reset', () => setTimeout(() => {
    lerFiltro();
    aplicarFiltro();
}));

// ---------- Adicionar ----------

configurarFormulario(document.getElementById('form-tarefa'), {
    url: 'api/tarefas.php',
    aoSucesso: (dados, form) => {
        listaTarefas.appendChild(criarLinha(dados.html));
        atualizarLinhaVazia();
        form.reset();
        form.descricao.focus();
    },
});

// ---------- Editar ----------

function abrirEdicao(linha) {
    ocultarMensagem(formEditar.querySelector('[data-mensagem]'));
    formEditar.elements.namedItem('id').value = linha.dataset.id;
    formEditar.elements.namedItem('descricao').value = linha.dataset.descricao;
    formEditar.elements.namedItem('situacao').value = linha.dataset.situacao;
    dialogEditar.showModal();
}

configurarFormulario(formEditar, {
    url: 'api/tarefas_editar.php',
    aoSucesso: (dados) => {
        linhaDaTarefa(dados.id)?.replaceWith(criarLinha(dados.html));
        aplicarFiltro();
        dialogEditar.close();
        mostrarMensagem(mensagemLista, dados.msg, 'success');
    },
});

// ---------- Excluir ----------

async function excluir(linha, botao) {
    if (!confirm(`Excluir a tarefa "${linha.dataset.descricao}" e todos os anexos dela?`)) {
        return;
    }

    botao.disabled = true;
    try {
        const resposta = await enviarId('api/tarefas_excluir.php', linha.dataset.id);
        if (resposta.ok) {
            linha.remove();
            atualizarLinhaVazia();
            mostrarMensagem(mensagemLista, resposta.msg, 'success');
        } else {
            mostrarMensagem(mensagemLista, resposta.msg ?? 'Não foi possível excluir a tarefa.');
        }
    } catch {
        mostrarMensagem(mensagemLista, 'Falha de conexão com o servidor.');
    } finally {
        botao.disabled = false;
    }
}

// ---------- Anexos ----------

function atualizarAnexos(dados) {
    listaAnexos.innerHTML = dados.html;
    if (dados.total !== undefined) {
        const contador = linhaDaTarefa(formAnexo.elements.namedItem('id').value)?.querySelector('[data-total-anexos]');
        if (contador) {
            contador.textContent = dados.total;
        }
    }
}

async function abrirAnexos(linha) {
    ocultarMensagem(mensagemAnexos);
    ocultarMensagem(formAnexo.querySelector('[data-mensagem]'));
    formAnexo.reset();
    formAnexo.elements.namedItem('id').value = linha.dataset.id;
    dialogAnexos.querySelector('[data-titulo-tarefa]').textContent = linha.dataset.descricao;
    listaAnexos.innerHTML = '<p class="text-secondary mb-0">Carregando…</p>';
    formAnexo.hidden = true;
    dialogAnexos.showModal();

    try {
        const dados = await enviarId('api/anexos_listar.php', linha.dataset.id);
        if (dados.ok) {
            atualizarAnexos(dados);
            formAnexo.hidden = !dados.podeEditar;
        } else {
            listaAnexos.innerHTML = '';
            mostrarMensagem(mensagemAnexos, dados.msg ?? 'Não foi possível carregar os anexos.');
        }
    } catch {
        listaAnexos.innerHTML = '';
        mostrarMensagem(mensagemAnexos, 'Falha de conexão com o servidor.');
    }
}

configurarFormulario(formAnexo, {
    url: 'api/anexos_enviar.php',
    aoSucesso: (dados, form) => {
        atualizarAnexos(dados);
        const id = form.elements.namedItem('id').value;
        form.reset();
        form.elements.namedItem('id').value = id;
        mostrarMensagem(mensagemAnexos, dados.msg, 'success');
    },
});

listaAnexos.addEventListener('click', async (evento) => {
    const botao = evento.target.closest('[data-excluir-anexo]');
    if (!botao || !confirm(`Excluir o anexo "${botao.dataset.nome}"?`)) {
        return;
    }

    botao.disabled = true;
    try {
        const dados = await enviarId('api/anexos_excluir.php', botao.dataset.excluirAnexo);
        if (dados.ok) {
            atualizarAnexos(dados);
            mostrarMensagem(mensagemAnexos, dados.msg, 'success');
        } else {
            mostrarMensagem(mensagemAnexos, dados.msg ?? 'Não foi possível excluir o anexo.');
            botao.disabled = false;
        }
    } catch {
        mostrarMensagem(mensagemAnexos, 'Falha de conexão com o servidor.');
        botao.disabled = false;
    }
});

// ---------- Botões de ação das linhas ----------

listaTarefas.addEventListener('click', (evento) => {
    const botao = evento.target.closest('[data-acao]');
    if (!botao) {
        return;
    }

    const linha = botao.closest('tr');
    const acoes = {
        editar: () => abrirEdicao(linha),
        excluir: () => excluir(linha, botao),
        anexos: () => abrirAnexos(linha),
    };
    acoes[botao.dataset.acao]?.();
});
