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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Registrar - Zife Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #FBEEE3 0%, #F5F1ED 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Bricolage Grotesque';
        }
        .logo h1 {
            font-size: 28px;
            color: #1B1512;
        }
        .logo p {
            color: #7A6A5E;
            font-size: 14px;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1B1512;
            font-size: 13px;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0D5CA;
            border-radius: 8px;
            font-family: 'Instrument Sans';
            font-size: 13px;
        }
        .form-input:focus {
            outline: none;
            border-color: #E8491D;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #E8491D;
            color: white;
            border: none;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn:hover { background: #C7380F; }
        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #7A6A5E;
        }
        .login-link a {
            color: #E8491D;
            text-decoration: none;
            font-weight: 600;
        }
        .error-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .error-modal.show {
            display: flex;
        }
        .error-modal-content {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .error-icon {
            width: 64px;
            height: 64px;
            background: #FDEAEA;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .error-modal-content h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            color: #D32F2F;
            font-size: 20px;
            margin-bottom: 8px;
        }
        .error-modal-content p {
            color: #7A6A5E;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .error-close-btn {
            width: 100%;
            padding: 12px;
            background: #E8491D;
            color: white;
            border: none;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .error-close-btn:hover {
            background: #C7380F;
        }
        .loading-state {
            display: none;
            align-items: center;
            gap: 8px;
        }
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn:disabled {
            background: #CCC;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <h1>Zife Order</h1>
            <p>Comece Grátis</p>
        </div>

        <form onsubmit="handleRegister(event)">
            <div class="form-group">
                <label class="form-label">Nome do Restaurante</label>
                <input type="text" id="name" class="form-input" placeholder="Seu Restaurante" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="email" class="form-input" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="tel" id="phone" class="form-input" placeholder="(11) 99999-9999">
            </div>

            <div class="form-group">
                <label class="form-label">Senha</label>
                <input type="password" id="password" class="form-input" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirmar Senha</label>
                <input type="password" id="password_confirm" class="form-input" placeholder="Confirme sua senha" required>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <span class="btn-text">Criar Conta Grátis</span>
                <span class="loading-state" id="loadingState">
                    <div class="loading-spinner"></div>
                    Criando conta...
                </span>
            </button>
        </form>

        <div class="login-link">
            Já tem conta? <a href="/auth/login">Faça login aqui</a>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="error-modal" id="errorModal">
        <div class="error-modal-content">
            <div class="error-icon">⚠️</div>
            <h2>Erro ao Registrar</h2>
            <p id="errorMessage">Verifique os dados e tente novamente</p>
            <button class="error-close-btn" onclick="closeErrorModal()">Tentar Novamente</button>
        </div>
    </div>

    <script>
        function showErrorModal(message) {
            const errorMessage = document.getElementById('errorMessage');
            const errorModal = document.getElementById('errorModal');
            errorMessage.textContent = message;
            errorModal.classList.add('show');
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.remove('show');
        }

        async function handleRegister(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const loadingState = document.getElementById('loadingState');

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            // Validação básica
            if (!name || !email || !password || !passwordConfirm) {
                showErrorModal('Por favor, preencha todos os campos obrigatórios');
                return;
            }

            if (password.length < 6) {
                showErrorModal('A senha deve ter no mínimo 6 caracteres');
                return;
            }

            if (password !== passwordConfirm) {
                showErrorModal('As senhas não conferem. Tente novamente');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            loadingState.style.display = 'flex';

            try {
                const response = await fetch('/api/auth.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        phone,
                        password,
                        password_confirm: passwordConfirm
                    })
                });

                if (!response.ok) {
                    throw new Error('Erro na conexão com o servidor');
                }

                const result = await response.json();

                if (result.success) {
                    // Sucesso! Redirecionar
                    submitBtn.textContent = '✓ Conta Criada!';
                    await new Promise(resolve => setTimeout(resolve, 800));
                    window.location.replace('/admin/dashboard');
                } else {
                    // Erro no registro
                    showErrorModal(result.error || 'Erro ao criar conta. Tente novamente.');
                    submitBtn.disabled = false;
                    btnText.style.display = 'block';
                    loadingState.style.display = 'none';
                }
            } catch (error) {
                console.error('Register error:', error);
                showErrorModal('Erro de conexão. Verifique sua internet e tente novamente.');
                submitBtn.disabled = false;
                btnText.style.display = 'block';
                loadingState.style.display = 'none';
            }
        }

        // Fechar modal ao clicar fora
        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeErrorModal();
            }
        });
    </script>
</body>
</html>
