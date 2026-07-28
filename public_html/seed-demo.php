<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    // Check if demo restaurant already exists
    $stmt = $pdo->prepare("SELECT id FROM restaurants WHERE id = 1");
    $stmt->execute();
    $restaurant = $stmt->fetch();

    if (!$restaurant) {
        // Create demo restaurant
        $stmt = $pdo->prepare("
            INSERT INTO restaurants (id, name, email, password, phone, address, city, state, description)
            VALUES (1, 'Zife Demo', 'demo@zife.local', ?, '(11) 99999-0000', 'Rua Demo, 123', 'São Paulo', 'SP', 'Restaurante de demonstração - Teste o Zife Order')
        ");
        $stmt->execute([password_hash('demo123', PASSWORD_BCRYPT)]);
    }

    // Clear existing categories and items for restaurant 1
    $pdo->prepare("DELETE FROM menu_items WHERE restaurant_id = 1")->execute();
    $pdo->prepare("DELETE FROM categories WHERE restaurant_id = 1")->execute();

    // Create categories
    $categories = [
        'Entradas',
        'Pratos Principais',
        'Bebidas',
        'Sobremesas'
    ];

    $categoryIds = [];
    foreach ($categories as $index => $name) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (restaurant_id, name, display_order)
            VALUES (1, ?, ?)
        ");
        $stmt->execute([$name, $index]);
        $categoryIds[$name] = $pdo->lastInsertId();
    }

    // Menu items data
    $items = [
        'Entradas' => [
            ['name' => 'Bruschetta Italiana', 'price' => 24.90, 'desc' => 'Pão tostado com tomate, alho e manjericão'],
            ['name' => 'Camarones al Ajillo', 'price' => 32.90, 'desc' => 'Camarões suculentos salteados no alho'],
            ['name' => 'Tábua de Queijos', 'price' => 38.90, 'desc' => 'Seleção de queijos artesanais e crocantes']
        ],
        'Pratos Principais' => [
            ['name' => 'Burguer Premium', 'price' => 45.90, 'desc' => 'Carne 180g, queijo cheddar, bacon, tomate'],
            ['name' => 'Salmão Grelhado', 'price' => 52.90, 'desc' => 'Filé de salmão com limão siciliano e legumes'],
            ['name' => 'Risoto de Cogumelos', 'price' => 38.90, 'desc' => 'Risoto cremoso com cogumelos frescos e sálvia'],
            ['name' => 'Frango à Parmesana', 'price' => 42.90, 'desc' => 'Peito de frango empanado com molho e queijo'],
            ['name' => 'Espaguete Carbonara', 'price' => 36.90, 'desc' => 'Receita italiana com bacon e ovos']
        ],
        'Bebidas' => [
            ['name' => 'Refrigerante Lata', 'price' => 7.90, 'desc' => 'Refrigerante gelado 350ml'],
            ['name' => 'Suco Natural Laranja', 'price' => 12.90, 'desc' => 'Suco natural prensado na hora'],
            ['name' => 'Cerveja Artesanal', 'price' => 16.90, 'desc' => 'Cerveja artesanal 500ml'],
            ['name' => 'Vinho Tinto', 'price' => 35.90, 'desc' => 'Vinho tinto selecionado 750ml']
        ],
        'Sobremesas' => [
            ['name' => 'Tiramisú', 'price' => 18.90, 'desc' => 'Doce italiano clássico com café'],
            ['name' => 'Brownie Quente', 'price' => 15.90, 'desc' => 'Brownie de chocolate com sorvete'],
            ['name' => 'Pavê de Chocolate', 'price' => 14.90, 'desc' => 'Pavê delicioso com cobertura de chocolate']
        ]
    ];

    // Insert menu items
    foreach ($items as $category => $itemList) {
        $categoryId = $categoryIds[$category];
        foreach ($itemList as $index => $item) {
            $stmt = $pdo->prepare("
                INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available)
                VALUES (1, ?, ?, ?, ?, ?, TRUE)
            ");
            $stmt->execute([$categoryId, $item['name'], $item['desc'], $item['price'], $index]);
        }
    }

    echo "✅ Dados de demo adicionados com sucesso!\n";
    echo "📱 Acesse: /app/menu?restaurant=1\n";
    echo "👤 Login demo: demo@zife.local | Senha: demo123\n";

} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
    exit(1);
}
?>
