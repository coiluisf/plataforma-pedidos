<?php
$restaurant_id = $_GET['restaurant'] ?? 1;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Cardápio - Zife Order</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .app-title {
            font-family: 'Bricolage Grotesque';
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .categories-pills {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
            scroll-behavior: smooth;
        }
        .pill {
            padding: 8px 16px;
            border-radius: 9999px;
            background: #F5F1ED;
            border: none;
            color: #4A3E36;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        .pill.active {
            background: #E8491D;
            color: white;
        }
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }
        .menu-item {
            background: white;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 80px 1fr 60px;
            gap: 12px;
            align-items: center;
        }
        .menu-item-img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background: #E0D5CA;
            object-fit: cover;
        }
        .menu-item-info {
            flex: 1;
        }
        .menu-item-name {
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .menu-item-desc {
            font-size: 11px;
            color: #7A6A5E;
            margin-bottom: 4px;
        }
        .menu-item-price {
            font-weight: 700;
            color: #E8491D;
            font-size: 12px;
        }
        .stepper {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .stepper button {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1px solid #E0D5CA;
            background: white;
            cursor: pointer;
            font-weight: 700;
            color: #E8491D;
        }
        .stepper-value {
            font-size: 12px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }
        .app-footer {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #E0D5CA;
            display: flex;
            gap: 8px;
        }
        .btn-cart {
            flex: 1;
            padding: 12px;
            background: #E8491D;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }
        .cart-badge {
            display: inline-block;
            background: #4B7F52;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            margin-left: 4px;
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            font-size: 14px;
            color: #7A6A5E;
        }
        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #E0D5CA;
            border-top-color: #E8491D;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .error-message {
            background: #FDEAEA;
            border: 1px solid #EFCCCF;
            border-radius: 8px;
            padding: 12px;
            margin: 16px;
            color: #D32F2F;
            font-size: 13px;
            text-align: center;
        }
        .empty-state {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 300px;
            color: #7A6A5E;
            text-align: center;
        }
        .empty-state-text {
            font-size: 14px;
            margin-top: 12px;
        }
        @media (max-width: 500px) {
            .phone-wrapper {
                width: 100%;
                height: auto;
                border: none;
                border-radius: 0;
            }
            .phone-notch { display: none; }
        }
    </style>
</head>
<body>
    <div class="phone-wrapper">
        <div class="phone-notch"></div>
        <div class="phone-screen">
            <div class="app-header">
                <div class="app-title">Cardápio</div>
                <div class="categories-pills" id="categoriesPills"></div>
            </div>

            <div class="app-content" id="menuContent">
                <div class="loading">
                    <div class="loading-spinner"></div>
                    Carregando cardápio...
                </div>
            </div>

            <div class="app-footer">
                <button class="btn-cart" onclick="goToCart()">
                    Ver Sacola <span class="cart-badge" id="cartCount">0</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        const restaurantId = <?php echo $restaurant_id; ?>;

        document.addEventListener('DOMContentLoaded', function() {
            loadMenu();
        });

        function loadMenu() {
            fetch(`/api/menu/restaurant/${restaurantId}`)
                .then(r => {
                    if (!r.ok) throw new Error('Erro ao carregar cardápio');
                    return r.json();
                })
                .then(categories => {
                    if (!categories || categories.length === 0) {
                        showEmptyState();
                    } else {
                        renderCategories(categories);
                        loadCartFromStorage();
                    }
                })
                .catch(error => {
                    console.error('Menu load error:', error);
                    showError('Não foi possível carregar o cardápio. Tente novamente.');
                });
        }

        function renderCategories(categories) {
            const pills = categories.map((cat, i) => `
                <button class="pill ${i === 0 ? 'active' : ''}" onclick="filterCategory(${cat.id}, this)">
                    ${cat.name}
                </button>
            `).join('');
            document.getElementById('categoriesPills').innerHTML = pills;

            const menu = categories[0]?.items || [];
            if (menu.length === 0) {
                showEmptyState();
            } else {
                renderMenuItems(menu);
            }
        }

        function filterCategory(catId, btn) {
            document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');

            fetch(`/api/menu/${catId}`)
                .then(r => r.json())
                .then(items => renderMenuItems(items));
        }

        function renderMenuItems(items) {
            const html = items.map(item => `
                <div class="menu-item">
                    <img src="${item.image_url || 'https://via.placeholder.com/80'}" alt="${item.name}" class="menu-item-img">
                    <div class="menu-item-info">
                        <div class="menu-item-name">${item.name}</div>
                        <div class="menu-item-desc">${item.description || ''}</div>
                        <div class="menu-item-price">R$ ${parseFloat(item.price).toFixed(2)}</div>
                    </div>
                    <div class="stepper">
                        <button onclick="decreaseQty(${item.id})">−</button>
                        <span class="stepper-value" id="qty-${item.id}">0</span>
                        <button onclick="increaseQty(${item.id}, '${item.name}', ${item.price})">+</button>
                    </div>
                </div>
            `).join('');
            document.getElementById('menuContent').innerHTML = html;
        }

        function increaseQty(id, name, price) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.qty++;
            } else {
                cart.push({id, name, price, qty: 1});
            }
            updateQtyDisplay(id);
            saveCartToStorage();
        }

        function decreaseQty(id) {
            const item = cart.find(i => i.id === id);
            if (item && item.qty > 0) {
                item.qty--;
                if (item.qty === 0) {
                    cart = cart.filter(i => i.id !== id);
                }
            }
            updateQtyDisplay(id);
            saveCartToStorage();
        }

        function updateQtyDisplay(id) {
            const item = cart.find(i => i.id === id);
            document.getElementById(`qty-${id}`).textContent = item?.qty || 0;
            document.getElementById('cartCount').textContent = cart.reduce((sum, i) => sum + i.qty, 0);
        }

        function saveCartToStorage() {
            localStorage.setItem('cart', JSON.stringify(cart));
            localStorage.setItem('restaurantId', restaurantId);
        }

        function loadCartFromStorage() {
            const saved = localStorage.getItem('cart');
            if (saved) cart = JSON.parse(saved);
            document.getElementById('cartCount').textContent = cart.reduce((sum, i) => sum + i.qty, 0);
        }

        function goToCart() {
            window.location.href = '/app/cart';
        }

        function showError(message) {
            document.getElementById('menuContent').innerHTML = `
                <div class="error-message">${message}</div>
            `;
        }

        function showEmptyState() {
            document.getElementById('menuContent').innerHTML = `
                <div class="empty-state">
                    <div style="font-size: 32px;">∘</div>
                    <div class="empty-state-text">Nenhum item disponível<br>no cardápio</div>
                </div>
            `;
        }
    </script>
</body>
</html>
