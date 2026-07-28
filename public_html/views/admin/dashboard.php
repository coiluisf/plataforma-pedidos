<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /auth/login');
    exit;
}

$db = Database::getInstance();
$restaurant = $db->fetch('SELECT * FROM restaurants WHERE id = ?', [$_SESSION['restaurant_id']]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Visão Geral</h1>
                    <div class="topbar-right">
                        <span class="restaurant-name"><?php echo htmlspecialchars($restaurant['name']); ?></span>
                        <span class="plan-badge plan-<?php echo $restaurant['plan']; ?>"><?php echo PLANS[$restaurant['plan']]['name']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="admin-content">
                <!-- Stat Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon orders-icon"></div>
                        <div class="stat-details">
                            <p class="stat-label">Pedidos Hoje</p>
                            <p class="stat-value" id="todayOrders">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon revenue-icon"></div>
                        <div class="stat-details">
                            <p class="stat-label">Faturamento Hoje</p>
                            <p class="stat-value" id="todayRevenue">R$ 0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon calendar-icon"></div>
                        <div class="stat-details">
                            <p class="stat-label">Pedidos Mês</p>
                            <p class="stat-value" id="monthOrders">0</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon chart-icon"></div>
                        <div class="stat-details">
                            <p class="stat-label">Faturamento Mês</p>
                            <p class="stat-value" id="monthRevenue">R$ 0</p>
                        </div>
                    </div>
                </div>

                <!-- Chart & Recent Orders -->
                <div class="dashboard-grid">
                    <div class="chart-card">
                        <h3>Pedidos por Hora</h3>
                        <canvas id="hourlyChart"></canvas>
                    </div>
                    <div class="recent-orders">
                        <h3>Pedidos Recentes</h3>
                        <div id="recentOrdersList" class="orders-list">
                            <p style="color: #7A6A5E;">Carregando...</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/public/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            setInterval(loadDashboardData, 30000); // Refresh every 30 seconds
        });

        function loadDashboardData() {
            fetch('/api/admin/dashboard')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('todayOrders').textContent = data.stats.today_orders;
                    document.getElementById('todayRevenue').textContent = 'R$ ' + data.stats.today_revenue.toFixed(2);
                    document.getElementById('monthOrders').textContent = data.stats.month_orders;
                    document.getElementById('monthRevenue').textContent = 'R$ ' + data.stats.month_revenue.toFixed(2);

                    // Render chart
                    renderHourlyChart(data.hourly_data);

                    // Render recent orders
                    renderRecentOrders(data.recent_orders);
                });
        }

        function renderHourlyChart(data) {
            const ctx = document.getElementById('hourlyChart')?.getContext('2d');
            if (!ctx) return;

            if (window.hourlyChart) {
                window.hourlyChart.destroy();
            }

            const labels = data.map(d => d.hour + ':00');
            const values = data.map(d => d.count);

            window.hourlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pedidos',
                        data: values,
                        backgroundColor: '#E8491D',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function renderRecentOrders(orders) {
            const html = orders.map(order => `
                <div class="order-item">
                    <div class="order-info">
                        <p class="order-number">${order.order_number}</p>
                        <p class="customer-name">${order.customer_name}</p>
                    </div>
                    <div class="order-status">
                        <span class="status status-${order.status}">${getStatusLabel(order.status)}</span>
                        <p class="order-total">R$ ${parseFloat(order.total).toFixed(2)}</p>
                    </div>
                </div>
            `).join('');

            document.getElementById('recentOrdersList').innerHTML = html || '<p style="color: #7A6A5E;">Nenhum pedido ainda</p>';
        }

        function getStatusLabel(status) {
            const labels = {
                'new': 'Novo',
                'preparing': 'Preparando',
                'ready': 'Pronto',
                'delivered': 'Entregue',
                'cancelled': 'Cancelado'
            };
            return labels[status] || status;
        }
    </script>
</body>
</html>
