<?php

// Define environment (development, production)
define('ENVIRONMENT', getenv('APP_ENV') ?: 'production');

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'zife_order');

// Base paths
define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST']);
define('ROOT_PATH', dirname(__DIR__));

// Payment gateway configuration
define('MERCADO_PAGO_PUBLIC_KEY', getenv('MERCADO_PAGO_PUBLIC_KEY') ?: '');
define('MERCADO_PAGO_ACCESS_TOKEN', getenv('MERCADO_PAGO_ACCESS_TOKEN') ?: '');

// App configuration
define('APP_NAME', 'Zife Order');
define('APP_TIMEZONE', 'America/Sao_Paulo');

date_default_timezone_set(APP_TIMEZONE);

// Plans configuration
define('PLANS', [
    'free' => [
        'name' => 'Grátis',
        'price' => 0,
        'monthly_orders_limit' => 50,
        'features' => ['Cardápio digital', 'Até 5 categorias', 'Até 20 itens']
    ],
    'starter' => [
        'name' => 'Starter',
        'price' => 49,
        'monthly_orders_limit' => 500,
        'features' => ['Tudo do Grátis', 'Até 20 categorias', 'Até 100 itens', 'Suporte por email']
    ],
    'pro' => [
        'name' => 'Pro',
        'price' => 99,
        'monthly_orders_limit' => 9999,
        'features' => ['Tudo do Starter', 'Ilimitado', 'Suporte prioritário', 'Relatórios avançados']
    ]
]);

// Color scheme (from design tokens)
define('COLORS', [
    'primary' => '#E8491D',      // Chili orange
    'primary_hover' => '#C7380F',
    'cream' => '#FBEEE3',        // Background
    'dark' => '#1B1512',         // Text/base
    'secondary' => '#4A3E36',    // Secondary text
    'tertiary' => '#7A6A5E',
    'success' => '#4B7F52',
    'success_dark' => '#3A6640',
    'warning' => '#C98A1D',
    'warning_dark' => '#9C6B15',
]);

// Typography (from Google Fonts)
define('FONTS', [
    'title' => "'Bricolage Grotesque', sans-serif",
    'body' => "'Instrument Sans', sans-serif",
]);
