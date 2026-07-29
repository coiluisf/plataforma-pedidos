<?php
require_once __DIR__ . '/../../config/constants.php';

// Verificar autenticação
if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /auth/login');
    exit;
}

// Buscar dados do restaurante
$db = Database::getInstance();
$restaurant = $db->fetch(
    'SELECT id, name, plan FROM restaurants WHERE id = ?',
    [$_SESSION['restaurant_id']]
);

// Determinar página ativa
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$current_page = 'dashboard';
if (strpos($request_uri, 'orders') !== false) $current_page = 'orders';
elseif (strpos($request_uri, 'menu') !== false) $current_page = 'menu';
elseif (strpos($request_uri, 'payments') !== false) $current_page = 'payments';
elseif (strpos($request_uri, 'tables') !== false) $current_page = 'tables';
elseif (strpos($request_uri, 'reports') !== false) $current_page = 'reports';
elseif (strpos($request_uri, 'settings') !== false) $current_page = 'settings';

$page_titles = [
    'dashboard' => 'Visão Geral',
    'orders' => 'Pedidos',
    'menu' => 'Cardápio',
    'payments' => 'Pagamentos',
    'tables' => 'Mesas',
    'reports' => 'Relatórios',
    'settings' => 'Configurações'
];

$page_title = $page_titles[$current_page] ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Zife Order Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Zife Order</h2>
        </div>

        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span>Visão Geral</span>
            </a>
            <a href="/admin/orders" class="nav-link <?php echo $current_page === 'orders' ? 'active' : ''; ?>">
                <span class="nav-icon">📋</span>
                <span>Pedidos</span>
            </a>
            <a href="/admin/menu" class="nav-link <?php echo $current_page === 'menu' ? 'active' : ''; ?>">
                <span class="nav-icon">🍽️</span>
                <span>Cardápio</span>
            </a>
            <a href="/admin/payments" class="nav-link <?php echo $current_page === 'payments' ? 'active' : ''; ?>">
                <span class="nav-icon">💳</span>
                <span>Pagamentos</span>
            </a>
            <a href="/admin/tables" class="nav-link <?php echo $current_page === 'tables' ? 'active' : ''; ?>">
                <span class="nav-icon">🪑</span>
                <span>Mesas</span>
            </a>
            <a href="/admin/reports" class="nav-link <?php echo $current_page === 'reports' ? 'active' : ''; ?>">
                <span class="nav-icon">📈</span>
                <span>Relatórios</span>
            </a>
            <a href="/admin/settings" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                <span class="nav-icon">⚙️</span>
                <span>Configurações</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="account-card">
                <div class="account-avatar"><?php echo strtoupper(substr($restaurant['name'], 0, 2)); ?></div>
                <div class="account-info">
                    <strong><?php echo htmlspecialchars($restaurant['name']); ?></strong>
                    <small><?php echo ucfirst($restaurant['plan']); ?></small>
                </div>
            </div>
            <a href="/admin/logout" class="btn-logout">Sair</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="admin-wrapper">
        <!-- TOPBAR -->
        <header class="topbar">
            <h1 class="page-title"><?php echo $page_title; ?></h1>
            <div class="topbar-actions">
                <button class="btn-icon" title="Notificações">🔔</button>
                <button class="btn-icon" title="Perfil">👤</button>
            </div>
        </header>

        <!-- CONTEÚDO -->
        <main class="admin-content">
            <?php
            // Incluir página específica
            $page_file = __DIR__ . '/' . $current_page . '.php';
            if (file_exists($page_file)) {
                include $page_file;
            } else {
                echo '<p>Página não encontrada</p>';
            }
            ?>
        </main>
    </div>
</body>
</html>
