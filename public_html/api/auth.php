<?php
session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'login':
            handleLogin($input ?? $_POST);
            break;
        case 'register':
            handleRegister($input ?? $_POST);
            break;
        case 'logout':
            session_destroy();
            echo json_encode(['success' => true]);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
}

function handleLogin($data) {
    $db = Database::getInstance();

    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password required']);
        return;
    }

    $restaurant = $db->fetch(
        'SELECT id, name, email, password, plan, active FROM restaurants WHERE email = ?',
        [$email]
    );

    if (!$restaurant) {
        http_response_code(401);
        echo json_encode(['error' => 'Email ou senha inválidos']);
        return;
    }

    if (!$restaurant['active']) {
        http_response_code(403);
        echo json_encode(['error' => 'Sua conta está desativada']);
        return;
    }

    if (!password_verify($password, $restaurant['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Email ou senha inválidos']);
        return;
    }

    $_SESSION['restaurant_id'] = $restaurant['id'];
    $_SESSION['restaurant_name'] = $restaurant['name'];
    $_SESSION['plan'] = $restaurant['plan'];

    echo json_encode([
        'success' => true,
        'restaurant' => [
            'id' => $restaurant['id'],
            'name' => $restaurant['name'],
            'plan' => $restaurant['plan']
        ]
    ]);
}

function handleRegister($data) {
    $db = Database::getInstance();

    $name = $data['name'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $password_confirm = $data['password_confirm'] ?? null;
    $phone = $data['phone'] ?? null;

    // Validation
    if (!$name || !$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Nome, email e senha são obrigatórios']);
        return;
    }

    if ($password !== $password_confirm) {
        http_response_code(400);
        echo json_encode(['error' => 'As senhas não conferem']);
        return;
    }

    if (strlen($password) < 6) {
        http_response_code(400);
        echo json_encode(['error' => 'A senha deve ter no mínimo 6 caracteres']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        return;
    }

    // Check if email exists
    $existing = $db->fetch('SELECT id FROM restaurants WHERE email = ?', [$email]);
    if ($existing) {
        http_response_code(409);
        echo json_encode(['error' => 'Este email já está registrado']);
        return;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Create restaurant account
    try {
        $restaurant_id = $db->insert(
            'INSERT INTO restaurants (name, email, password, phone, plan) VALUES (?, ?, ?, ?, ?)',
            [$name, $email, $hashed_password, $phone, 'free']
        );

        // Create default categories
        $default_categories = ['Lanches', 'Porções', 'Bebidas', 'Sobremesas'];
        foreach ($default_categories as $i => $category) {
            $db->insert(
                'INSERT INTO categories (restaurant_id, name, display_order) VALUES (?, ?, ?)',
                [$restaurant_id, $category, $i]
            );
        }

        // Enable Pix by default
        $db->insert(
            'INSERT INTO payment_methods (restaurant_id, method, enabled) VALUES (?, ?, ?)',
            [$restaurant_id, 'pix', true]
        );

        $_SESSION['restaurant_id'] = $restaurant_id;
        $_SESSION['restaurant_name'] = $name;
        $_SESSION['plan'] = 'free';

        echo json_encode([
            'success' => true,
            'restaurant' => [
                'id' => $restaurant_id,
                'name' => $name,
                'plan' => 'free'
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao criar conta: ' . $e->getMessage()]);
    }
}
