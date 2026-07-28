<?php
session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['restaurant_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET') {
    if (strpos($path, '/api/admin/dashboard') !== false) {
        getDashboardData($_SESSION['restaurant_id']);
    } elseif (strpos($path, '/api/admin/stats') !== false) {
        getStats($_SESSION['restaurant_id']);
    }
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (strpos($path, '/api/admin/settings') !== false) {
        updateSettings($input);
    } elseif (strpos($path, '/api/admin/payment-method') !== false) {
        updatePaymentMethod($input);
    }
}

function getDashboardData($restaurant_id) {
    $db = Database::getInstance();

    // Get restaurant info
    $restaurant = $db->fetch(
        'SELECT name, plan, order_count_this_month FROM restaurants WHERE id = ?',
        [$restaurant_id]
    );

    // Get stats cards
    $today = date('Y-m-d');
    $this_month = date('Y-m-01');

    $today_orders = $db->fetch(
        'SELECT COUNT(*) as count FROM orders WHERE restaurant_id = ? AND DATE(created_at) = ?',
        [$restaurant_id, $today]
    );

    $today_revenue = $db->fetch(
        'SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE restaurant_id = ? AND DATE(created_at) = ? AND payment_status = "completed"',
        [$restaurant_id, $today]
    );

    $month_orders = $db->fetch(
        'SELECT COUNT(*) as count FROM orders WHERE restaurant_id = ? AND created_at >= ?',
        [$restaurant_id, $this_month]
    );

    $month_revenue = $db->fetch(
        'SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE restaurant_id = ? AND created_at >= ? AND payment_status = "completed"',
        [$restaurant_id, $this_month]
    );

    // Get recent orders
    $recent_orders = $db->fetchAll(
        'SELECT id, order_number, customer_name, status, total, created_at FROM orders WHERE restaurant_id = ? ORDER BY created_at DESC LIMIT 5',
        [$restaurant_id]
    );

    // Get hourly orders chart data
    $hourly_data = $db->fetchAll(
        'SELECT HOUR(created_at) as hour, COUNT(*) as count FROM orders WHERE restaurant_id = ? AND DATE(created_at) = ? GROUP BY HOUR(created_at) ORDER BY hour',
        [$restaurant_id, $today]
    );

    echo json_encode([
        'restaurant' => $restaurant,
        'stats' => [
            'today_orders' => $today_orders['count'],
            'today_revenue' => floatval($today_revenue['total']),
            'month_orders' => $month_orders['count'],
            'month_revenue' => floatval($month_revenue['total'])
        ],
        'recent_orders' => $recent_orders,
        'hourly_data' => $hourly_data
    ]);
}

function getStats($restaurant_id) {
    $db = Database::getInstance();

    $last_7_days = date('Y-m-d', strtotime('-7 days'));

    // Get daily stats for last 7 days
    $daily_stats = $db->fetchAll(
        'SELECT DATE(created_at) as date, COUNT(*) as count, COALESCE(SUM(total), 0) as revenue FROM orders WHERE restaurant_id = ? AND created_at >= ? AND payment_status = "completed" GROUP BY DATE(created_at) ORDER BY date',
        [$restaurant_id, $last_7_days]
    );

    // Get top items
    $top_items = $db->fetchAll(
        'SELECT m.name, SUM(oi.quantity) as total_sold, COALESCE(SUM(oi.subtotal), 0) as revenue FROM order_items oi
         JOIN menu_items m ON oi.menu_item_id = m.id
         JOIN orders o ON oi.order_id = o.id
         WHERE o.restaurant_id = ? AND o.created_at >= ? GROUP BY oi.menu_item_id ORDER BY total_sold DESC LIMIT 10',
        [$restaurant_id, $last_7_days]
    );

    echo json_encode([
        'daily_stats' => $daily_stats,
        'top_items' => $top_items
    ]);
}

function updateSettings($data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    $updates = [];
    $params = [];

    $allowed_fields = ['name', 'phone', 'address', 'city', 'state', 'zip_code', 'business_hours_open', 'business_hours_close', 'description'];

    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $updates[] = $field . ' = ?';
            $params[] = $data[$field];
        }
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nenhum dado para atualizar']);
        return;
    }

    $params[] = $restaurant_id;

    try {
        $db->update(
            'UPDATE restaurants SET ' . implode(', ', $updates) . ' WHERE id = ?',
            $params
        );

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar configurações']);
    }
}

function updatePaymentMethod($data) {
    $db = Database::getInstance();
    $restaurant_id = $_SESSION['restaurant_id'];

    $method = $data['method'] ?? null;
    $enabled = $data['enabled'] ?? null;

    if (!$method || $enabled === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Método de pagamento e status são obrigatórios']);
        return;
    }

    if (!in_array($method, ['pix', 'credit_card'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Método de pagamento inválido']);
        return;
    }

    try {
        // Check if payment method exists
        $existing = $db->fetch(
            'SELECT id FROM payment_methods WHERE restaurant_id = ? AND method = ?',
            [$restaurant_id, $method]
        );

        if ($existing) {
            $db->update(
                'UPDATE payment_methods SET enabled = ? WHERE restaurant_id = ? AND method = ?',
                [$enabled ? 1 : 0, $restaurant_id, $method]
            );
        } else {
            $db->insert(
                'INSERT INTO payment_methods (restaurant_id, method, enabled) VALUES (?, ?, ?)',
                [$restaurant_id, $method, $enabled ? 1 : 0]
            );
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar método de pagamento']);
    }
}
