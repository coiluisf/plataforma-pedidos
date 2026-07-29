<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove /public_html from the path if present
$request_uri = str_replace('/public_html', '', $request_uri);
$request_uri = rtrim($request_uri, '/') ?: '/';

// Route logic
if ($request_uri === '/') {
    include __DIR__ . '/views/landing-premium.php';
} elseif (strpos($request_uri, '/checkout-plan') === 0) {
    include __DIR__ . '/views/checkout-plan.php';
} elseif (strpos($request_uri, '/payment-success') === 0) {
    include __DIR__ . '/views/payment-success.php';
} elseif (strpos($request_uri, '/payment-failure') === 0) {
    include __DIR__ . '/views/payment-failure.php';
} elseif (strpos($request_uri, '/admin') === 0) {
    if (!isset($_SESSION['restaurant_id'])) {
        header('Location: /auth/login');
        exit;
    }
    routeAdmin($request_uri);
} elseif (strpos($request_uri, '/app') === 0) {
    routeApp($request_uri);
} elseif (strpos($request_uri, '/api') === 0) {
    header('Content-Type: application/json');
    routeApi($request_uri, $request_method);
} elseif (strpos($request_uri, '/auth') === 0) {
    routeAuth($request_uri, $request_method);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}

function routeAdmin($uri) {
    $parts = explode('/', trim($uri, '/'));
    $action = $parts[1] ?? 'dashboard';

    switch ($action) {
        case 'dashboard':
        case 'orders':
        case 'menu':
        case 'payments':
        case 'tables':
        case 'reports':
        case 'settings':
            // Use premium base layout for all admin pages
            include __DIR__ . '/views/admin/base-layout-premium.php';
            break;
        case 'logout':
            // Expirar o cookie de sessão
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            session_destroy();
            header('Location: /');
            exit;
        default:
            http_response_code(404);
            break;
    }
}

function routeApp($uri) {
    $parts = explode('/', trim($uri, '/'));
    $action = $parts[1] ?? 'menu';

    switch ($action) {
        case 'menu':
            include __DIR__ . '/views/app/menu.php';
            break;
        case 'cart':
            include __DIR__ . '/views/app/cart.php';
            break;
        case 'checkout':
            include __DIR__ . '/views/app/checkout.php';
            break;
        case 'confirmation':
            include __DIR__ . '/views/app/confirmation.php';
            break;
        default:
            http_response_code(404);
            break;
    }
}

function routeApi($uri, $method) {
    $parts = explode('/', trim($uri, '/'));
    $resource = $parts[1] ?? null;
    $action = $parts[2] ?? null;

    switch ($resource) {
        case 'auth':
            include __DIR__ . '/api/auth.php';
            break;
        case 'menu':
            include __DIR__ . '/api/menu.php';
            break;
        case 'orders':
            include __DIR__ . '/api/orders.php';
            break;
        case 'payments':
            include __DIR__ . '/api/payments.php';
            break;
        case 'checkout':
            include __DIR__ . '/api/checkout.php';
            break;
        case 'admin':
            include __DIR__ . '/api/admin.php';
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            break;
    }
}

function routeAuth($uri, $method) {
    $parts = explode('/', trim($uri, '/'));
    $action = $parts[1] ?? null;

    switch ($action) {
        case 'login':
            if ($method === 'GET') {
                include __DIR__ . '/views/auth/login.php';
            } elseif ($method === 'POST') {
                include __DIR__ . '/api/auth.php';
            }
            break;
        case 'register':
            if ($method === 'GET') {
                include __DIR__ . '/views/auth/register.php';
            } elseif ($method === 'POST') {
                include __DIR__ . '/api/auth.php';
            }
            break;
        default:
            header('Location: /auth/login');
            break;
    }
}
