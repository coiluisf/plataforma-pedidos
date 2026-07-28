<?php
$order_number = $_GET['order'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Pedido Confirmado - Zife Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: #FBEEE3;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 10px;
        }
        .phone-wrapper {
            width: 402px;
            height: 874px;
            background: black;
            border-radius: 40px;
            border: 12px solid #333;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .phone-notch {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 28px;
            background: black;
            border-radius: 0 0 20px 20px;
            z-index: 10;
        }
        .phone-screen {
            width: 100%;
            height: 100%;
            background: #FBEEE3;
            overflow-y: auto;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
        }
        .confirmation-header {
            text-align: center;
            padding: 40px 16px 20px;
            background: white;
            border-bottom: 1px solid #E0D5CA;
        }
        .check-icon {
            font-size: 60px;
            margin-bottom: 16px;
            animation: bounce 0.6s ease-in-out;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        .confirmation-title {
            font-family: 'Bricolage Grotesque';
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #4B7F52;
        }
        .confirmation-subtitle {
            font-size: 12px;
            color: #7A6A5E;
        }
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }
        .order-info {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 12px;
            text-align: center;
        }
        .order-number {
            font-family: 'Bricolage Grotesque';
            font-size: 28px;
            font-weight: 700;
            color: #E8491D;
            margin-bottom: 8px;
        }
        .order-label {
            font-size: 11px;
            color: #7A6A5E;
            text-transform: uppercase;
            font-weight: 600;
        }
        .status-tracker {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .tracker-title {
            font-family: 'Bricolage Grotesque';
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .status-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .status-step {
            text-align: center;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            margin: 0 auto 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: #F5F1ED;
            border: 2px solid #E0D5CA;
        }
        .step-circle.active {
            background: #E8491D;
            color: white;
            border-color: #E8491D;
        }
        .step-circle.completed {
            background: #4B7F52;
            color: white;
            border-color: #4B7F52;
        }
        .step-name {
            font-size: 10px;
            color: #7A6A5E;
            font-weight: 600;
        }
        .details {
            background: white;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            font-size: 12px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #F5F1ED;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            color: #7A6A5E;
        }
        .detail-value {
            font-weight: 600;
            color: #1B1512;
        }
        .estimate-time {
            background: linear-gradient(135deg, #FFE0D5 0%, #FFF0E6 100%);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            text-align: center;
        }
        .estimate-label {
            font-size: 11px;
            color: #7A6A5E;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .estimate-time-value {
            font-family: 'Bricolage Grotesque';
            font-size: 20px;
            font-weight: 700;
            color: #E8491D;
        }
        .app-footer {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #E0D5CA;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #E8491D;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }
        .btn:hover {
            background: #C7380F;
        }
    </style>
</head>
<body>
    <div class="phone-wrapper">
        <div class="phone-notch"></div>
        <div class="phone-screen">
            <div class="confirmation-header">
                <div class="check-icon">✓</div>
                <div class="confirmation-title">Pedido Confirmado!</div>
                <div class="confirmation-subtitle">Seu pedido foi recebido com sucesso</div>
            </div>

            <div class="app-content">
                <div class="order-info">
                    <div class="order-label">Número do Pedido</div>
                    <div class="order-number" id="orderNumber"><?php echo htmlspecialchars($order_number ?: 'Carregando...'); ?></div>
                </div>

                <div class="estimate-time">
                    <div class="estimate-label">Tempo Estimado</div>
                    <div class="estimate-time-value">30 min</div>
                </div>

                <div class="status-tracker">
                    <div class="tracker-title">Status do Seu Pedido</div>
                    <div class="status-steps">
                        <div class="status-step">
                            <div class="step-circle active">✓</div>
                            <div class="step-name">Novo</div>
                        </div>
                        <div class="status-step">
                            <div class="step-circle">👨‍🍳</div>
                            <div class="step-name">Preparo</div>
                        </div>
                        <div class="status-step">
                            <div class="step-circle">⚡</div>
                            <div class="step-name">Pronto</div>
                        </div>
                        <div class="status-step">
                            <div class="step-circle">🚗</div>
                            <div class="step-name">Entregue</div>
                        </div>
                    </div>
                </div>

                <div class="details">
                    <div class="detail-row">
                        <span class="detail-label">Subtotal</span>
                        <span class="detail-value" id="subtotalVal">R$ 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Taxa de Entrega</span>
                        <span class="detail-value">R$ 5.00</span>
                    </div>
                    <div class="detail-row" style="border-bottom: 2px solid #E8491D; margin-bottom: 12px; padding-bottom: 12px;">
                        <span class="detail-label" style="font-weight: 700; color: #1B1512;">Total</span>
                        <span class="detail-value" style="color: #E8491D; font-size: 14px;" id="totalVal">R$ 0.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pagamento</span>
                        <span class="detail-value" id="paymentMethod">Pix</span>
                    </div>
                </div>
            </div>

            <div class="app-footer">
                <button class="btn" onclick="goToMenu()">Novo Pedido</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lastOrder = localStorage.getItem('lastOrder');
            if (lastOrder) {
                const order = JSON.parse(lastOrder);
                document.getElementById('subtotalVal').textContent = `R$ ${(order.total - 5).toFixed(2)}`;
                document.getElementById('totalVal').textContent = `R$ ${order.total.toFixed(2)}`;
                // Update payment method if available
            }

            // Simulate status updates
            setTimeout(() => updateStatus(1), 3000);
            setTimeout(() => updateStatus(2), 8000);
            setTimeout(() => updateStatus(3), 15000);
        });

        function updateStatus(stepIndex) {
            const steps = document.querySelectorAll('.step-circle');
            if (steps[stepIndex]) {
                steps[stepIndex].classList.add('active');
                if (stepIndex > 0) steps[stepIndex - 1].classList.add('completed');
            }
        }

        function goToMenu() {
            window.location.href = '/app/menu';
        }
    </script>
</body>
</html>
