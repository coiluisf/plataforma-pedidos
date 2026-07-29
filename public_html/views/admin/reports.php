<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

// Stats últimos 7 dias
$seven_days_ago = date('Y-m-d', strtotime('-7 days'));
$stats = $db->fetch(
    'SELECT COUNT(*) as total_orders, SUM(total) as total_revenue FROM orders
     WHERE restaurant_id = ? AND DATE(created_at) >= ?',
    [$restaurant_id, $seven_days_ago]
);

// Faturamento por dia
$daily_revenue = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $revenue = $db->fetch(
        'SELECT SUM(total) as total FROM orders WHERE restaurant_id = ? AND DATE(created_at) = ?',
        [$restaurant_id, $day]
    );
    $daily_revenue[date('d/m', strtotime($day))] = floatval($revenue['total'] ?? 0);
}

// Top 10 itens mais vendidos
$top_items = $db->fetchAll(
    'SELECT m.name, m.price, SUM(oi.quantity) as total_qty, SUM(oi.quantity * oi.unit_price) as revenue
     FROM order_items oi
     JOIN menu_items m ON oi.menu_item_id = m.id
     JOIN orders o ON oi.order_id = o.id
     WHERE o.restaurant_id = ? AND DATE(o.created_at) >= ?
     GROUP BY oi.menu_item_id
     ORDER BY total_qty DESC
     LIMIT 10',
    [$restaurant_id, $seven_days_ago]
);
?>

<div class="reports-container">
    <div class="reports-header">
        <h2>Relatórios</h2>
        <p>Últimos 7 dias</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Pedidos (7d)</div>
            <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Faturamento (7d)</div>
            <div class="stat-value">R$ <?php echo number_format($stats['total_revenue'] ?? 0, 2, ',', '.'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ticket Médio (7d)</div>
            <div class="stat-value">
                R$ <?php
                $count = $stats['total_orders'] ?? 0;
                $revenue = $stats['total_revenue'] ?? 0;
                echo number_format($count > 0 ? $revenue / $count : 0, 2, ',', '.');
                ?>
            </div>
        </div>
    </div>

    <!-- Gráfico de Faturamento -->
    <div class="chart-section">
        <h3>Faturamento por Dia (7d)</h3>
        <canvas id="revenueChart"></canvas>
    </div>

    <!-- Top Itens -->
    <div class="top-items-section">
        <h3>Top 10 Itens Vendidos</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd Vendida</th>
                    <th>Receita</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td style="text-align: center;"><?php echo $item['total_qty']; ?></td>
                        <td>R$ <?php echo number_format($item['revenue'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.reports-container { max-width: 1000px; }

.reports-header {
    margin-bottom: var(--space-2xl);
}

.reports-header h2 {
    margin: 0 0 var(--space-md) 0;
}

.reports-header p {
    margin: 0;
    color: var(--color-secondary);
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
    margin-bottom: var(--space-2xl);
}

.stat-card {
    background-color: white;
    padding: var(--space-lg);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.stat-label {
    font-size: 12px;
    color: var(--color-secondary);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: var(--space-md);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--color-primary);
}

.chart-section {
    background-color: white;
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: var(--space-2xl);
}

.chart-section h3 {
    margin-top: 0;
}

.top-items-section {
    background-color: white;
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.top-items-section h3 {
    margin-top: 0;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th {
    background-color: var(--color-cream);
    padding: var(--space-md);
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--color-neutral-gray);
}

.items-table td {
    padding: var(--space-md);
    border-bottom: 1px solid var(--color-neutral-gray);
}

.items-table tr:hover {
    background-color: #F9F7F5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const data = <?php echo json_encode($daily_revenue); ?>;
    const days = Object.keys(data);
    const revenue = Object.values(data);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Faturamento (R$)',
                data: revenue,
                backgroundColor: '#E8491D',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
