<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

// Buscar pedidos agrupados por status
$statuses = ['pending', 'preparing', 'ready', 'completed'];
$orders_by_status = [];

foreach ($statuses as $status) {
    $orders_by_status[$status] = $db->fetchAll(
        'SELECT id, order_number, customer_name, total, created_at FROM orders
         WHERE restaurant_id = ? AND status = ?
         ORDER BY created_at DESC LIMIT 20',
        [$restaurant_id, $status]
    );
}

$status_labels = [
    'pending' => 'Novo',
    'preparing' => 'Em preparo',
    'ready' => 'Pronto',
    'completed' => 'Entregue'
];

$status_colors = [
    'pending' => '#E8491D',
    'preparing' => '#C98A1D',
    'ready' => '#4B7F52',
    'completed' => '#7A6A5E'
];
?>

<div class="kanban-container">
    <div class="kanban-board">
        <?php foreach ($statuses as $status): ?>
            <div class="kanban-column">
                <div class="column-header" style="border-left: 4px solid <?php echo $status_colors[$status]; ?>">
                    <span class="status-dot" style="background-color: <?php echo $status_colors[$status]; ?>"></span>
                    <h3><?php echo $status_labels[$status]; ?></h3>
                    <span class="order-count"><?php echo count($orders_by_status[$status]); ?></span>
                </div>

                <div class="column-content">
                    <?php foreach ($orders_by_status[$status] as $order): ?>
                        <div class="order-card">
                            <div class="order-card-header">
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                                <span class="order-number">#<?php echo substr($order['order_number'], -6); ?></span>
                            </div>
                            <div class="order-card-body">
                                <p class="order-time">
                                    <?php echo date('d/m H:i', strtotime($order['created_at'])); ?>
                                </p>
                                <p class="order-total">
                                    R$ <?php echo number_format($order['total'], 2, ',', '.'); ?>
                                </p>
                            </div>
                            <?php if ($status !== 'completed'): ?>
                                <button class="btn-advance" onclick="advanceOrder(<?php echo $order['id']; ?>, '<?php echo $status; ?>')">
                                    <?php
                                    $next_labels = [
                                        'pending' => 'Aceitar Pedido',
                                        'preparing' => 'Marcar Pronto',
                                        'ready' => 'Marcar Entregue'
                                    ];
                                    echo $next_labels[$status] ?? 'Avançar';
                                    ?>
                                </button>
                            <?php else: ?>
                                <p class="order-completed">✓ Finalizado</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (count($orders_by_status[$status]) === 0): ?>
                        <p class="empty-message">Nenhum pedido</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.kanban-container {
    overflow-x: auto;
    padding-bottom: var(--space-lg);
}

.kanban-board {
    display: grid;
    grid-template-columns: repeat(4, minmax(300px, 1fr));
    gap: var(--space-lg);
    min-width: 100%;
}

.kanban-column {
    background-color: #F5F1ED;
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 200px);
}

.column-header {
    padding: var(--space-lg);
    background-color: white;
    border-bottom: 1px solid var(--color-neutral-gray);
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.column-header h3 {
    flex: 1;
    margin: 0;
    font-size: 16px;
}

.order-count {
    background-color: var(--color-cream);
    padding: 4px 12px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 600;
    color: var(--color-dark);
}

.column-content {
    flex: 1;
    overflow-y: auto;
    padding: var(--space-lg);
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.order-card {
    background-color: white;
    border-radius: var(--radius-md);
    padding: var(--space-md);
    box-shadow: var(--shadow-sm);
    cursor: grab;
    transition: box-shadow 0.3s;
}

.order-card:hover {
    box-shadow: var(--shadow-md);
}

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-sm);
}

.order-card-header strong {
    font-size: 14px;
    color: var(--color-dark);
}

.order-number {
    font-size: 12px;
    color: var(--color-secondary);
    font-weight: 600;
}

.order-card-body p {
    margin: var(--space-xs) 0;
    font-size: 13px;
}

.order-time {
    color: var(--color-secondary);
}

.order-total {
    color: var(--color-primary);
    font-weight: 600;
    font-size: 14px;
}

.btn-advance {
    width: 100%;
    padding: var(--space-sm);
    background-color: var(--color-primary);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: var(--space-md);
}

.btn-advance:hover {
    background-color: var(--color-primary-hover);
}

.order-completed {
    text-align: center;
    color: var(--color-success);
    font-weight: 600;
    font-size: 14px;
    margin-top: var(--space-md);
}

.empty-message {
    text-align: center;
    color: var(--color-secondary);
    padding: var(--space-lg);
    font-size: 13px;
}

@media (max-width: 1024px) {
    .kanban-board {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .kanban-board {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
async function advanceOrder(orderId, currentStatus) {
    const statusMap = {
        'pending': 'preparing',
        'preparing': 'ready',
        'ready': 'completed'
    };

    const nextStatus = statusMap[currentStatus];
    if (!nextStatus) return;

    try {
        const response = await fetch('/api/orders/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                order_id: orderId,
                status: nextStatus
            })
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao atualizar pedido');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Erro de conexão');
    }
}
</script>
