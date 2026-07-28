<?php
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['restaurant_id'])) header('Location: /auth/login');

$db = Database::getInstance();
$restaurant = $db->fetch('SELECT * FROM restaurants WHERE id = ?', [$_SESSION['restaurant_id']]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <?php require __DIR__ . '/_sidebar.php'; ?>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Configurações da Loja</h1>
                </div>
            </div>

            <div class="admin-content">
                <div style="max-width: 600px;">
                    <form onsubmit="saveSettings(event)">
                        <div class="form-group">
                            <label class="form-label">Nome do Restaurante</label>
                            <input type="text" class="form-input" id="restName" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Telefone</label>
                            <input type="tel" class="form-input" id="restPhone" value="<?php echo htmlspecialchars($restaurant['phone'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Endereço</label>
                            <input type="text" class="form-input" id="restAddress" value="<?php echo htmlspecialchars($restaurant['address'] ?? ''); ?>">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-input" id="restCity" value="<?php echo htmlspecialchars($restaurant['city'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-input" id="restState" maxlength="2" value="<?php echo htmlspecialchars($restaurant['state'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-input" id="restZip" value="<?php echo htmlspecialchars($restaurant['zip_code'] ?? ''); ?>">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Horário Abertura</label>
                                <input type="time" class="form-input" id="restOpen" value="<?php echo htmlspecialchars($restaurant['business_hours_open'] ?? '09:00'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Horário Fechamento</label>
                                <input type="time" class="form-input" id="restClose" value="<?php echo htmlspecialchars($restaurant['business_hours_close'] ?? '22:00'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-textarea" id="restDescription"><?php echo htmlspecialchars($restaurant['description'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Salvar Configurações</button>
                    </form>

                    <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #eee;">
                        <h3 style="font-family: 'Bricolage Grotesque'; margin-bottom: 12px;">Plano Atual</h3>
                        <p style="font-size: 18px; font-weight: 700; color: #E8491D;">
                            <?php echo PLANS[$restaurant['plan']]['name']; ?> - R$ <?php echo PLANS[$restaurant['plan']]['price']; ?>/mês
                        </p>
                        <p style="color: #7A6A5E; margin-top: 8px;">
                            Limite de pedidos: <?php echo PLANS[$restaurant['plan']]['monthly_orders_limit']; ?>/mês
                        </p>
                        <a href="#" class="btn btn-secondary" style="margin-top: 12px;">Alterar Plano</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function saveSettings(e) {
            e.preventDefault();

            const data = {
                name: document.getElementById('restName').value,
                phone: document.getElementById('restPhone').value,
                address: document.getElementById('restAddress').value,
                city: document.getElementById('restCity').value,
                state: document.getElementById('restState').value,
                zip_code: document.getElementById('restZip').value,
                business_hours_open: document.getElementById('restOpen').value,
                business_hours_close: document.getElementById('restClose').value,
                description: document.getElementById('restDescription').value
            };

            fetch('/api/admin/settings', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Configurações salvas com sucesso!');
                } else {
                    alert('Erro ao salvar: ' + result.error);
                }
            });
        }
    </script>
</body>
</html>
