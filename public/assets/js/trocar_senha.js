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
