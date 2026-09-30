configurarFormulario(document.getElementById('form-dados'), {
    url: 'api/conta.php',
    aoSucesso: (dados, form, mensagem) => {
        document.querySelectorAll('[data-nome-usuario]').forEach((el) => { el.textContent = dados.nome; });
        mostrarMensagem(mensagem, dados.msg, 'success');
    },
});

configurarFormulario(document.getElementById('form-senha'), {
    url: 'api/senha.php',
    validar: (form) => {
        if (form.nova_senha.value !== form.confirmar_senha.value) {
            return 'A confirmação não confere com a nova senha.';
        }
    },
    aoSucesso: (dados, form, mensagem) => {
        form.reset();
        mostrarMensagem(mensagem, dados.msg, 'success');
    },
});
