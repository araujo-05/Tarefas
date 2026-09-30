configurarFormulario(document.getElementById('form-cadastro'), {
    url: 'api/cadastro.php',
    aoSucesso: () => {
        window.location.href = 'login.php?cadastrado=1';
    },
});
