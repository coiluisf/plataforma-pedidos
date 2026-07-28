<?php
session_start();
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

            <button type="submit" class="btn" id="submitBtn">Criar Conta Grátis</button>
        </form>

        <div class="login-link">
            Já tem conta? <a href="/auth/login">Faça login aqui</a>
        </div>
    </div>

    <script>
        async function handleRegister(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;

            const data = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                password: document.getElementById('password').value,
                password_confirm: document.getElementById('password_confirm').value
            };

            submitBtn.disabled = true;
            submitBtn.textContent = 'Criando conta...';

            try {
                const response = await fetch('/api/auth.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    submitBtn.textContent = 'Sucesso!';
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
                    }, 500);
                } else {
                    alert(result.error || 'Erro ao registrar conta');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                alert('Erro de conexão. Tente novamente.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    </script>
</body>
</html>
