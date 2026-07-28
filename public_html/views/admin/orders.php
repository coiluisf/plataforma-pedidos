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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Pedidos - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Pedidos</h1>
                    <div class="topbar-right">
                        <select id="statusFilter" onchange="filterOrders(this.value)" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                            <option value="">Todos os Status</option>
                            <option value="new">Novo</option>
                            <option value="preparing">Preparando</option>
                            <option value="ready">Pronto</option>
                            <option value="delivered">Entregue</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="admin-content">
                <div class="kanban-board" id="kanbanBoard">
                    <div class="kanban-column">
                        <h3>Novo</h3>
                        <div id="column-new" class="kanban-column-items"></div>
                    </div>
                    <div class="kanban-column">
                        <h3>Preparando</h3>
                        <div id="column-preparing" class="kanban-column-items"></div>
                    </div>
                    <div class="kanban-column">
                        <h3>Pronto</h3>
                        <div id="column-ready" class="kanban-column-items"></div>
                    </div>
                    <div class="kanban-column">
                        <h3>Entregue</h3>
                        <div id="column-delivered" class="kanban-column-items"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="/public/js/admin.js"></script>
    <script>
        let allOrders = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
            setInterval(loadOrders, 10000); // Refresh every 10 seconds
        });

        function loadOrders() {
            fetch('/api/orders')
                .then(response => response.json())
                .then(data => {
                    allOrders = data;
                    renderKanban();
                });
        }

        function renderKanban() {
            const statuses = ['new', 'preparing', 'ready', 'delivered'];

            statuses.forEach(status => {
                const orders = allOrders.filter(o => o.status === status);
                const container = document.getElementById(`column-${status}`);

                container.innerHTML = orders.map(order => `
                    <div class="kanban-card" onclick="showOrderDetails(${order.id})">
                        <div class="kanban-card-title">${order.order_number}</div>
                        <div class="kanban-card-meta">${order.customer_name}</div>
                        <div class="kanban-card-meta">R$ ${parseFloat(order.total).toFixed(2)}</div>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 8px; font-size: 12px;" onclick="moveOrder(event, ${order.id})">
                            ${getNextStatus(status)}
                        </button>
                    </div>
                `).join('');
            });
        }

        function moveOrder(e, orderId) {
            e.stopPropagation();
            const order = allOrders.find(o => o.id === orderId);
            const statusMap = {'new': 'preparing', 'preparing': 'ready', 'ready': 'delivered'};
            const newStatus = statusMap[order.status];

            if (newStatus) {
                fetch('/api/orders/update-status', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({order_id: orderId, status: newStatus})
                }).then(() => loadOrders());
            }
        }

        function getNextStatus(status) {
            const map = {'new': 'Iniciar Preparo', 'preparing': 'Marcar Pronto', 'ready': 'Entregue'};
            return map[status] || 'Pronto';
        }

        function showOrderDetails(orderId) {
            alert('Detalhes do pedido ' + orderId + ' - Implementar modal com itens do pedido');
        }

        function filterOrders(status) {
            if (!status) return renderKanban();
            const filtered = allOrders.filter(o => o.status === status);
            // Rerender only one column for filtering
        }
    </script>
</body>
</html>
