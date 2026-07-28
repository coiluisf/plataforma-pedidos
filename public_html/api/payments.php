<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (strpos($path, '/api/payments/create-preference') !== false) {
        createMercadoPagoPreference($input);
    } elseif (strpos($path, '/api/payments/webhook') !== false) {
        handleMercadoPagoWebhook($input);
    }
} elseif ($method === 'GET') {
    if (isset($_SESSION['restaurant_id']) && strpos($path, '/api/payments/methods') !== false) {
        getPaymentMethods($_SESSION['restaurant_id']);
    }
}

function createMercadoPagoPreference($data) {
    $order_id = $data['order_id'] ?? null;
    $order_number = $data['order_number'] ?? null;
    $total = $data['total'] ?? null;
    $customer_name = $data['customer_name'] ?? null;
    $customer_email = $data['customer_email'] ?? null;

    if (!$order_id || !$total) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados obrigatórios faltando']);
        return;
    }

    $access_token = MERCADO_PAGO_ACCESS_TOKEN;
    if (!$access_token) {
        http_response_code(500);
        echo json_encode(['error' => 'Gateway de pagamento não configurado']);
        return;
    }

    // Create Mercado Pago preference
    $preference = [
        'items' => [
            [
                'title' => 'Pedido ' . $order_number,
                'quantity' => 1,
                'unit_price' => floatval($total)
            ]
        ],
        'payer' => [
            'name' => $customer_name,
            'email' => $customer_email
        ],
        'notification_url' => BASE_URL . '/api/payments/webhook',
        'back_urls' => [
            'success' => BASE_URL . '/app/confirmation?order_id=' . $order_id,
            'failure' => BASE_URL . '/app/checkout?error=payment_failed&order_id=' . $order_id,
            'pending' => BASE_URL . '/app/checkout?status=pending&order_id=' . $order_id
        ],
        'auto_return' => 'approved'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($preference),
        CURLOPT_CAINFO => '/root/.ccr/ca-bundle.crt'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 201) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao criar preferência de pagamento']);
        return;
    }

    $result = json_decode($response, true);

    // Store payment preference
    $db = Database::getInstance();
    $db->insert(
        'INSERT INTO transactions (order_id, amount, payment_method, status, payment_gateway, gateway_transaction_id) VALUES (?, ?, ?, ?, ?, ?)',
        [$order_id, floatval($total), 'credit_card', 'pending', 'mercado_pago', $result['id']]
    );

    echo json_encode([
        'success' => true,
        'init_point' => $result['init_point'],
        'preference_id' => $result['id']
    ]);
}

function handleMercadoPagoWebhook($data) {
    $db = Database::getInstance();

    $type = $data['type'] ?? null;
    $id = $data['id'] ?? null;

    if ($type !== 'payment') {
        http_response_code(200);
        echo json_encode(['success' => true]);
        return;
    }

    $access_token = MERCADO_PAGO_ACCESS_TOKEN;
    if (!$access_token) {
        http_response_code(500);
        return;
    }

    // Fetch payment details from Mercado Pago
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token
        ],
        CURLOPT_CAINFO => '/root/.ccr/ca-bundle.crt'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $payment = json_decode($response, true);

    if ($payment['status'] === 'approved') {
        // Find transaction and update order
        $transaction = $db->fetch(
            'SELECT id, order_id FROM transactions WHERE gateway_transaction_id = ?',
            [$payment['preference_id']]
        );

        if ($transaction) {
            $db->update(
                'UPDATE transactions SET status = ? WHERE id = ?',
                ['completed', $transaction['id']]
            );

            $db->update(
                'UPDATE orders SET payment_status = ?, status = ? WHERE id = ?',
                ['completed', 'preparing', $transaction['order_id']]
            );
        }
    }

    http_response_code(200);
    echo json_encode(['success' => true]);
}

function getPaymentMethods($restaurant_id) {
    $db = Database::getInstance();

    $methods = $db->fetchAll(
        'SELECT method, enabled FROM payment_methods WHERE restaurant_id = ?',
        [$restaurant_id]
    );

    $result = [
        'pix' => false,
        'credit_card' => false
    ];

    foreach ($methods as $method) {
        if ($method['method'] === 'pix') {
            $result['pix'] = $method['enabled'];
        } elseif ($method['method'] === 'credit_card') {
            $result['credit_card'] = $method['enabled'];
        }
    }

    echo json_encode($result);
}
