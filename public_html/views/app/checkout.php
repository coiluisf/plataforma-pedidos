<?php
// Checkout page
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Zife Order</title>
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
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }
        .section {
            background: white;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Bricolage Grotesque';
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 13px;
        }
        .form-group {
            margin-bottom: 12px;
        }
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #4A3E36;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #E0D5CA;
            border-radius: 6px;
            font-family: 'Instrument Sans';
            font-size: 12px;
        }
        .radio-group {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }
        .radio-option {
            flex: 1;
        }
        .radio-option input {
            display: none;
        }
        .radio-option label {
            display: block;
            padding: 8px;
            border: 2px solid #E0D5CA;
            border-radius: 8px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .radio-option input:checked + label {
            border-color: #E8491D;
            background: #FFE0D5;
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
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .summary-total {
            border-top: 1px solid #E0D5CA;
            padding-top: 8px;
            margin-top: 8px;
            font-weight: 700;
            font-size: 14px;
            color: #E8491D;
        }
    </style>
</head>
<body>
    <div class="phone-wrapper">
        <div class="phone-notch"></div>
        <div class="phone-screen">
            <div class="app-header">
                <div class="app-title">✓ Checkout</div>
                <button onclick="goToCart()" style="background: none; border: none; font-size: 20px; cursor: pointer;">✕</button>
            </div>

            <div class="app-content">
                <form onsubmit="submitOrder(event)">
                    <!-- Customer Info -->
                    <div class="section">
                        <div class="section-title">Seus Dados</div>
                        <div class="form-group">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-input" id="customerName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telefone</label>
                            <input type="tel" class="form-input" id="customerPhone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" id="customerEmail" required>
                        </div>
                    </div>

                    <!-- Order Type -->
                    <div class="section">
                        <div class="section-title">Tipo de Entrega</div>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="orderType" id="delivery" value="delivery" checked onchange="toggleDelivery(true)">
                                <label for="delivery">🚗 Delivery</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="orderType" id="table" value="table" onchange="toggleDelivery(false)">
                                <label for="table">🪑 Mesa</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="orderType" id="counter" value="counter" onchange="toggleDelivery(false)">
                                <label for="counter">🏪 Balcão</label>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address (shown only for delivery) -->
                    <div class="section" id="deliverySection">
                        <div class="section-title">Endereço de Entrega</div>
                        <div class="form-group">
                            <label class="form-label">Endereço</label>
                            <input type="text" class="form-input" id="deliveryAddress" required>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="section">
                        <div class="section-title">Forma de Pagamento</div>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="paymentMethod" id="pix" value="pix" checked>
                                <label for="pix">🔑 Pix</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="paymentMethod" id="card" value="credit_card">
                                <label for="card">💳 Cartão</label>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="section" id="summarySection">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">R$ 0.00</span>
                        </div>
                        <div class="summary-row" id="deliveryRow">
                            <span>Entrega:</span>
                            <span>R$ 5.00</span>
                        </div>
                        <div class="summary-total">
                            Total: <span id="total">R$ 5.00</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 12px;">Finalizar Pedido</button>
                </form>
            </div>

            <div class="app-footer">
                <button class="btn btn-secondary" onclick="goToCart()">← Voltar</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let restaurantId = localStorage.getItem('restaurantId') || 1;

        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('cart');
            if (saved) cart = JSON.parse(saved);
            updateSummary();
        });

        function toggleDelivery(isDelivery) {
            document.getElementById('deliverySection').style.display = isDelivery ? 'block' : 'none';
            document.getElementById('deliveryAddress').required = isDelivery;
            document.getElementById('deliveryRow').style.display = isDelivery ? 'flex' : 'none';
            updateSummary();
        }

        function updateSummary() {
            const subtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            const delivery = document.querySelector('input[name="orderType"]:checked').value === 'delivery' ? 5 : 0;
            const total = subtotal + delivery;

            document.getElementById('subtotal').textContent = `R$ ${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `R$ ${total.toFixed(2)}`;
        }

        function submitOrder(e) {
            e.preventDefault();

            const orderData = {
                restaurant_id: restaurantId,
                customer_name: document.getElementById('customerName').value,
                customer_phone: document.getElementById('customerPhone').value,
                customer_email: document.getElementById('customerEmail').value,
                order_type: document.querySelector('input[name="orderType"]:checked').value,
                delivery_address: document.querySelector('input[name="orderType"]:checked').value === 'delivery'
                    ? document.getElementById('deliveryAddress').value
                    : null,
                payment_method: document.querySelector('input[name="paymentMethod"]:checked').value,
                items: cart
            };

            fetch('/api/orders/create', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(orderData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('lastOrder', JSON.stringify(data));
                    localStorage.removeItem('cart');
                    window.location.href = `/app/confirmation?order=${data.order_number}`;
                } else {
                    alert(data.error || 'Erro ao criar pedido');
                }
            });
        }

        function goToCart() {
            window.location.href = '/app/cart';
        }
    </script>
</body>
</html>
