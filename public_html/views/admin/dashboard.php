<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];
$today = date('Y-m-d');

// Stat 1: Pedidos hoje
$orders_today = $db->fetch(
    'SELECT COUNT(*) as count, SUM(total) as total FROM orders WHERE restaurant_id = ? AND DATE(created_at) = ?',
    [$restaurant_id, $today]
);
$orders_count = $orders_today['count'] ?? 0;
$faturamento_hoje = $orders_today['total'] ?? 0;

// Stat 2: Ticket médio
$ticket_medio = $orders_count > 0 ? $faturamento_hoje / $orders_count : 0;

// Stat 3: Pedidos pendentes
$pending = $db->fetch(
    'SELECT COUNT(*) as count FROM orders WHERE restaurant_id = ? AND status != ?',
    [$restaurant_id, 'completed']
);
$pending_count = $pending['count'] ?? 0;

// Pedidos por hora (últimas 6 horas)
$orders_by_hour = [];
for ($i = 5; $i >= 0; $i--) {
    $hour = date('Y-m-d H:00:00', strtotime("-$i hours"));
    $count = $db->fetch(
        'SELECT COUNT(*) as count FROM orders WHERE restaurant_id = ? AND created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 HOUR)',
        [$restaurant_id, $hour, $hour]
    );
    $orders_by_hour[date('H', strtotime($hour))] = $count['count'] ?? 0;
}

// Pedidos recentes (últimos 5)
$recent_orders = $db->fetchAll(
    'SELECT id, order_number, customer_name, order_type, status, created_at FROM orders WHERE restaurant_id = ? ORDER BY created_at DESC LIMIT 5',
    [$restaurant_id]
);
?>

<div class="dashboard-grid">
    <!-- STAT CARDS -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Pedidos Hoje</div>
            <div class="stat-value"><?php echo $orders_count; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Faturamento Hoje</div>
            <div class="stat-value">R$ <?php echo number_format($faturamento_hoje, 2, ',', '.'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ticket Médio</div>
            <div class="stat-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></div>
        </div>
        <div class="stat-card stat-card-highlight">
            <div class="stat-label">Pendentes</div>
            <div class="stat-value"><?php echo $pending_count; ?></div>
        </div>
    </div>

    <!-- GRÁFICO -->
    <div class="chart-section">
        <h3>Pedidos por Hora</h3>
        <canvas id="ordersChart"></canvas>
    </div>

    <!-- PEDIDOS RECENTES -->
    <div class="recent-orders-section">
        <h3>Pedidos Recentes</h3>
        <div class="orders-list">
            <?php if (count($recent_orders) > 0): ?>
                <?php foreach ($recent_orders as $order): ?>
                    <div class="order-item">
                        <div class="order-info">
                            <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                            <small><?php echo ucfirst($order['order_type']); ?> • <?php echo date('H:i', strtotime($order['created_at'])); ?></small>
                        </div>
                        <div class="order-status">
                            <span class="badge badge-<?php echo str_replace(' ', '-', strtolower($order['status'])); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--color-secondary); padding: var(--space-lg);">Nenhum pedido ainda</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Chart.js - Pedidos por hora
    const ctx = document.getElementById('ordersChart').getContext('2d');
    const data = <?php echo json_encode($orders_by_hour); ?>;
    const hours = Object.keys(data);
    const counts = Object.values(data);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hours.map(h => h + 'h'),
            datasets: [{
                label: 'Pedidos',
                data: counts,
                backgroundColor: '#E8491D',
                borderRadius: 8,
                borderSkipped: false
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
</script>
