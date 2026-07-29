<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

$tables = $db->fetchAll(
    'SELECT * FROM restaurant_tables WHERE restaurant_id = ? ORDER BY table_number',
    [$restaurant_id]
);
?>

<div class="tables-container">
    <div class="tables-header">
        <h2>Mesas</h2>
        <button class="btn btn-primary" onclick="openAddTableModal()">+ Nova Mesa</button>
    </div>

    <div class="tables-grid">
        <?php foreach ($tables as $table): ?>
            <div class="table-card table-<?php echo $table['status']; ?>">
                <div class="table-number">Mesa <?php echo $table['table_number']; ?></div>
                <div class="table-seats">👥 <?php echo $table['seats']; ?> lugares</div>
                <div class="table-status">
                    <?php
                    $status_text = [
                        'available' => '✓ Disponível',
                        'occupied' => '⏳ Ocupada',
                        'waiting_payment' => '💳 Aguardando Pagamento'
                    ];
                    echo $status_text[$table['status']] ?? '';
                    ?>
                </div>
                <div class="table-actions">
                    <button class="btn-icon" onclick="showQRCode('<?php echo $table['qr_code']; ?>')">
                        📱 QR Code
                    </button>
                    <button class="btn-icon" onclick="editTable(<?php echo $table['id']; ?>)">
                        ✏️
                    </button>
                    <button class="btn-icon btn-danger" onclick="deleteTable(<?php echo $table['id']; ?>)">
                        🗑️
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($tables) === 0): ?>
        <p style="text-align: center; color: var(--color-secondary); padding: var(--space-2xl);">
            Nenhuma mesa configurada. Crie mesas para iniciar.
        </p>
    <?php endif; ?>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="modal" style="display: none;">
    <div class="modal-content">
        <button class="modal-close" onclick="closeQRCode()">✕</button>
        <h3>QR Code da Mesa</h3>
        <div id="qrContainer"></div>
        <p id="qrUrl" style="word-break: break-all; color: var(--color-secondary); font-size: 12px;"></p>
    </div>
</div>

<style>
.tables-container { max-width: 1000px; }

.tables-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-2xl);
}

.tables-header h2 { margin: 0; }

.tables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.table-card {
    background-color: white;
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    box-shadow: var(--shadow-sm);
    border-left: 4px solid;
    text-align: center;
}

.table-available { border-left-color: var(--color-success); }
.table-occupied { border-left-color: var(--color-warning); }
.table-waiting_payment { border-left-color: var(--color-primary); }

.table-number {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-dark);
    margin-bottom: var(--space-md);
}

.table-seats {
    font-size: 14px;
    color: var(--color-secondary);
    margin-bottom: var(--space-md);
}

.table-status {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: var(--space-lg);
}

.table-actions {
    display: flex;
    gap: var(--space-md);
    justify-content: center;
}

.btn-icon {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
}

.btn-danger { color: #D32F2F; }

.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    max-width: 400px;
    position: relative;
}

.modal-close {
    position: absolute;
    top: var(--space-md);
    right: var(--space-md);
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

#qrContainer {
    margin: var(--space-lg) 0;
    display: flex;
    justify-content: center;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function showQRCode(qrCode) {
    const url = window.location.origin + '/app?table=' + qrCode;
    document.getElementById('qrContainer').innerHTML = '';
    new QRCode(document.getElementById('qrContainer'), {
        text: url,
        width: 200,
        height: 200
    });
    document.getElementById('qrUrl').textContent = 'URL: ' + url;
    document.getElementById('qrModal').style.display = 'flex';
}

function closeQRCode() {
    document.getElementById('qrModal').style.display = 'none';
}

function editTable(tableId) {
    alert('Editar mesa ' + tableId);
}

function deleteTable(tableId) {
    if (!confirm('Excluir esta mesa?')) return;
    fetch('/api/admin.php?action=delete-table', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ table_id: tableId })
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}

function openAddTableModal() {
    alert('Abrir modal para adicionar mesa');
}
</script>
