document.addEventListener('DOMContentLoaded', function () {
    const novaSenhaInput = document.getElementById('novaSenha');
    const confirmarSenhaInput = document.getElementById('confirmarSenha');
    const mensagemMesmaSenha = document.getElementById('mensagemMesmaSenha');
    const mensagemSenhaCurta = document.getElementById('senhaCurta');
    const mensagemNovaSenhaIgual = document.getElementById('senhasIguais');
    const mensagemNovaSenhaValida = document.getElementById('novaSenhaValida');
    const btnAtualizarDados = document.getElementById('btnAtualizarDados');

    function verificarSenhas() {
        const novaSenha = novaSenhaInput.value;
        const confirmarSenha = confirmarSenhaInput.value;

        let senhaValida = false;
        let senhasIguais = false;

        // Verifica comprimento da nova senha
        if (novaSenha && novaSenha.length < 8) {
            mensagemSenhaCurta.style.display = 'block';
            mensagemNovaSenhaValida.style.display = 'none';
        } else if (novaSenha) {
            mensagemSenhaCurta.style.display = 'none';
            mensagemNovaSenhaValida.style.display = 'block';
            senhaValida = true;
        } else {
            mensagemSenhaCurta.style.display = 'none';
            mensagemNovaSenhaValida.style.display = 'none';
        }

        // Verifica se as senhas coincidem apenas se ambos os campos tiverem valores
        if (novaSenha && confirmarSenha) {
            if (confirmarSenha !== novaSenha) {
                mensagemMesmaSenha.style.display = 'block';
                mensagemNovaSenhaIgual.style.display = 'none';
            } else {
                mensagemMesmaSenha.style.display = 'none';
                mensagemNovaSenhaIgual.style.display = 'block';
                senhasIguais = true;
            }
        } else {
            mensagemMesmaSenha.style.display = 'none';
            mensagemNovaSenhaIgual.style.display = 'none';
        }

        // Ativa o botão apenas se tudo estiver válido
        btnAtualizarDados.disabled = !(senhaValida && senhasIguais);
    }

    novaSenhaInput.addEventListener('input', verificarSenhas);
    confirmarSenhaInput.addEventListener('input', verificarSenhas);

    // Garante que o botão esteja desativado ao carregar
    document.getElementById('btnAtualizarDados').disabled = true;
});
