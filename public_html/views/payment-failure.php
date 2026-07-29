<?php
require_once __DIR__ . '/../config/constants.php';

$restaurant_id = $_GET['restaurant_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Cancelado - Zife Order</title>
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

        .failure-container {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            text-align: center;
            max-width: 500px;
            box-shadow: var(--shadow-md);
        }

        .failure-icon {
            font-size: 64px;
            margin-bottom: var(--space-lg);
        }

        h1 {
            color: #C7380F;
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
            margin-right: var(--space-md);
        }

        .btn:hover {
            background: #C7380F;
        }

        .btn-secondary {
            background: #E0D5CA;
            color: var(--color-dark);
        }

        .btn-secondary:hover {
            background: #D0C5BA;
        }
    </style>
</head>
<body>
    <div class="failure-container">
        <div class="failure-icon">✕</div>
        <h1>Pagamento Cancelado</h1>
        <p>O pagamento foi cancelado ou não foi concluído. Sua conta foi criada mas ainda não está ativa.</p>
        <p>Você pode tentar novamente ou usar o plano grátis.</p>
        <div>
            <a href="/" class="btn">Tentar Novamente</a>
            <a href="/auth/login" class="btn btn-secondary">Acessar Plano Grátis</a>
        </div>
    </div>
</body>
</html>
