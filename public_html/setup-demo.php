<?php
require_once __DIR__ . '/config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'setup_demo') {
    try {
        $db = new Database();
        $pdo = $db->getConnection();

        // Create demo restaurant
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO restaurants (id, name, email, password, phone, address, city, state, description, active)
            VALUES (1, 'Zife Demo', 'demo@zife.local', ?, '(11) 99999-0000', 'Rua Demo, 123', 'São Paulo', 'SP', 'Restaurante de demonstração', TRUE)
        ");
        $stmt->execute([password_hash('demo123', PASSWORD_BCRYPT)]);

        // Clear existing data
        $pdo->prepare("DELETE FROM menu_items WHERE restaurant_id = 1")->execute();
        $pdo->prepare("DELETE FROM categories WHERE restaurant_id = 1")->execute();

        // Categories
        $categories = ['Entradas' => 0, 'Pratos Principais' => 1, 'Bebidas' => 2, 'Sobremesas' => 3];
        $categoryIds = [];

        foreach ($categories as $name => $order) {
            $stmt = $pdo->prepare("INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, ?, ?)");
            $stmt->execute([$name, $order]);
            $categoryIds[$name] = $pdo->lastInsertId();
        }

        // Menu items
        $items = [
            'Entradas' => [
                ['name' => 'Bruschetta Italiana', 'price' => 24.90, 'desc' => 'Pão tostado com tomate, alho e manjericão fresco'],
                ['name' => 'Camarones al Ajillo', 'price' => 32.90, 'desc' => 'Camarões suculentos salteados no alho e azeite'],
                ['name' => 'Tábua de Queijos', 'price' => 38.90, 'desc' => 'Seleção de queijos artesanais e complementos']
            ],
            'Pratos Principais' => [
                ['name' => 'Burger Premium', 'price' => 45.90, 'desc' => 'Carne 180g, queijo cheddar, bacon e tomate'],
                ['name' => 'Salmão Grelhado', 'price' => 52.90, 'desc' => 'Filé de salmão com limão e legumes frescos'],
                ['name' => 'Risoto de Cogumelos', 'price' => 38.90, 'desc' => 'Risoto cremoso com cogumelos frescos'],
                ['name' => 'Frango à Parmesana', 'price' => 42.90, 'desc' => 'Peito de frango empanado com molho'],
                ['name' => 'Espaguete Carbonara', 'price' => 36.90, 'desc' => 'Receita italiana com bacon e ovos']
            ],
            'Bebidas' => [
                ['name' => 'Refrigerante Lata', 'price' => 7.90, 'desc' => 'Refrigerante gelado 350ml'],
                ['name' => 'Suco Natural', 'price' => 12.90, 'desc' => 'Suco natural prensado 500ml'],
                ['name' => 'Cerveja Artesanal', 'price' => 16.90, 'desc' => 'Cerveja artesanal 500ml'],
                ['name' => 'Vinho Tinto', 'price' => 35.90, 'desc' => 'Vinho tinto selecionado 750ml']
            ],
            'Sobremesas' => [
                ['name' => 'Tiramisú', 'price' => 18.90, 'desc' => 'Doce italiano com café e mascarpone'],
                ['name' => 'Brownie Quente', 'price' => 15.90, 'desc' => 'Brownie de chocolate com sorvete'],
                ['name' => 'Pavê de Chocolate', 'price' => 14.90, 'desc' => 'Pavê com cobertura de chocolate']
            ]
        ];

        $totalItems = 0;
        foreach ($items as $category => $itemList) {
            $catId = $categoryIds[$category];
            foreach ($itemList as $index => $item) {
                $stmt = $pdo->prepare("
                    INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available)
                    VALUES (1, ?, ?, ?, ?, ?, TRUE)
                ");
                $stmt->execute([$catId, $item['name'], $item['desc'], $item['price'], $index]);
                $totalItems++;
            }
        }

        $message = "✅ Demo configurado com sucesso! $totalItems produtos adicionados.";
    } catch (Exception $e) {
        $error = "❌ Erro: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Setup Demo - Zife Order</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #FBEEE3 0%, #F5F1ED 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1B1512;
            margin-bottom: 12px;
        }
        p {
            color: #7A6A5E;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert.success {
            background: #E8F5E9;
            border: 1px solid #4B7F52;
            color: #2E5233;
        }
        .alert.error {
            background: #FDEAEA;
            border: 1px solid #EF5350;
            color: #D32F2F;
        }
        .button {
            width: 100%;
            padding: 14px;
            background: #E8491D;
            color: white;
            border: none;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .button:hover {
            background: #C7380F;
        }
        .button:disabled {
            background: #CCC;
            cursor: not-allowed;
        }
        .info {
            background: #F5F1ED;
            border: 1px solid #E0D5CA;
            border-radius: 8px;
            padding: 16px;
            margin-top: 24px;
            font-size: 13px;
            color: #4A3E36;
            line-height: 1.8;
        }
        .info strong { color: #1B1512; }
        code {
            background: #FBEEE3;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Configurar Demo</h1>
        <p>Adicione produtos de teste para a demonstração do Zife Order</p>

        <?php if ($message): ?>
            <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="setup_demo">
            <button type="submit" class="button">Criar Demo com 18 Produtos</button>
        </form>

        <div class="info">
            <strong>Dados de Teste:</strong><br>
            Email: <code>demo@zife.local</code><br>
            Senha: <code>demo123</code><br><br>
            <strong>Link da Demo:</strong><br>
            <code>/app/menu?restaurant=1</code>
        </div>
    </div>
</body>
</html>
