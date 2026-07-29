<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'create-preference') {
        createMercadoPagoPreference($input);
    } elseif (!isset($input['action'])) {
        // Fallback para compatibilidade
        createMercadoPagoPreference($input);
    }
}

function createMercadoPagoPreference($data) {
    $db = Database::getInstance();

    $restaurant_id = $data['restaurant_id'] ?? null;
    $plan = $data['plan'] ?? null;
    $payment_method = $data['payment_method'] ?? 'pix';
    $restaurant_email = $data['restaurant_email'] ?? null;

    if (!$restaurant_id || !$plan) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados inválidos']);
        return;
    }

    $plans = PLANS;
    if (!isset($plans[$plan])) {
        http_response_code(400);
        echo json_encode(['error' => 'Plano inválido']);
        return;
    }

    $plan_info = $plans[$plan];
    $price = $plan_info['price'];

    // Se for plano grátis, não precisa de pagamento
    if ($price == 0) {
        echo json_encode(['success' => true, 'payment_url' => '/auth/login']);
        return;
    }

    $access_token = getenv('MERCADO_PAGO_ACCESS_TOKEN');

    if (!$access_token) {
        http_response_code(500);
        echo json_encode(['error' => 'Chave de Mercado Pago não configurada']);
        return;
    }

    // Dados da preferência de pagamento
    $preference_data = [
        'items' => [
            [
                'title' => 'Plano ' . $plan_info['name'] . ' - Zife Order',
                'description' => implode(', ', $plan_info['features']),
                'quantity' => 1,
                'unit_price' => $price
            ]
        ],
        'payer' => [
            'email' => $restaurant_email
        ],
        'payment_methods' => [
            'excluded_payment_types' => []
        ],
        'back_urls' => [
            'success' => 'https://' . $_SERVER['HTTP_HOST'] . '/payment-success?restaurant_id=' . $restaurant_id,
            'failure' => 'https://' . $_SERVER['HTTP_HOST'] . '/payment-failure?restaurant_id=' . $restaurant_id,
            'pending' => 'https://' . $_SERVER['HTTP_HOST'] . '/payment-pending?restaurant_id=' . $restaurant_id
        ],
        'external_reference' => 'zife_' . $restaurant_id . '_' . time(),
        'metadata' => [
            'restaurant_id' => $restaurant_id,
            'plan' => $plan,
            'payment_method' => $payment_method
        ]
    ];

    // Restringir método de pagamento se especificado
    if ($payment_method === 'pix') {
        $preference_data['payment_methods']['excluded_payment_types'] = [
            ['id' => 'credit_card'],
            ['id' => 'debit_card'],
            ['id' => 'bank_transfer']
        ];
    } elseif ($payment_method === 'credit_card') {
        $preference_data['payment_methods']['excluded_payment_types'] = [
            ['id' => 'ticket'],
            ['id' => 'bank_transfer']
        ];
    }

    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ],
            CURLOPT_POSTFIELDS => json_encode($preference_data),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response_data = json_decode($response, true);

        if ($http_code === 201 && isset($response_data['id'])) {
            // Salvar preferência no banco
            $db->insert(
                'INSERT INTO transactions (restaurant_id, amount, payment_method, status, gateway, gateway_transaction_id)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [$restaurant_id, $price, $payment_method, 'pending', 'mercado_pago', $response_data['id']]
            );

            // Gerar URL de checkout
            $checkout_url = 'https://checkout.mercadopago.com/checkout/v1/redirect?pref_id=' . $response_data['id'];

            echo json_encode([
                'success' => true,
                'payment_url' => $checkout_url,
                'preference_id' => $response_data['id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao criar preferência de pagamento',
                'details' => $response_data
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao processar pagamento: ' . $e->getMessage()]);
    }
}
