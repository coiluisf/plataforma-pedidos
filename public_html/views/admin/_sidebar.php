<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Zife Order</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="/admin/dashboard" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
            <span class="icon icon-dashboard"></span>
            <span>Visão Geral</span>
        </a>
        <a href="/admin/orders" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'orders') !== false ? 'active' : ''; ?>">
            <span class="icon icon-orders"></span>
            <span>Pedidos</span>
        </a>
        <a href="/admin/menu" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'menu') !== false ? 'active' : ''; ?>">
            <span class="icon icon-menu"></span>
            <span>Cardápio</span>
        </a>
        <a href="/admin/payments" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'payments') !== false ? 'active' : ''; ?>">
            <span class="icon icon-payments"></span>
            <span>Pagamentos</span>
        </a>
        <a href="/admin/tables" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'tables') !== false ? 'active' : ''; ?>">
            <span class="icon icon-tables"></span>
            <span>Mesas</span>
        </a>
        <a href="/admin/reports" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : ''; ?>">
            <span class="icon icon-reports"></span>
            <span>Relatórios</span>
        </a>
        <a href="/admin/settings" class="nav-item <?php echo strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : ''; ?>">
            <span class="icon icon-settings"></span>
            <span>Configurações</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="/auth/logout" class="btn-logout">Sair</a>
    </div>
</aside>
