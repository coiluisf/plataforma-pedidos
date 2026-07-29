<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

$categories = $db->fetchAll(
    'SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY `order`',
    [$restaurant_id]
);
?>

<div class="menu-container">
    <div class="menu-header">
        <h2>Cardápio</h2>
        <button class="btn btn-primary" onclick="openAddItemModal()">+ Novo Item</button>
    </div>

    <?php if (count($categories) > 0): ?>
        <?php foreach ($categories as $category): ?>
            <?php
            $items = $db->fetchAll(
                'SELECT * FROM menu_items WHERE restaurant_id = ? AND category_id = ? ORDER BY name',
                [$restaurant_id, $category['id']]
            );
            ?>
            <div class="category-section">
                <div class="category-header">
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <span class="item-count"><?php echo count($items); ?> itens</span>
                </div>

                <div class="items-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p><?php echo htmlspecialchars(substr($item['description'], 0, 60)) . '...'; ?></p>
                                <div class="item-price">R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></div>
                            </div>
                            <div class="item-actions">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo $item['active'] ? 'checked' : ''; ?> onchange="toggleItem(<?php echo $item['id']; ?>)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <button class="btn-icon" onclick="editItem(<?php echo $item['id']; ?>)">✏️</button>
                                <button class="btn-icon btn-danger" onclick="deleteItem(<?php echo $item['id']; ?>)">🗑️</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; color: var(--color-secondary); padding: var(--space-2xl);">
            Nenhuma categoria criada. Adicione itens ao cardápio.
        </p>
    <?php endif; ?>
</div>

<style>
.menu-container {
    max-width: 1000px;
}

.menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-2xl);
}

.menu-header h2 {
    margin: 0;
}

.category-section {
    background-color: white;
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    margin-bottom: var(--space-xl);
    box-shadow: var(--shadow-sm);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-lg);
    padding-bottom: var(--space-lg);
    border-bottom: 1px solid var(--color-neutral-gray);
}

.category-header h3 {
    margin: 0;
}

.item-count {
    background-color: var(--color-cream);
    padding: 4px 12px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 600;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.menu-item {
    border: 1px solid var(--color-neutral-gray);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: box-shadow 0.3s;
}

.menu-item:hover {
    box-shadow: var(--shadow-md);
}

.menu-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.item-info {
    padding: var(--space-md);
}

.item-info h4 {
    margin: 0 0 var(--space-xs) 0;
    font-size: 14px;
}

.item-info p {
    margin: 0 0 var(--space-md) 0;
    font-size: 12px;
    color: var(--color-secondary);
}

.item-price {
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--space-md);
}

.item-actions {
    display: flex;
    gap: var(--space-md);
    padding-top: var(--space-md);
    border-top: 1px solid var(--color-neutral-gray);
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
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
    transform: translateX(16px);
}

.btn-icon {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 0;
}

.btn-danger {
    color: #D32F2F;
}
</style>

<script>
function toggleItem(itemId) {
    // Chamar API para ativar/desativar item
    fetch('/api/menu.php?action=toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId })
    }).then(r => r.json()).then(data => {
        if (!data.success) alert('Erro ao atualizar');
    });
}

function editItem(itemId) {
    alert('Editar item ' + itemId);
}

function deleteItem(itemId) {
    if (!confirm('Tem certeza?')) return;
    fetch('/api/menu.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: itemId })
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}

function openAddItemModal() {
    alert('Abrir modal para adicionar item');
}
</script>
