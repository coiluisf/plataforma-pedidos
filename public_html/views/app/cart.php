<?php
// Cart page for mobile app
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacola - Zife Order</title>
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
        .app-header {
            padding: 16px;
            background: white;
            border-bottom: 1px solid #E0D5CA;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .app-title {
            font-family: 'Bricolage Grotesque';
            font-size: 18px;
            font-weight: 700;
        }
        .app-header button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }
        .cart-item {
            background: white;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .item-qty {
            font-size: 12px;
            color: #7A6A5E;
        }
        .item-price {
            font-weight: 700;
            color: #E8491D;
        }
        .remove-btn {
            background: #FFE0D5;
            border: none;
            color: #E8491D;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .summary {
            background: white;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .summary-total {
            border-top: 1px solid #E0D5CA;
            padding-top: 8px;
            margin-top: 8px;
            font-weight: 700;
            font-size: 16px;
            color: #E8491D;
        }
        .app-footer {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #E0D5CA;
            display: flex;
            gap: 8px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-primary {
            background: #E8491D;
            color: white;
        }
        .btn-secondary {
            background: #F5F1ED;
            color: #4A3E36;
        }
        .empty-state {
            text-align: center;
            padding: 40px 16px;
            color: #7A6A5E;
        }
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="phone-wrapper">
        <div class="phone-notch"></div>
        <div class="phone-screen">
            <div class="app-header">
                <div class="app-title">🛒 Sacola</div>
                <button onclick="goToMenu()">✕</button>
            </div>

            <div class="app-content" id="cartContent"></div>

            <div class="app-footer">
                <button class="btn btn-secondary" onclick="goToMenu()">Voltar</button>
                <button class="btn btn-primary" id="checkoutBtn" onclick="goToCheckout()" style="display: none;">Checkout</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
        });

        function loadCart() {
            const saved = localStorage.getItem('cart');
            if (saved) cart = JSON.parse(saved);
            renderCart();
        }

        function renderCart() {
            if (cart.length === 0) {
                document.getElementById('cartContent').innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🛒</div>
                        <p>Sua sacola está vazia</p>
                    </div>
                `;
                document.getElementById('checkoutBtn').style.display = 'none';
                return;
            }

            const items = cart.map(item => `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-qty">${item.qty}x</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="item-price">R$ ${(item.price * item.qty).toFixed(2)}</div>
                        <button class="remove-btn" onclick="removeItem(${item.id})">Remover</button>
                    </div>
                </div>
            `).join('');

            const subtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            const delivery = 5.00;
            const total = subtotal + delivery;

            document.getElementById('cartContent').innerHTML = `
                ${items}
                <div class="summary" style="margin-top: 16px;">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>R$ ${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Taxa de entrega:</span>
                        <span>R$ ${delivery.toFixed(2)}</span>
                    </div>
                    <div class="summary-total">
                        <span>Total: R$ ${total.toFixed(2)}</span>
                    </div>
                </div>
            `;
            document.getElementById('checkoutBtn').style.display = 'flex';
        }

        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        function goToMenu() {
            window.location.href = '/app/menu';
        }

        function goToCheckout() {
            window.location.href = '/app/checkout';
        }
    </script>
</body>
</html>
