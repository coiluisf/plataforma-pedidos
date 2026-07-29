<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET') {
    if (isset($_SESSION['restaurant_id'])) {
        // Admin - get restaurant orders
        $status = $_GET['status'] ?? null;
        getRestaurantOrders($_SESSION['restaurant_id'], $status);
    } else {
        // Client - check order status
        $order_number = $_GET['order_number'] ?? null;
        if ($order_number) {
            getOrderStatus($order_number);
        }
    }
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (strpos($path, '/api/orders/create') !== false) {
        createOrder($input);
    } elseif (strpos($path, '/api/orders/update-status') !== false) {
        if (!isset($_SESSION['restaurant_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Não autorizado']);
            return;
        }
        updateOrderStatus($input);
    }
}

function getRestaurantOrders($restaurant_id, $status = null) {
    $db = Database::getInstance();

    $sql = 'SELECT * FROM orders WHERE restaurant_id = ?';
    $params = [$restaurant_id];

    if ($status) {
        $sql .= ' AND status = ?';
        $params[] = $status;
    }

    $sql .= ' ORDER BY created_at DESC LIMIT 50';

    $orders = $db->fetchAll($sql, $params);

    // Get items for each order
    foreach ($orders as &$order) {
        $order['items'] = $db->fetchAll(
            'SELECT oi.*, m.name as menu_item_name FROM order_items oi
             JOIN menu_items m ON oi.menu_item_id = m.id
             WHERE oi.order_id = ?',
            [$order['id']]
        );
    }

    echo json_encode($orders);
}

function getOrderStatus($order_number) {
    $db = Database::getInstance();

    $order = $db->fetch(
        'SELECT id, order_number, status, customer_name, customer_phone, estimated_delivery_time, payment_status FROM orders WHERE order_number = ?',
        [$order_number]
    );

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Pedido não encontrado']);
        return;
    }

    $order['items'] = $db->fetchAll(
        'SELECT oi.quantity, oi.unit_price, m.name FROM order_items oi
         JOIN menu_items m ON oi.menu_item_id = m.id
         WHERE oi.order_id = ?',
        [$order['id']]
    );

    echo json_encode($order);
}

function createOrder($data) {
    $db = Database::getInstance();

    $restaurant_id = $data['restaurant_id'] ?? null;
    $customer_name = $data['customer_name'] ?? null;
    $customer_phone = $data['customer_phone'] ?? null;
    $customer_email = $data['customer_email'] ?? null;
    $order_type = $data['order_type'] ?? 'delivery';
    $delivery_address = $data['delivery_address'] ?? null;
    $payment_method = $data['payment_method'] ?? null;
    $items = $data['items'] ?? [];
    $notes = $data['notes'] ?? null;

    if (!$restaurant_id || !$customer_name || empty($items)) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados obrigatórios faltando']);
        return;
    }

    // Verify restaurant exists
    $restaurant = $db->fetch(
        'SELECT id, plan FROM restaurants WHERE id = ?',
        [$restaurant_id]
    );

    if (!$restaurant) {
        http_response_code(404);
        echo json_encode(['error' => 'Restaurante não encontrado']);
        return;
    }

    // Check order limit for free plan
    $current_month = date('Y-m-01');
    $order_count = $db->fetch(
        'SELECT COUNT(*) as count FROM orders WHERE restaurant_id = ? AND created_at >= ?',
        [$restaurant_id, $current_month]
    );

    if ($restaurant['plan'] === 'free' && $order_count['count'] >= 50) {
        http_response_code(403);
        echo json_encode(['error' => 'Limite de pedidos mensais atingido. Atualize seu plano.']);
        return;
    }

    try {
        // Calculate totals
        $subtotal = 0;
        $delivery_fee = $order_type === 'delivery' ? 5.00 : 0;

        $menu_items = [];
        foreach ($items as $item) {
            $menu_item = $db->fetch(
                'SELECT id, price FROM menu_items WHERE id = ?',
                [$item['id']]
            );
            if ($menu_item) {
                $qty = $item['qty'] ?? $item['quantity'] ?? 0;
                $menu_items[] = [
                    'id' => $menu_item['id'],
                    'price' => $menu_item['price'],
                    'quantity' => $qty
                ];
                $subtotal += $menu_item['price'] * $qty;
            }
        }

        $total = $subtotal + $delivery_fee;

        // Generate order number
        $order_number = 'ZO' . date('YmdHis') . rand(100, 999);

        // Insert order
        $order_id = $db->insert(
            'INSERT INTO orders (restaurant_id, order_number, customer_name, customer_phone, customer_email, order_type, delivery_address, delivery_fee, subtotal, total, payment_method, notes, estimated_delivery_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$restaurant_id, $order_number, $customer_name, $customer_phone, $customer_email, $order_type, $delivery_address, $delivery_fee, $subtotal, $total, $payment_method, $notes, 30]
        );

        // Insert order items
        foreach ($menu_items as $item) {
            $db->insert(
                'INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)',
                [$order_id, $item['id'], $item['quantity'], $item['price'], $item['price'] * $item['quantity']]
            );
        }

        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'order_number' => $order_number,
            'total' => $total
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao criar pedido']);
    }
}

function updateOrderStatus($data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    $order_id = $data['order_id'] ?? null;
    $status = $data['status'] ?? null;

    if (!$order_id || !$status) {
        http_response_code(400);
        echo json_encode(['error' => 'ID do pedido e status são obrigatórios']);
        return;
    }

    // Verify order belongs to restaurant
    $order = $db->fetch(
        'SELECT id FROM orders WHERE id = ? AND restaurant_id = ?',
        [$order_id, $restaurant_id]
    );

    if (!$order) {
        http_response_code(403);
        echo json_encode(['error' => 'Pedido não encontrado']);
        return;
    }

    try {
        $db->update(
            'UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?',
            [$status, $order_id]
        );

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar status']);
    }
}
