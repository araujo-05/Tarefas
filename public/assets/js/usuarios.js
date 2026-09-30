const listaUsuarios = document.getElementById('lista-usuarios');
const usuarioLogadoId = listaUsuarios.dataset.usuarioLogado;
const mensagemLista = document.getElementById('mensagem-lista');
const dialogUsuario = document.getElementById('dialog-usuario');
const formUsuario = document.getElementById('form-usuario');
const campos = formUsuario.elements;
const ajudaSenha = formUsuario.querySelector('[data-ajuda-senha]');
const avisoPerfil = formUsuario.querySelector('[data-aviso-perfil]');

// ---------- Criar / editar ----------

function abrirFormulario(linha = null) {
    const editando = linha !== null;
    const ehVoce = editando && linha.dataset.id === usuarioLogadoId;

    formUsuario.reset();
    ocultarMensagem(formUsuario.querySelector('[data-mensagem]'));
    dialogUsuario.querySelector('#dialog-usuario-titulo').textContent = editando ? 'Editar usuário' : 'Novo usuário';

    campos.namedItem('id').value = editando ? linha.dataset.id : '';
    campos.namedItem('nome').value = editando ? linha.dataset.nome : '';
    campos.namedItem('email').value = editando ? linha.dataset.email : '';
    campos.namedItem('perfil').value = editando ? linha.dataset.perfil : 'usuario';
    campos.namedItem('perfil').disabled = ehVoce;
    avisoPerfil.hidden = !ehVoce;

    campos.namedItem('senha').required = !editando;
    ajudaSenha.textContent = editando ? ajudaSenha.dataset.edicao : ajudaSenha.dataset.novo;

    dialogUsuario.showModal();
}

document.getElementById('btn-novo-usuario').addEventListener('click', () => abrirFormulario());

configurarFormulario(formUsuario, {
    url: 'api/usuarios_salvar.php',
    aoSucesso: (dados) => {
        const nova = criarLinha(dados.html);
        const atual = listaUsuarios.querySelector(`tr[data-id="${dados.id}"]`);
        atual ? atual.replaceWith(nova) : listaUsuarios.appendChild(nova);
        dialogUsuario.close();
        mostrarMensagem(mensagemLista, dados.msg, 'success');
    },
});

// O campo desabilitado não é enviado; manda o perfil atual para não parecer uma alteração.
formUsuario.addEventListener('formdata', (evento) => {
    if (campos.namedItem('perfil').disabled) {
        evento.formData.set('perfil', campos.namedItem('perfil').value);
    }
});

// ---------- Ações que só enviam o id ----------

async function executarAcao(linha, botao, { url, confirmacao, aoSucesso }) {
    if (!confirm(confirmacao)) {
        return;
    }

    botao.disabled = true;
    try {
        const dados = await enviarId(url, linha.dataset.id);
        if (dados.ok) {
            aoSucesso(dados);
        } else {
            mostrarMensagem(mensagemLista, dados.msg ?? 'Não foi possível concluir a operação.');
        }
    } catch {
        mostrarMensagem(mensagemLista, 'Falha de conexão com o servidor.');
    } finally {
        botao.disabled = false;
    }
}

listaUsuarios.addEventListener('click', (evento) => {
    const botao = evento.target.closest('[data-acao]');
    if (!botao) {
        return;
    }

    const linha = botao.closest('tr');
    const nome = linha.dataset.nome;

    if (botao.dataset.acao === 'editar') {
        abrirFormulario(linha);
    } else if (botao.dataset.acao === 'personificar') {
        executarAcao(linha, botao, {
            url: 'api/personificar.php',
            confirmacao: `Navegar como ${nome}? Você verá o sistema exatamente como esse usuário vê.`,
            aoSucesso: () => { window.location.href = 'index.php'; },
        });
    } else if (botao.dataset.acao === 'excluir') {
        executarAcao(linha, botao, {
            url: 'api/usuarios_excluir.php',
            confirmacao: `Excluir ${nome}? As tarefas e anexos desse usuário também serão excluídos.`,
            aoSucesso: (dados) => {
                linha.remove();
                mostrarMensagem(mensagemLista, dados.msg, 'success');
            },
        });
    }
});
