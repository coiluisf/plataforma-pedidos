<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = explode('/', trim($path, '/'))[2] ?? null;

if ($method === 'GET') {
    if (strpos($path, '/api/menu/restaurant/') !== false) {
        $parts = explode('/', trim($path, '/'));
        $restaurant_id = end($parts);
        getMenuByRestaurant($restaurant_id);
    } elseif (strpos($path, '/api/menu/') !== false) {
        $parts = explode('/', trim($path, '/'));
        $category_id = end($parts);
        getMenuByCategory($category_id);
    } else {
        getAllMenu();
    }
} elseif ($method === 'POST') {
    if (!isset($_SESSION['restaurant_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Não autorizado']);
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'add-item') {
        addMenuItem($input);
    } elseif ($action === 'add-category') {
        addCategory($input);
    }
} elseif ($method === 'PUT') {
    if (!isset($_SESSION['restaurant_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Não autorizado']);
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (strpos($path, '/api/menu/item/') !== false) {
        $parts = explode('/', trim($path, '/'));
        $item_id = end($parts);
        updateMenuItem($item_id, $input);
    }
} elseif ($method === 'DELETE') {
    if (!isset($_SESSION['restaurant_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Não autorizado']);
        return;
    }

    if (strpos($path, '/api/menu/item/') !== false) {
        $parts = explode('/', trim($path, '/'));
        $item_id = end($parts);
        deleteMenuItem($item_id);
    }
}

function getMenuByRestaurant($restaurant_id) {
    $db = Database::getInstance();

    $categories = $db->fetchAll(
        'SELECT id, name, display_order FROM categories WHERE restaurant_id = ? ORDER BY display_order',
        [$restaurant_id]
    );

    $menu = [];
    foreach ($categories as $category) {
        $items = $db->fetchAll(
            'SELECT id, name, description, price, image_url, available FROM menu_items WHERE category_id = ? AND available = TRUE ORDER BY display_order',
            [$category['id']]
        );

        $menu[] = [
            'id' => $category['id'],
            'name' => $category['name'],
            'items' => $items
        ];
    }

    echo json_encode($menu);
}

function getMenuByCategory($restaurant_id) {
    $db = Database::getInstance();

    $items = $db->fetchAll(
        'SELECT id, name, description, price, image_url, available FROM menu_items WHERE restaurant_id = ? AND available = TRUE ORDER BY display_order',
        [$restaurant_id]
    );

    echo json_encode($items);
}

function getAllMenu() {
    $db = Database::getInstance();

    $categories = $db->fetchAll(
        'SELECT DISTINCT c.id, c.name FROM categories c
         INNER JOIN menu_items m ON c.id = m.category_id
         WHERE m.available = TRUE ORDER BY c.display_order'
    );

    $menu = [];
    foreach ($categories as $category) {
        $items = $db->fetchAll(
            'SELECT id, name, description, price, image_url FROM menu_items WHERE category_id = ? AND available = TRUE ORDER BY display_order',
            [$category['id']]
        );

        $menu[] = [
            'id' => $category['id'],
            'name' => $category['name'],
            'items' => $items
        ];
    }

    echo json_encode($menu);
}

function addMenuItem($data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $category_id = $data['category_id'] ?? null;
    $image_url = $data['image_url'] ?? null;

    if (!$name || !$price || !$category_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Nome, preço e categoria são obrigatórios']);
        return;
    }

    // Verify category belongs to restaurant
    $category = $db->fetch('SELECT id FROM categories WHERE id = ? AND restaurant_id = ?', [$category_id, $restaurant_id]);
    if (!$category) {
        http_response_code(403);
        echo json_encode(['error' => 'Categoria não encontrada']);
        return;
    }

    try {
        $item_id = $db->insert(
            'INSERT INTO menu_items (restaurant_id, category_id, name, description, price, image_url, available) VALUES (?, ?, ?, ?, ?, ?, TRUE)',
            [$restaurant_id, $category_id, $name, $description, floatval($price), $image_url]
        );

        echo json_encode(['success' => true, 'id' => $item_id]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao adicionar item']);
    }
}

function addCategory($data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    $name = $data['name'] ?? null;

    if (!$name) {
        http_response_code(400);
        echo json_encode(['error' => 'Nome da categoria obrigatório']);
        return;
    }

    try {
        $category_id = $db->insert(
            'INSERT INTO categories (restaurant_id, name) VALUES (?, ?)',
            [$restaurant_id, $name]
        );

        echo json_encode(['success' => true, 'id' => $category_id]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao adicionar categoria']);
    }
}

function updateMenuItem($item_id, $data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    // Verify item belongs to restaurant
    $item = $db->fetch('SELECT id FROM menu_items WHERE id = ? AND restaurant_id = ?', [$item_id, $restaurant_id]);
    if (!$item) {
        http_response_code(403);
        echo json_encode(['error' => 'Item não encontrado']);
        return;
    }

    $updates = [];
    $params = [];

    if (isset($data['name'])) {
        $updates[] = 'name = ?';
        $params[] = $data['name'];
    }
    if (isset($data['description'])) {
        $updates[] = 'description = ?';
        $params[] = $data['description'];
    }
    if (isset($data['price'])) {
        $updates[] = 'price = ?';
        $params[] = floatval($data['price']);
    }
    if (isset($data['available'])) {
        $updates[] = 'available = ?';
        $params[] = $data['available'] ? 1 : 0;
    }
    if (isset($data['image_url'])) {
        $updates[] = 'image_url = ?';
        $params[] = $data['image_url'];
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nenhum dado para atualizar']);
        return;
    }

    $params[] = $item_id;

    try {
        $db->update(
            'UPDATE menu_items SET ' . implode(', ', $updates) . ' WHERE id = ?',
            $params
        );

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar item']);
    }
}

function deleteMenuItem($item_id) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    // Verify item belongs to restaurant
    $item = $db->fetch('SELECT id FROM menu_items WHERE id = ? AND restaurant_id = ?', [$item_id, $restaurant_id]);
    if (!$item) {
        http_response_code(403);
        echo json_encode(['error' => 'Item não encontrado']);
        return;
    }

    try {
        $db->delete('DELETE FROM menu_items WHERE id = ?', [$item_id]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao deletar item']);
    }
}
