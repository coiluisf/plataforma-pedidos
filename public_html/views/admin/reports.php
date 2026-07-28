<?php
if (!isset($_SESSION['restaurant_id'])) header('Location: /auth/login');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Relatórios - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Relatórios</h1>
                </div>
            </div>

            <div class="admin-content">
                <div class="dashboard-grid">
                    <div class="chart-card">
                        <h3>Vendas - Últimos 7 Dias</h3>
                        <canvas id="salesChart"></canvas>
                    </div>
                    <div class="chart-card">
                        <h3>Top Itens Vendidos</h3>
                        <div id="topItemsList" style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                                <span>Item</span>
                                <span>Vendas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadReportStats();
        });

        function loadReportStats() {
            fetch('/api/admin/stats')
                .then(response => response.json())
                .then(data => {
                    renderSalesChart(data.daily_stats);
                    renderTopItems(data.top_items);
                });
        }

        function renderSalesChart(data) {
            const ctx = document.getElementById('salesChart')?.getContext('2d');
            if (!ctx) return;

            const labels = data.map(d => new Date(d.date).toLocaleDateString('pt-BR'));
            const revenues = data.map(d => parseFloat(d.revenue));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: revenues,
                        borderColor: '#E8491D',
                        backgroundColor: 'rgba(232, 73, 29, 0.1)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {display: false}
                    },
                    scales: {
                        y: {beginAtZero: true}
                    }
                }
            });
        }

        function renderTopItems(items) {
            const html = items.map((item, i) => `
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                    <span>${i + 1}. ${item.name}</span>
                    <strong>${item.total_sold} vendas</strong>
                </div>
            `).join('');

            document.getElementById('topItemsList').innerHTML += html;
        }
    </script>
</body>
</html>
