<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$restaurant_id = $_GET['restaurant_id'] ?? null;
$payment_id = $_GET['payment_id'] ?? null;

if (!$restaurant_id || !$payment_id) {
    header('Location: /');
    exit;
}

// Atualizar transação como completa
$db = Database::getInstance();
$db->update(
    'UPDATE transactions SET status = ? WHERE restaurant_id = ? AND gateway_transaction_id = ?',
    ['completed', $restaurant_id, $payment_id]
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Confirmado - Zife Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <style>
        body {
            font-family: var(--font-body);
            background: var(--color-cream);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: var(--space-lg);
        }

        .success-container {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            text-align: center;
            max-width: 500px;
            box-shadow: var(--shadow-md);
        }

        .success-icon {
            font-size: 64px;
            margin-bottom: var(--space-lg);
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
        }

        h1 {
            color: var(--color-success);
            font-family: var(--font-title);
            margin: var(--space-lg) 0;
        }

        p {
            color: var(--color-secondary);
            line-height: 1.6;
            margin-bottom: var(--space-lg);
        }

        .btn {
            display: inline-block;
            padding: var(--space-md) var(--space-lg);
            background: var(--color-primary);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #C7380F;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Pagamento Confirmado!</h1>
        <p>Sua assinatura foi ativada com sucesso. Você pode acessar seu painel de controle agora.</p>
        <a href="/auth/login" class="btn">Acessar Painel Admin</a>
    </div>
</body>
</html>
