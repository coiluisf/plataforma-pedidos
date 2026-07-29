<?php
require_once __DIR__ . '/../config/constants.php';

// Se já logado, redireciona para admin
if (isset($_SESSION['restaurant_id'])) {
    header('Location: /admin/dashboard');
    exit;
}

// Pegar plano da query string
$plan = $_GET['plan'] ?? 'starter';
$valid_plans = ['free', 'starter', 'pro'];

if (!in_array($plan, $valid_plans)) {
    header('Location: /');
    exit;
}

$plans_info = PLANS;
$current_plan = $plans_info[$plan];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Zife Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <style>
        body {
            font-family: var(--font-body);
            background: var(--color-cream);
            margin: 0;
            padding: var(--space-lg);
        }

        .checkout-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .checkout-header {
            background: var(--color-dark);
            color: white;
            padding: var(--space-xl);
            text-align: center;
        }

        .checkout-header h1 {
            margin: 0;
            font-family: var(--font-title);
            font-size: 28px;
        }

        .checkout-content {
            padding: var(--space-xl);
        }

        .plan-summary {
            background: var(--color-cream);
            padding: var(--space-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-lg);
        }

        .plan-name {
            font-family: var(--font-title);
            font-size: 20px;
            margin: 0 0 var(--space-md) 0;
            color: var(--color-dark);
        }

        .plan-price {
            font-size: 36px;
            font-weight: 700;
            color: var(--color-primary);
            margin: 0;
        }

        .plan-price span {
            font-size: 14px;
            color: var(--color-secondary);
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: var(--space-lg) 0 0 0;
        }

        .plan-features li {
            padding: var(--space-sm) 0;
            color: var(--color-dark);
            font-size: 14px;
        }

        .plan-features li:before {
            content: "✓ ";
            color: var(--color-success);
            font-weight: 700;
            margin-right: var(--space-sm);
        }

        .form-group {
            margin-bottom: var(--space-lg);
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: var(--space-sm);
            color: var(--color-dark);
        }

        .form-input {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid #E0D5CA;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(232, 73, 29, 0.1);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .payment-method {
            padding: var(--space-md);
            border: 2px solid #E0D5CA;
            border-radius: var(--radius-md);
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
        }

        .payment-method.active {
            border-color: var(--color-primary);
            background: rgba(232, 73, 29, 0.05);
        }

        .payment-method input {
            display: none;
        }

        .btn-checkout {
            width: 100%;
            padding: var(--space-md);
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-checkout:hover {
            background: #C7380F;
        }

        .btn-checkout:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: var(--space-lg);
            color: var(--color-secondary);
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            color: var(--color-dark);
        }

        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: var(--space-md);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-lg);
            display: none;
        }

        .loading {
            display: none;
            text-align: center;
            padding: var(--space-lg);
        }

        .spinner {
            border: 3px solid #E0D5CA;
            border-top-color: var(--color-primary);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto var(--space-md);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Finalizar Compra</h1>
        </div>

        <div class="checkout-content">
            <div class="error-message" id="errorMessage"></div>

            <div class="plan-summary">
                <h2 class="plan-name"><?php echo $current_plan['name']; ?></h2>
                <p class="plan-price">
                    R$ <?php echo number_format($current_plan['price'], 2, ',', '.'); ?><span>/mês</span>
                </p>
                <ul class="plan-features">
                    <?php foreach ($current_plan['features'] as $feature): ?>
                        <li><?php echo $feature; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form id="checkoutForm">
                <div class="form-group">
                    <label class="form-label">Nome do Restaurante</label>
                    <input type="text" name="restaurant_name" class="form-input" placeholder="Seu Restaurante" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Senha</label>
                    <input type="password" name="password_confirm" class="form-input" placeholder="••••••" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Método de Pagamento</label>
                    <div class="payment-methods">
                        <label class="payment-method active">
                            <input type="radio" name="payment_method" value="pix" checked>
                            <div>🔑 Pix</div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div>💳 Cartão</div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-checkout" id="submitBtn">Ir para Pagamento</button>
            </form>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Processando seu pedido...</p>
            </div>

            <a href="/" class="btn-back">← Voltar aos Planos</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('checkoutForm');
        const errorDiv = document.getElementById('errorMessage');
        const loading = document.getElementById('loading');
        const submitBtn = document.getElementById('submitBtn');
        const plan = '<?php echo $plan; ?>';

        // Atualizar classe active ao selecionar método de pagamento
        document.querySelectorAll('.payment-method input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
                this.closest('.payment-method').classList.add('active');
            });
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (form.password.value !== form.password_confirm.value) {
                showError('As senhas não conferem');
                return;
            }

            submitBtn.disabled = true;
            loading.style.display = 'block';
            errorDiv.style.display = 'none';

            try {
                // 1. Criar conta
                const registerResponse = await fetch('/api/auth', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'register',
                        name: form.restaurant_name.value,
                        email: form.email.value,
                        password: form.password.value,
                        plan: plan
                    })
                });

                const registerData = await registerResponse.json();

                if (!registerData.success) {
                    showError(registerData.error || 'Erro ao criar conta');
                    submitBtn.disabled = false;
                    loading.style.display = 'none';
                    return;
                }

                // 2. Se for plano grátis, apenas redireciona
                if (plan === 'free') {
                    window.location.href = '/auth/login';
                    return;
                }

                // 3. Criar preferência de pagamento no Mercado Pago
                const checkoutResponse = await fetch('/api/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        restaurant_id: registerData.restaurant_id,
                        plan: plan,
                        payment_method: form.payment_method.value,
                        restaurant_email: form.email.value
                    })
                });

                const checkoutData = await checkoutResponse.json();

                if (!checkoutData.success) {
                    showError(checkoutData.error || 'Erro ao processar pagamento');
                    submitBtn.disabled = false;
                    loading.style.display = 'none';
                    return;
                }

                // 4. Redirecionar para Mercado Pago
                if (checkoutData.payment_url) {
                    window.location.href = checkoutData.payment_url;
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Erro ao processar pedido. Tente novamente.');
                submitBtn.disabled = false;
                loading.style.display = 'none';
            }
        });

        function showError(message) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
