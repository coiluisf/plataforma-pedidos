<?php
require_once __DIR__ . '/../../config/constants.php';
if (isset($_SESSION['restaurant_id'])) {
    header('Location: /admin/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zife Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Zife Order</h1>
                <p>Painel do Restaurante</p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email do Restaurante</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    <span class="error-msg" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Sua senha segura" required>
                    <span class="error-msg" id="passwordError"></span>
                </div>

                <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                    <span class="btn-text">Entrar no Painel</span>
                    <span class="btn-loading">
                        <span class="spinner"></span> Conectando...
                    </span>
                </button>

                <span class="error-msg" id="generalError"></span>
            </form>

            <div class="auth-footer">
                <p>Novo por aqui? <a href="/auth/register">Crie uma conta grátis</a></p>
            </div>
        </div>

        <!-- Modal de erro -->
        <div id="errorModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="errorTitle">
            <div class="modal-content">
                <div class="modal-icon">⚠️</div>
                <h2 id="errorTitle">Erro ao Conectar</h2>
                <p id="errorMessage">Verifique suas credenciais e tente novamente</p>
                <button class="btn btn-primary" onclick="closeModal()">Tentar Novamente</button>
            </div>
        </div>
    </div>

    <script>
        let isProcessing = false;

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (isProcessing) return;

            isProcessing = true;
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            // Limpar erros anteriores
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('generalError').textContent = '';

            // Validação
            if (!email) {
                document.getElementById('emailError').textContent = 'Email é obrigatório';
                isProcessing = false;
                btn.disabled = false;
                return;
            }

            if (!password) {
                document.getElementById('passwordError').textContent = 'Senha é obrigatória';
                isProcessing = false;
                btn.disabled = false;
                return;
            }

            try {
                const response = await fetch('/api/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    document.querySelector('.btn-text').textContent = '✓ Sucesso!';
                    await new Promise(r => setTimeout(r, 800));
                    window.location.href = '/admin/dashboard';
                } else {
                    showErrorModal(data.error || 'Email ou senha inválidos');
                    isProcessing = false;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Login error:', error);
                showErrorModal('Erro de conexão. Tente novamente.');
                isProcessing = false;
                btn.disabled = false;
            }
        });

        function showErrorModal(msg) {
            document.getElementById('errorMessage').textContent = msg;
            document.getElementById('errorModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('errorModal').classList.remove('show');
        }

        // Fechar ao clicar fora
        document.getElementById('errorModal').addEventListener('click', (e) => {
            if (e.target.id === 'errorModal') closeModal();
        });
    </script>
</body>
</html>
