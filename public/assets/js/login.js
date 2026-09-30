configurarFormulario(document.getElementById('form-login'), {
    url: 'api/login.php',
    aoSucesso: () => {
        window.location.href = 'index.php';
    },
});
