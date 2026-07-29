<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /auth/login');
    exit;
}

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$current_page = trim(explode('/', $request_uri)[2] ?? 'dashboard', '/');

$restaurant_id = $_SESSION['restaurant_id'];
$db = Database::getInstance();

// Get restaurant data
$restaurant = $db->fetch(
    'SELECT name, plan FROM restaurants WHERE id = ?',
    [$restaurant_id]
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zife Order - Painel Administrativo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/views/admin/design-system.css">
    <style>
        /* ============================================
           SIDEBAR PREMIUM
           ============================================ */

        .sidebar {
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 0 20px 32px;
            border-bottom: 1px solid var(--color-border);
            margin-bottom: 24px;
        }

        .sidebar-logo {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text);
            letter-spacing: -0.5px;
        }

        .sidebar-section {
            padding: 0 12px;
            margin-bottom: 32px;
        }

        .sidebar-section-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 8px 12px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu-item {
            margin-bottom: 6px;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--color-text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-menu-link:hover {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .sidebar-menu-link.active {
            background-color: rgba(228, 93, 34, 0.1);
            color: var(--color-primary);
            font-weight: 600;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 24px 12px 0;
            border-top: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background-color: var(--color-bg);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .profile-card:hover {
            background-color: rgba(228, 93, 34, 0.05);
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary) 0%, #F97316 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-plan {
            font-size: 11px;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============================================
           TOPBAR PREMIUM
           ============================================ */

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            gap: 24px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 24px;
            flex: 1;
        }

        .topbar-search {
            flex: 1;
            max-width: 300px;
        }

        .topbar-search input {
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            background-color: var(--color-bg);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background-color: transparent;
            border: 1px solid var(--color-border);
            color: var(--color-text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
            position: relative;
        }

        .topbar-icon-btn:hover {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .topbar-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 18px;
            height: 18px;
            background-color: var(--color-error);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */

        .main-content {
            padding: 32px;
            max-width: 1600px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        /* ============================================
           STATS ROW
           ============================================ */

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background-color: var(--color-cards);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: all var(--transition-base);
        }

        .stat-card:hover {
            border-color: var(--color-primary);
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            font-size: 12px;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .stat-change {
            font-size: 12px;
            color: var(--color-success);
            font-weight: 600;
        }

        .stat-change.negative {
            color: var(--color-error);
        }

        /* ============================================
           INSIGHTS
           ============================================ */

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .insight-card {
            background-color: var(--color-cards);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 16px;
            transition: all var(--transition-base);
        }

        .insight-card:hover {
            border-color: var(--color-primary);
        }

        .insight-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            background-color: rgba(228, 93, 34, 0.1);
            color: var(--color-primary);
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .insight-text {
            font-size: 14px;
            line-height: 1.6;
            color: var(--color-text);
            margin-bottom: 8px;
        }

        .insight-meta {
            font-size: 12px;
            color: var(--color-text-secondary);
        }

        @media (max-width: 1024px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .insights-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-wrap: wrap;
            }

            .topbar-left {
                flex: 1 0 100%;
            }

            .topbar-search {
                max-width: none;
            }

            .main-content {
                padding: 16px;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-logo">Zife</div>
                </div>

                <!-- Main Menu -->
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Menu</div>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="/admin/dashboard" class="sidebar-menu-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                                <span class="sidebar-icon">📊</span>
                                Dashboard
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="/admin/orders" class="sidebar-menu-link <?= $current_page === 'orders' ? 'active' : '' ?>">
                                <span class="sidebar-icon">📦</span>
                                Pedidos
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="/admin/menu" class="sidebar-menu-link <?= $current_page === 'menu' ? 'active' : '' ?>">
                                <span class="sidebar-icon">🍽</span>
                                Cardápio
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="/admin/payments" class="sidebar-menu-link <?= $current_page === 'payments' ? 'active' : '' ?>">
                                <span class="sidebar-icon">💳</span>
                                Financeiro
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="/admin/reports" class="sidebar-menu-link <?= $current_page === 'reports' ? 'active' : '' ?>">
                                <span class="sidebar-icon">📈</span>
                                Relatórios
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tools Menu -->
                <div class="sidebar-section">
                    <div class="sidebar-section-label">Ferramentas</div>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="/admin/tables" class="sidebar-menu-link <?= $current_page === 'tables' ? 'active' : '' ?>">
                                <span class="sidebar-icon">🎯</span>
                                Mesas
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="/admin/settings" class="sidebar-menu-link <?= $current_page === 'settings' ? 'active' : '' ?>">
                                <span class="sidebar-icon">⚙️</span>
                                Configurações
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Footer -->
                <div class="sidebar-footer">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <?= strtoupper(substr($restaurant['name'], 0, 1)) ?>
                        </div>
                        <div class="profile-info">
                            <div class="profile-name"><?= htmlspecialchars($restaurant['name']) ?></div>
                            <div class="profile-plan"><?= ucfirst($restaurant['plan']) ?></div>
                        </div>
                    </div>
                    <a href="/admin/logout" class="btn btn-secondary" style="width: 100%; text-align: center;">
                        Sair
                    </a>
                </div>
            </div>
        </aside>

        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div class="topbar">
                <div class="topbar-left">
                    <div class="topbar-search">
                        <input type="text" placeholder="Buscar pedidos, produtos, clientes..." />
                    </div>
                </div>
                <div class="topbar-right">
                    <button class="topbar-icon-btn" title="Ajuda">
                        ❓
                    </button>
                    <button class="topbar-icon-btn" title="Notificações">
                        🔔
                        <div class="topbar-badge">3</div>
                    </button>
                    <button class="topbar-icon-btn" title="Perfil">
                        👤
                    </button>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="admin-main">
            <div class="main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">
                        <?php
                        $titles = [
                            'dashboard' => 'Dashboard',
                            'orders' => 'Pedidos',
                            'menu' => 'Cardápio',
                            'payments' => 'Financeiro',
                            'reports' => 'Relatórios',
                            'tables' => 'Mesas',
                            'settings' => 'Configurações'
                        ];
                        echo $titles[$current_page] ?? 'Zife Order';
                        ?>
                    </h1>
                    <p class="page-subtitle">Gerenciar seu restaurante de forma inteligente e eficiente</p>
                </div>

                <!-- Stats Row (Dashboard only) -->
                <?php if ($current_page === 'dashboard'): ?>
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-label">Receita Hoje</div>
                        <div class="stat-value">R$ 4.820</div>
                        <div class="stat-change">+18% vs ontem</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Pedidos</div>
                        <div class="stat-value">124</div>
                        <div class="stat-change">+22% vs ontem</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Ticket Médio</div>
                        <div class="stat-value">R$ 38,70</div>
                        <div class="stat-change">-3% vs ontem</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Clientes</div>
                        <div class="stat-value">1.240</div>
                        <div class="stat-change positive">+8 novos</div>
                    </div>
                </div>

                <!-- Insights Row -->
                <div class="insights-grid">
                    <div class="insight-card">
                        <div class="insight-badge">🔥 TRENDING</div>
                        <div class="insight-text">Seu X-Burger vende 38% acima da média.</div>
                        <div class="insight-meta">Aumentar preço 5-10%?</div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-badge">⚠️ ALERTA</div>
                        <div class="insight-text">Estoque de carne acaba amanhã.</div>
                        <div class="insight-meta">Repor agora para evitar faltas</div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-badge">📈 OPORTUNIDADE</div>
                        <div class="insight-text">Sexta-feira terá alta demanda.</div>
                        <div class="insight-meta">Considere dobrar produção</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Content Placeholder -->
                <div class="card" style="padding: 40px; text-align: center; color: var(--color-text-secondary);">
                    Conteúdo da página <?= htmlspecialchars($current_page) ?> será carregado aqui.
                </div>
            </div>
        </main>
    </div>

    <script>
        // Highlight active menu item
        document.querySelectorAll('.sidebar-menu-link').forEach(link => {
            if (link.getAttribute('href') === window.location.pathname) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
