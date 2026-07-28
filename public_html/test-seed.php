<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();
    
    // Verificar se já existem itens para restaurant_id=1
    $check = $pdo->prepare("SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = 1");
    $check->execute();
    $result = $check->fetch();
    
    if ($result['count'] == 0) {
        echo "📊 Iniciando população de dados de demo...\n\n";
        
        // Criar restaurante de demo se não existir
        $stmt = $pdo->prepare("INSERT IGNORE INTO restaurants (id, name, email, password, phone, address, city, state, description, active) VALUES (1, 'Zife Demo', 'demo@zife.local', ?, '(11) 99999-0000', 'Rua Demo, 123', 'São Paulo', 'SP', 'Restaurante de demonstração', TRUE)");
        $stmt->execute([password_hash('demo123', PASSWORD_BCRYPT)]);
        
        // Limpar categorias e itens anteriores
        $pdo->prepare("DELETE FROM menu_items WHERE restaurant_id = 1")->execute();
        $pdo->prepare("DELETE FROM categories WHERE restaurant_id = 1")->execute();
        
        // Dados das categorias
        $categories = [
            'Entradas' => 0,
            'Pratos Principais' => 1,
            'Bebidas' => 2,
            'Sobremesas' => 3
        ];
        
        $categoryIds = [];
        foreach ($categories as $name => $order) {
            $stmt = $pdo->prepare("INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, ?, ?)");
            $stmt->execute([$name, $order]);
            $categoryIds[$name] = $pdo->lastInsertId();
            echo "✓ Categoria criada: $name\n";
        }
        
        // Dados dos produtos
        $items = [
            'Entradas' => [
                ['name' => 'Bruschetta Italiana', 'price' => 24.90, 'desc' => 'Pão tostado com tomate, alho e manjericão fresco'],
                ['name' => 'Camarones al Ajillo', 'price' => 32.90, 'desc' => 'Camarões suculentos salteados no alho e azeite'],
                ['name' => 'Tábua de Queijos', 'price' => 38.90, 'desc' => 'Seleção de queijos artesanais e complementos']
            ],
            'Pratos Principais' => [
                ['name' => 'Burger Premium', 'price' => 45.90, 'desc' => 'Carne 180g, queijo cheddar, bacon e tomate'],
                ['name' => 'Salmão Grelhado', 'price' => 52.90, 'desc' => 'Filé de salmão com limão siciliano e legumes'],
                ['name' => 'Risoto de Cogumelos', 'price' => 38.90, 'desc' => 'Risoto cremoso com cogumelos frescos'],
                ['name' => 'Frango à Parmesana', 'price' => 42.90, 'desc' => 'Peito de frango empanado com molho e queijo'],
                ['name' => 'Espaguete Carbonara', 'price' => 36.90, 'desc' => 'Receita italiana autêntica com bacon e ovos']
            ],
            'Bebidas' => [
                ['name' => 'Refrigerante Lata', 'price' => 7.90, 'desc' => 'Refrigerante gelado 350ml'],
                ['name' => 'Suco Natural', 'price' => 12.90, 'desc' => 'Suco natural prensado 500ml'],
                ['name' => 'Cerveja Artesanal', 'price' => 16.90, 'desc' => 'Cerveja artesanal premium 500ml'],
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
                $stmt = $pdo->prepare("INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available) VALUES (1, ?, ?, ?, ?, ?, TRUE)");
                $stmt->execute([$catId, $item['name'], $item['desc'], $item['price'], $index]);
                $totalItems++;
            }
            echo "✓ $totalItems produtos adicionados em $category\n";
        }
        
        echo "\n✅ Demo configurada com sucesso!\n";
        echo "📍 Acesse: https://seu-dominio.com/app/menu?restaurant=1\n";
        echo "👤 Email: demo@zife.local | Senha: demo123\n";
    } else {
        echo "✓ Demo já possui " . $result['count'] . " produtos configurados!\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
