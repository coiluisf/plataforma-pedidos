<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

$methods = $db->fetchAll(
    'SELECT * FROM payment_methods WHERE restaurant_id = ?',
    [$restaurant_id]
);

$transactions = $db->fetchAll(
    'SELECT t.*, o.order_number FROM transactions t
     JOIN orders o ON t.order_id = o.id
     WHERE o.restaurant_id = ?
     ORDER BY t.created_at DESC LIMIT 20',
    [$restaurant_id]
);
?>

<div class="payments-container">
    <div class="payments-header">
        <h2>Pagamentos</h2>
    </div>

    <!-- Formas de Pagamento -->
    <div class="payment-methods-section">
        <h3>Formas de Pagamento Habilitadas</h3>
        <div class="methods-grid">
            <?php foreach ($methods as $method): ?>
                <div class="method-card">
                    <div class="method-icon">
                        <?php echo $method['method'] === 'pix' ? '🔑' : '💳'; ?>
                    </div>
                    <h4><?php echo ucfirst($method['method']); ?></h4>
                    <label class="toggle-switch">
                        <input type="checkbox" <?php echo $method['enabled'] ? 'checked' : ''; ?> 
                               onchange="togglePaymentMethod('<?php echo $method['method']; ?>', this.checked)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Transações Recentes -->
    <div class="transactions-section">
        <h3>Transações Recentes</h3>
        <div class="transactions-table">
            <table>
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Valor</th>
                        <th>Método</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td>#<?php echo substr($t['order_number'], -6); ?></td>
                            <td>R$ <?php echo number_format($t['amount'], 2, ',', '.'); ?></td>
                            <td><?php echo ucfirst($t['payment_method']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $t['status']; ?>">
                                    <?php echo ucfirst($t['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m H:i', strtotime($t['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.payments-container { max-width: 1000px; }

.payments-header {
    margin-bottom: var(--space-2xl);
}

.payments-header h2 {
    margin: 0;
}

.payment-methods-section {
    background-color: white;
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    margin-bottom: var(--space-xl);
    box-shadow: var(--shadow-sm);
}

.payment-methods-section h3 {
    margin-top: 0;
}

.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--space-lg);
}

.method-card {
    border: 1px solid var(--color-neutral-gray);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
    text-align: center;
}

.method-icon {
    font-size: 32px;
    margin-bottom: var(--space-md);
}

.method-card h4 {
    margin: 0 0 var(--space-md) 0;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
}

.toggle-switch input { opacity: 0; width: 0; height: 0; }

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.3s;
    border-radius: 28px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: var(--color-success);
}

input:checked + .toggle-slider:before {
    transform: translateX(22px);
}

.transactions-section {
    background-color: white;
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    box-shadow: var(--shadow-sm);
}

.transactions-section h3 {
    margin-top: 0;
}

.transactions-table {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: var(--color-cream);
    padding: var(--space-md);
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--color-neutral-gray);
}

td {
    padding: var(--space-md);
    border-bottom: 1px solid var(--color-neutral-gray);
}

tr:hover {
    background-color: #F9F7F5;
}

.badge-completed { background-color: var(--color-success); color: white; }
.badge-pending { background-color: var(--color-warning); color: white; }
.badge-failed { background-color: #D32F2F; color: white; }
</style>

<script>
async function togglePaymentMethod(method, enabled) {
    try {
        const response = await fetch('/api/admin.php?action=toggle-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ method, enabled })
        });
        const data = await response.json();
        if (!data.success) alert('Erro ao atualizar');
    } catch (e) {
        alert('Erro de conexão');
    }
}
</script>
