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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zife Order</title>
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
        .login-container {
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
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1B1512;
        }
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #E0D5CA;
            border-radius: 8px;
            font-family: 'Instrument Sans';
            font-size: 14px;
        }
        .form-input:focus {
            outline: none;
            border-color: #E8491D;
            box-shadow: 0 0 0 3px rgba(232, 73, 29, 0.1);
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
            font-family: 'Instrument Sans';
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #C7380F;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7A6A5E;
        }
        .register-link a {
            color: #E8491D;
            text-decoration: none;
            font-weight: 600;
        }
        .error {
            color: #D32F2F;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Zife Order</h1>
            <p>Painel do Restaurante</p>
        </div>

        <form onsubmit="handleLogin(event)">
            <div class="form-group">
                <label class="form-label">Email do Restaurante</label>
                <input type="email" id="email" class="form-input" placeholder="seu@email.com" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Senha</label>
                <input type="password" id="password" class="form-input" placeholder="Sua senha" required>
                <div class="error" id="passwordError"></div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Entrar no Painel</button>
        </form>

        <div class="register-link">
            Novo? <a href="/auth/register">Crie uma conta grátis</a>
        </div>
    </div>

    <script>
        async function handleLogin(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Conectando...';

            try {
                const response = await fetch('/api/auth.php?action=login', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({email, password})
                });

                const data = await response.json();

                if (data.success) {
                    submitBtn.textContent = 'Sucesso!';
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
                    }, 500);
                } else {
                    alert(data.error || 'Erro ao fazer login');
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
