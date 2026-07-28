<?php
if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-header"><h2>Zife Order</h2></div>
            <nav class="sidebar-nav">
                <a href="/admin/dashboard" class="nav-item"><span class="icon">📊</span><span>Visão Geral</span></a>
                <a href="/admin/orders" class="nav-item"><span class="icon">📋</span><span>Pedidos</span></a>
                <a href="/admin/menu" class="nav-item active"><span class="icon">🍽️</span><span>Cardápio</span></a>
                <a href="/admin/payments" class="nav-item"><span class="icon">💳</span><span>Pagamentos</span></a>
                <a href="/admin/tables" class="nav-item"><span class="icon">🪑</span><span>Mesas</span></a>
                <a href="/admin/reports" class="nav-item"><span class="icon">📈</span><span>Relatórios</span></a>
                <a href="/admin/settings" class="nav-item"><span class="icon">⚙️</span><span>Configurações</span></a>
            </nav>
            <div class="sidebar-footer"><a href="/auth/logout" class="btn-logout">Sair</a></div>
        </aside>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Cardápio</h1>
                    <button class="btn btn-primary" onclick="openAddItemModal()">+ Adicionar Item</button>
                </div>
            </div>

            <div class="admin-content">
                <div id="categoriesList" style="display: grid; grid-template-columns: 1fr; gap: 20px;"></div>
            </div>

            <div id="addItemModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Adicionar Item ao Cardápio</h2>
                        <button class="modal-close" onclick="document.getElementById('addItemModal').classList.remove('active')">&times;</button>
                    </div>
                    <form onsubmit="submitAddItem(event)">
                        <div class="form-group">
                            <label class="form-label">Categoria</label>
                            <select id="categorySelect" class="form-select" required></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nome do Item</label>
                            <input type="text" id="itemName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <textarea id="itemDescription" class="form-textarea"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Preço (R$)</label>
                            <input type="number" id="itemPrice" class="form-input" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Adicionar</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="/public/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadMenu();
        });

        function loadMenu() {
            fetch(`/api/menu/restaurant/<?php echo $_SESSION['restaurant_id']; ?>`)
                .then(response => response.json())
                .then(categories => {
                    renderCategories(categories);
                    populateCategorySelect(categories);
                });
        }

        function renderCategories(categories) {
            const html = categories.map(cat => `
                <div class="table-wrapper">
                    <div style="padding: 16px; border-bottom: 1px solid #eee;">
                        <h3 style="font-family: 'Bricolage Grotesque'; font-weight: 700;">${cat.name}</h3>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${cat.items.map(item => `
                                <tr>
                                    <td><strong>${item.name}</strong></td>
                                    <td>${item.description || '-'}</td>
                                    <td>R$ ${parseFloat(item.price).toFixed(2)}</td>
                                    <td>
                                        <button class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;" onclick="toggleItemAvailability(${item.id}, ${item.available ? 0 : 1})">
                                            ${item.available ? 'Disponível' : 'Indisponível'}
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;" onclick="deleteItem(${item.id})">Deletar</button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `).join('');

            document.getElementById('categoriesList').innerHTML = html;
        }

        function populateCategorySelect(categories) {
            const select = document.getElementById('categorySelect');
            select.innerHTML = categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('');
        }

        function openAddItemModal() {
            document.getElementById('addItemModal').classList.add('active');
        }

        function submitAddItem(e) {
            e.preventDefault();
            const data = {
                category_id: document.getElementById('categorySelect').value,
                name: document.getElementById('itemName').value,
                description: document.getElementById('itemDescription').value,
                price: document.getElementById('itemPrice').value
            };

            fetch('/api/menu/add-item', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            }).then(() => {
                document.getElementById('addItemModal').classList.remove('active');
                loadMenu();
            });
        }

        function toggleItemAvailability(itemId, available) {
            fetch(`/api/menu/item/${itemId}`, {
                method: 'PUT',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({available: available ? true : false})
            }).then(() => loadMenu());
        }

        function deleteItem(itemId) {
            if (confirm('Tem certeza que deseja deletar este item?')) {
                fetch(`/api/menu/item/${itemId}`, {method: 'DELETE'})
                    .then(() => loadMenu());
            }
        }
    </script>
</body>
</html>
