<?php
if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamentos - Zife Order Admin</title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-header"><h2>Zife Order</h2></div>
            <nav class="sidebar-nav">
                <a href="/admin/dashboard" class="nav-item"><span class="icon">📊</span><span>Visão Geral</span></a>
                <a href="/admin/orders" class="nav-item"><span class="icon">📋</span><span>Pedidos</span></a>
                <a href="/admin/menu" class="nav-item"><span class="icon">🍽️</span><span>Cardápio</span></a>
                <a href="/admin/payments" class="nav-item active"><span class="icon">💳</span><span>Pagamentos</span></a>
                <a href="/admin/tables" class="nav-item"><span class="icon">🪑</span><span>Mesas</span></a>
                <a href="/admin/reports" class="nav-item"><span class="icon">📈</span><span>Relatórios</span></a>
                <a href="/admin/settings" class="nav-item"><span class="icon">⚙️</span><span>Configurações</span></a>
            </nav>
            <div class="sidebar-footer"><a href="/auth/logout" class="btn-logout">Sair</a></div>
        </aside>

        <main class="admin-main">
            <div class="topbar">
                <div class="topbar-content">
                    <h1>Métodos de Pagamento</h1>
                </div>
            </div>

            <div class="admin-content">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="table-wrapper" style="padding: 24px;">
                        <h3 style="font-family: 'Bricolage Grotesque'; margin-bottom: 20px;">Pix</h3>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div style="display: flex; gap: 10px;">
                                <button class="btn btn-primary" id="pixToggle" onclick="togglePaymentMethod('pix')">Habilitar</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chave Pix (Email, CPF ou Telefone)</label>
                            <input type="text" class="form-input" id="pixKey" placeholder="Digite sua chave Pix">
                            <button class="btn btn-primary" style="margin-top: 8px; width: 100%;" onclick="savePixKey()">Salvar</button>
                        </div>
                    </div>

                    <div class="table-wrapper" style="padding: 24px;">
                        <h3 style="font-family: 'Bricolage Grotesque'; margin-bottom: 20px;">Cartão de Crédito</h3>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div style="display: flex; gap: 10px;">
                                <button class="btn btn-primary" id="cardToggle" onclick="togglePaymentMethod('credit_card')">Desabilitar</button>
                            </div>
                        </div>
                        <p style="color: #999; font-size: 12px;">Integrado com Mercado Pago automaticamente.</p>
                    </div>
                </div>

                <div class="table-wrapper" style="margin-top: 30px;">
                    <div style="padding: 16px; border-bottom: 1px solid #eee;">
                        <h3 style="font-family: 'Bricolage Grotesque'; font-weight: 700;">Transações Recentes</h3>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Pedido</th>
                                <th>Valor</th>
                                <th>Método</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsList">
                            <tr><td colspan="5">Carregando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="/public/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPaymentMethods();
            loadTransactions();
        });

        function loadPaymentMethods() {
            fetch('/api/payments/methods')
                .then(response => response.json())
                .then(methods => {
                    document.getElementById('pixToggle').textContent = methods.pix ? 'Desabilitar' : 'Habilitar';
                    document.getElementById('cardToggle').textContent = methods.credit_card ? 'Desabilitar' : 'Habilitar';
                });
        }

        function togglePaymentMethod(method) {
            const isEnabled = document.getElementById(method === 'pix' ? 'pixToggle' : 'cardToggle').textContent === 'Desabilitar';

            fetch('/api/admin/payment-method', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({method: method, enabled: !isEnabled})
            }).then(() => loadPaymentMethods());
        }

        function savePixKey() {
            const key = document.getElementById('pixKey').value;
            if (key) {
                alert('Chave Pix salva: ' + key);
            }
        }

        function loadTransactions() {
            // Fetch last transactions
            fetch('/api/orders')
                .then(response => response.json())
                .then(orders => {
                    const html = orders.slice(0, 10).map(order => `
                        <tr>
                            <td>${new Date(order.created_at).toLocaleDateString('pt-BR')}</td>
                            <td>${order.order_number}</td>
                            <td>R$ ${parseFloat(order.total).toFixed(2)}</td>
                            <td>${order.payment_method || 'N/A'}</td>
                            <td><span class="status status-${order.payment_status}">${order.payment_status === 'completed' ? 'Concluído' : 'Pendente'}</span></td>
                        </tr>
                    `).join('');
                    document.getElementById('transactionsList').innerHTML = html || '<tr><td colspan="5">Nenhuma transação</td></tr>';
                });
        }
    </script>
</body>
</html>
