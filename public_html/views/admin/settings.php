<?php
$db = Database::getInstance();
$restaurant_id = $_SESSION['restaurant_id'];

// Fetch restaurant settings
$restaurant = $db->fetch(
    'SELECT id, name, address, phone, business_hours_open, business_hours_close, description FROM restaurants WHERE id = ?',
    [$restaurant_id]
);
?>

<div class="settings-container">
    <div class="settings-card">
        <h2>Configurações do Restaurante</h2>
        
        <form id="settingsForm" class="settings-form">
            <div class="form-group">
                <label for="name">Nome do Restaurante</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo htmlspecialchars($restaurant['name']); ?>" 
                    required
                    placeholder="Ex: Pizzaria Delícia"
                >
            </div>

            <div class="form-group">
                <label for="address">Endereço</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    value="<?php echo htmlspecialchars($restaurant['address'] ?? ''); ?>" 
                    placeholder="Ex: Rua das Flores, 123, Centro"
                >
            </div>

            <div class="form-group">
                <label for="phone">Telefone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="<?php echo htmlspecialchars($restaurant['phone'] ?? ''); ?>" 
                    placeholder="Ex: (11) 99999-9999"
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="business_hours_open">Horário de Abertura</label>
                    <input
                        type="time"
                        id="business_hours_open"
                        name="business_hours_open"
                        value="<?php echo htmlspecialchars($restaurant['business_hours_open'] ?? '10:00'); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="business_hours_close">Horário de Fechamento</label>
                    <input
                        type="time"
                        id="business_hours_close"
                        name="business_hours_close"
                        value="<?php echo htmlspecialchars($restaurant['business_hours_close'] ?? '22:00'); ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="description">Descrição / Sobre</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Conte um pouco sobre o seu restaurante..."
                    rows="5"
                ><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Configurações</button>
                <button type="reset" class="btn-secondary">Cancelar</button>
            </div>
        </form>

        <div id="successMessage" class="success-message" style="display: none;">
            ✓ Configurações salvas com sucesso!
        </div>
        <div id="errorMessage" class="error-message" style="display: none;"></div>
    </div>
</div>

<style>
.settings-container {
    max-width: 600px;
    margin: 0 auto;
}

.settings-card {
    background-color: white;
    padding: var(--space-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.settings-card h2 {
    margin-top: 0;
    margin-bottom: var(--space-lg);
    color: var(--color-dark);
    font-size: 24px;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.form-group label {
    font-weight: 600;
    color: var(--color-dark);
    font-size: 14px;
}

.form-group input,
.form-group textarea {
    padding: var(--space-md);
    border: 1px solid var(--color-neutral-gray);
    border-radius: var(--radius-md);
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--color-dark);
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(232, 73, 29, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-lg);
}

.form-actions {
    display: flex;
    gap: var(--space-md);
    margin-top: var(--space-lg);
}

.btn-primary,
.btn-secondary {
    padding: var(--space-md) var(--space-lg);
    border: none;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    flex: 1;
}

.btn-primary {
    background-color: var(--color-primary);
    color: white;
}

.btn-primary:hover {
    background-color: var(--color-primary-hover);
}

.btn-secondary {
    background-color: var(--color-neutral-gray);
    color: var(--color-dark);
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}

.success-message,
.error-message {
    padding: var(--space-md);
    border-radius: var(--radius-md);
    font-weight: 600;
    margin-top: var(--space-lg);
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .settings-card {
        padding: var(--space-lg);
    }
}
</style>

<script>
document.getElementById('settingsForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = {
        name: document.getElementById('name').value,
        address: document.getElementById('address').value,
        phone: document.getElementById('phone').value,
        business_hours_open: document.getElementById('business_hours_open').value,
        business_hours_close: document.getElementById('business_hours_close').value,
        description: document.getElementById('description').value
    };

    try {
        const response = await fetch('/api/admin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: 'update-restaurant',
                ...formData
            })
        });

        const data = await response.json();
        
        if (data.success) {
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none';
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'none';
            }, 3000);
        } else {
            showError(data.message || 'Erro ao salvar configurações');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Erro de conexão ao salvar');
    }
});

function showError(message) {
    const errorElement = document.getElementById('errorMessage');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
    document.getElementById('successMessage').style.display = 'none';
}
</script>
