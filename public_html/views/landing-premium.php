<?php
require_once __DIR__ . '/../config/constants.php';
if (isset($_SESSION['restaurant_id'])) {
    header('Location: /admin/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zife Order - Cardápio Digital e Pedidos Online para Restaurantes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></link>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-white: #FFFFFF;
            --color-bg-light: #F8F9FB;
            --color-bg-lighter: #ECEEF2;
            --color-text: #111111;
            --color-text-secondary: #6B7280;
            --color-primary: #E45D22;
            --color-primary-hover: #C94A16;
            --color-border: #E5E7EB;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-family);
            color: var(--color-text);
            background: var(--color-white);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 1rem 2rem;
            transition: var(--transition);
            background: transparent;
            backdrop-filter: none;
        }

        nav.scrolled {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--color-text);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            color: var(--color-text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--color-text);
        }

        .nav-cta {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-left: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            gap: 8px;
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--color-primary-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover {
            background: var(--color-bg-light);
            color: var(--color-text);
            border-color: var(--color-bg-lighter);
        }

        .btn-lg {
            padding: 14px 28px;
            font-size: 16px;
        }

        /* Hero */
        section.hero {
            padding: 120px 2rem;
            margin-top: 60px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 56px;
            line-height: 1.2;
            font-weight: 800;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-content p {
            font-size: 18px;
            color: var(--color-text-secondary);
            margin-bottom: 2rem;
            max-width: 500px;
            line-height: 1.8;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 12px;
            background: var(--color-bg-lighter);
            border: 1px solid var(--color-border);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-ctas {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .hero-trust {
            font-size: 12px;
            color: var(--color-text-secondary);
            display: flex;
            gap: 1rem;
        }

        .hero-trust span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hero-trust svg {
            width: 16px;
            height: 16px;
            color: var(--color-primary);
        }

        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 500px;
        }

        .mockup-laptop {
            width: 100%;
            max-width: 500px;
            background: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .laptop-header {
            background: var(--color-bg-light);
            padding: 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            border-bottom: 1px solid var(--color-border);
        }

        .laptop-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--color-border);
        }

        .laptop-content {
            padding: 2rem;
            background: var(--color-white);
            height: 300px;
            position: relative;
            overflow: hidden;
        }

        .dashboard-mock {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            height: 100%;
        }

        .mock-card {
            background: var(--color-bg-light);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mock-card-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .mock-card-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-text);
        }

        .mock-small {
            font-size: 12px;
            color: var(--color-text-secondary);
            margin-top: 0.5rem;
        }

        /* Floating notifications */
        .floating-notif {
            position: absolute;
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .notif-1 {
            top: -30px;
            right: -50px;
            width: 200px;
        }

        .notif-2 {
            bottom: -20px;
            left: -60px;
            width: 180px;
            animation-delay: 0.5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        /* Social Proof */
        section.social-proof {
            padding: 4rem 2rem;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
            max-width: 1400px;
            margin: 0 auto;
        }

        .social-proof-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            text-align: center;
        }

        .social-proof-item h3 {
            font-size: 42px;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 0.5rem;
        }

        .social-proof-item p {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        /* Features Bento */
        section.features {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 42px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .section-subtitle {
            font-size: 16px;
            color: var(--color-text-secondary);
            text-align: center;
            margin-bottom: 4rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .bento-card {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 2rem;
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .bento-card:hover {
            border-color: var(--color-primary);
            box-shadow: 0 20px 40px rgba(228, 93, 34, 0.08);
            transform: translateY(-4px);
        }

        .bento-card.large {
            grid-column: span 2;
            grid-row: span 2;
        }

        .bento-card.tall {
            grid-row: span 2;
        }

        .bento-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .bento-card p {
            font-size: 14px;
            color: var(--color-text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .bento-preview {
            background: var(--color-bg-light);
            border-radius: 8px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--color-text-secondary);
            margin-top: 1rem;
        }

        .bento-card.large .bento-preview {
            height: 250px;
        }

        /* How it Works */
        section.how-it-works {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .timeline {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: 4rem;
        }

        .timeline-step {
            position: relative;
            padding-top: 3rem;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 40px;
            height: 40px;
            background: var(--color-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 16px;
        }

        .timeline-step:nth-child(1)::before {
            content: '01';
        }

        .timeline-step:nth-child(2)::before {
            content: '02';
        }

        .timeline-step:nth-child(3)::before {
            content: '03';
        }

        .timeline-step:nth-child(4)::before {
            content: '04';
        }

        .timeline-step h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .timeline-step p {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        /* Dashboard Preview */
        section.dashboard-preview {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: var(--color-bg-light);
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .dashboard-preview h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 1rem;
            text-align: center;
            letter-spacing: -1px;
        }

        .dashboard-preview > p {
            text-align: center;
            color: var(--color-text-secondary);
            margin-bottom: 3rem;
            font-size: 16px;
        }

        .dashboard-mockup {
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            margin-bottom: 3rem;
        }

        .dashboard-header {
            background: var(--color-bg-light);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--color-border);
        }

        .dashboard-header-left {
            display: flex;
            gap: 8px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--color-border);
        }

        .dashboard-header-middle {
            font-size: 12px;
            color: var(--color-text-secondary);
        }

        .dashboard-body {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 400px;
        }

        .dashboard-sidebar {
            background: var(--color-bg-light);
            padding: 20px;
            border-right: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .dashboard-menu-item {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: var(--transition);
        }

        .dashboard-menu-item:first-child {
            background: white;
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .dashboard-content {
            padding: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            align-content: start;
        }

        .dashboard-stat {
            background: var(--color-bg-light);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--color-border);
        }

        .dashboard-stat-label {
            font-size: 12px;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .dashboard-stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 0.5rem;
        }

        .dashboard-stat-change {
            font-size: 12px;
            color: var(--color-primary);
        }

        /* Benefits */
        section.benefits {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .benefit-card {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            padding: 2rem;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .benefit-card:hover {
            border-color: var(--color-primary);
            box-shadow: 0 20px 40px rgba(228, 93, 34, 0.08);
        }

        .benefit-icon {
            width: 40px;
            height: 40px;
            background: var(--color-bg-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--color-primary);
        }

        .benefit-card h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .benefit-card p {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        /* Testimonials */
        section.testimonials {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 4rem;
        }

        .testimonial-card {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            padding: 2rem;
            border-radius: var(--radius);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .testimonial-text {
            font-size: 14px;
            line-height: 1.8;
            color: var(--color-text);
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .testimonial-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .testimonial-info h5 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .testimonial-info p {
            font-size: 12px;
            color: var(--color-text-secondary);
        }

        /* Pricing */
        section.pricing {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .pricing-table {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 3rem;
        }

        .pricing-header {
            display: grid;
            grid-template-columns: 200px 1fr 1fr 1fr;
            gap: 0;
            background: var(--color-bg-light);
            border-bottom: 1px solid var(--color-border);
        }

        .pricing-header-cell {
            padding: 20px;
            font-weight: 700;
            font-size: 14px;
        }

        .pricing-header-cell:first-child {
            background: white;
        }

        .pricing-row {
            display: grid;
            grid-template-columns: 200px 1fr 1fr 1fr;
            border-bottom: 1px solid var(--color-border);
            align-items: center;
        }

        .pricing-row:last-child {
            border-bottom: none;
        }

        .pricing-row-label {
            padding: 20px;
            font-size: 14px;
            font-weight: 600;
            background: var(--color-bg-light);
        }

        .pricing-row-cell {
            padding: 20px;
            font-size: 14px;
            text-align: center;
            color: var(--color-text-secondary);
        }

        .pricing-row-cell.included {
            color: var(--color-primary);
            font-weight: 600;
        }

        .pricing-plan-card {
            text-align: center;
            padding: 30px 20px;
        }

        .pricing-plan-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .pricing-plan-price {
            font-size: 36px;
            font-weight: 800;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }

        .pricing-plan-price-period {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        .pricing-plan-cta {
            margin-top: 1.5rem;
        }

        .pricing-plan-featured {
            background: var(--color-bg-light);
            border: 2px solid var(--color-primary);
            border-radius: 12px;
            position: relative;
        }

        .pricing-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--color-primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* CTA Final */
        section.cta-final {
            padding: 6rem 2rem;
            background: #1A1A1A;
            color: white;
            text-align: center;
        }

        .cta-final h2 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .cta-final p {
            font-size: 18px;
            color: #999;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-final .btn {
            margin-top: 1rem;
            padding: 16px 40px;
            font-size: 16px;
        }

        /* Footer */
        footer {
            background: #0F0F0F;
            color: white;
            padding: 4rem 2rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-column h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column a {
            color: #999;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .footer-column a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-visual {
                height: auto;
                margin-top: 2rem;
            }

            .social-proof-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bento-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bento-card.large {
                grid-column: span 1;
                grid-row: span 1;
            }

            .timeline {
                grid-template-columns: repeat(2, 1fr);
            }

            .benefits-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .section-title {
                font-size: 36px;
            }

            .hero-content h1 {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-content h1 {
                font-size: 36px;
            }

            .hero-ctas {
                flex-direction: column;
            }

            .bento-grid {
                grid-template-columns: 1fr;
            }

            .social-proof-grid {
                grid-template-columns: 1fr;
            }

            .timeline {
                grid-template-columns: 1fr;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .pricing-header,
            .pricing-row {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 28px;
            }

            .cta-final h2 {
                font-size: 36px;
            }
        }

        /* Scroll reveal animations */
        [data-reveal] {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-reveal].revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Loading animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="/" class="nav-logo">Zife Order</a>
            <ul class="nav-links">
                <li><a href="#recursos">Recursos</a></li>
                <li><a href="#como-funciona">Como funciona</a></li>
                <li><a href="#planos">Planos</a></li>
                <li><a href="#contato">Contato</a></li>
                <li><a href="/auth/login">Entrar</a></li>
            </ul>
            <div class="nav-cta">
                <a href="/auth/register" class="btn btn-primary">Começar grátis</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero" data-reveal>
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-badge">Nova geração de cardápios digitais</div>
                <h1>Controle pedidos. Venda online. Sem depender do iFood.</h1>
                <p>Painel administrativo intuitivo, pagamentos em tempo real e integração com todos os canais de venda.</p>

                <div class="hero-ctas">
                    <a href="/auth/register" class="btn btn-primary btn-lg">Começar grátis</a>
                    <a href="#demo" class="btn btn-secondary btn-lg">Ver demonstração</a>
                </div>

                <div class="hero-trust">
                    <span>
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        Sem cartão de crédito
                    </span>
                    <span>
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        7 dias grátis
                    </span>
                    <span>
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                        Cancelamento imediato
                    </span>
                </div>
            </div>

            <div class="hero-visual">
                <div class="mockup-laptop">
                    <div class="laptop-header">
                        <div class="laptop-dot"></div>
                        <div class="laptop-dot"></div>
                        <div class="laptop-dot"></div>
                    </div>
                    <div class="laptop-content">
                        <div class="dashboard-mock">
                            <div class="mock-card">
                                <div>
                                    <div class="mock-card-title">Pedidos hoje</div>
                                    <div class="mock-card-value">24</div>
                                </div>
                                <div class="mock-small">+12% vs ontem</div>
                            </div>
                            <div class="mock-card">
                                <div>
                                    <div class="mock-card-title">Faturamento</div>
                                    <div class="mock-card-value">R$ 1.240</div>
                                </div>
                                <div class="mock-small">+8% vs ontem</div>
                            </div>
                            <div class="mock-card">
                                <div>
                                    <div class="mock-card-title">Taxa média</div>
                                    <div class="mock-card-value">R$ 52</div>
                                </div>
                                <div class="mock-small">Ticket médio</div>
                            </div>
                            <div class="mock-card">
                                <div>
                                    <div class="mock-card-title">Clientes novos</div>
                                    <div class="mock-card-value">8</div>
                                </div>
                                <div class="mock-small">+3 vs ontem</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="floating-notif notif-1">
                    <strong style="color: var(--color-primary);">✓</strong> Pedido #2847 confirmado
                </div>
                <div class="floating-notif notif-2">
                    PIX recebido: R$ 145,50
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="social-proof" data-reveal>
        <div class="social-proof-grid">
            <div class="social-proof-item">
                <h3>300+</h3>
                <p>Restaurantes atendidos</p>
            </div>
            <div class="social-proof-item">
                <h3>95%</h3>
                <p>Taxa de conclusão de pedidos</p>
            </div>
            <div class="social-proof-item">
                <h3>R$ 12M</h3>
                <p>Processados via plataforma</p>
            </div>
            <div class="social-proof-item">
                <h3>4.9★</h3>
                <p>Satisfação dos clientes</p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="recursos" data-reveal>
        <h2 class="section-title">Funcionalidades premium</h2>
        <p class="section-subtitle">Tudo que você precisa para vender online de forma profissional</p>

        <div class="bento-grid">
            <div class="bento-card large">
                <h3>Dashboard em tempo real</h3>
                <p>Visualize todas as suas vendas, pedidos e métricas importantes em um único painel.</p>
                <div class="bento-preview" style="height: 250px; background: linear-gradient(135deg, var(--color-bg-light) 0%, var(--color-bg-lighter) 100%);"></div>
            </div>

            <div class="bento-card">
                <h3>Gerenciador de cardápio</h3>
                <p>Organize produtos em categorias, defina preços e configure personalizações.</p>
                <div class="bento-preview"></div>
            </div>

            <div class="bento-card tall">
                <h3>QR Code integrado</h3>
                <p>Compartilhe um único QR Code que leva ao seu cardápio online e receba pedidos das mesas.</p>
                <div class="bento-preview"></div>
            </div>

            <div class="bento-card">
                <h3>Pagamentos seguros</h3>
                <p>PIX, cartão de crédito e débito. Integração automática com Mercado Pago.</p>
                <div class="bento-preview"></div>
            </div>

            <div class="bento-card">
                <h3>Controle de pedidos</h3>
                <p>Fluxo automático: recebimento, preparação, saída e entrega. Histórico completo.</p>
                <div class="bento-preview"></div>
            </div>

            <div class="bento-card">
                <h3>Relatórios avançados</h3>
                <p>Analise vendas por horário, produto, categoria e cliente. Gráficos em tempo real.</p>
                <div class="bento-preview"></div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section class="how-it-works" id="como-funciona" data-reveal>
        <h2 class="section-title">4 passos para começar</h2>
        <p class="section-subtitle">Simples, rápido e sem complicações</p>

        <div class="timeline">
            <div class="timeline-step">
                <h4>Cadastre seu restaurante</h4>
                <p>Crie sua conta em 2 minutos. Basta email e senha. Sem documentação complicada.</p>
            </div>
            <div class="timeline-step">
                <h4>Monte seu cardápio</h4>
                <p>Adicione seus produtos com fotos, descrições e preços. Organize em categorias.</p>
            </div>
            <div class="timeline-step">
                <h4>Comece a receber pedidos</h4>
                <p>Compartilhe o link ou QR Code. Seus clientes pedem e você recebe em tempo real.</p>
            </div>
            <div class="timeline-step">
                <h4>Receba pelo PIX</h4>
                <p>Pagamento automático na sua conta. Sem intermediários. Sem taxa por pedido.</p>
            </div>
        </div>
    </section>

    <!-- Dashboard Preview -->
    <section class="dashboard-preview" id="demo" data-reveal>
        <h2>Seu painel de controle</h2>
        <p>Gerenciar seu restaurante nunca foi tão simples</p>

        <div class="dashboard-mockup">
            <div class="dashboard-header">
                <div class="dashboard-header-left">
                    <div class="dot" style="background: #E45D22;"></div>
                    <div class="dot" style="background: #FFC107;"></div>
                    <div class="dot" style="background: #4CAF50;"></div>
                </div>
                <div class="dashboard-header-middle">zife-order.com/admin/dashboard</div>
                <div></div>
            </div>
            <div class="dashboard-body">
                <div class="dashboard-sidebar">
                    <div class="dashboard-menu-item">Dashboard</div>
                    <div class="dashboard-menu-item">Pedidos</div>
                    <div class="dashboard-menu-item">Cardápio</div>
                    <div class="dashboard-menu-item">Mesas</div>
                    <div class="dashboard-menu-item">Relatórios</div>
                    <div class="dashboard-menu-item">Pagamentos</div>
                    <div class="dashboard-menu-item">Clientes</div>
                    <div class="dashboard-menu-item">Configurações</div>
                </div>
                <div class="dashboard-content">
                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Pedidos hoje</div>
                        <div class="dashboard-stat-value">24</div>
                        <div class="dashboard-stat-change">+12% vs ontem</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Faturamento</div>
                        <div class="dashboard-stat-value">R$ 1.240</div>
                        <div class="dashboard-stat-change">+8% vs ontem</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Taxa média</div>
                        <div class="dashboard-stat-value">R$ 52</div>
                        <div class="dashboard-stat-change">Ticket médio</div>
                    </div>
                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Clientes novos</div>
                        <div class="dashboard-stat-value">8</div>
                        <div class="dashboard-stat-change">+3 vs ontem</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="benefits" data-reveal>
        <h2 class="section-title">Tudo incluído</h2>
        <p class="section-subtitle">Sem custos ocultos. Sem taxas surpresa.</p>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4" stroke="currentColor"/>
                    </svg>
                </div>
                <h4>Sem comissão</h4>
                <p>Você fica com 100% dos seus pedidos. Sem taxa de intermediação.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <h4>Seu domínio</h4>
                <p>Customize com seu logo e cores. Criando marca própria do seu restaurante.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <h4>PIX integrado</h4>
                <p>Receba pagamentos instantaneamente. Sem esperar por taxas bancárias.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                    </svg>
                </div>
                <h4>QR Code único</h4>
                <p>Disponibilize nas mesas. Seus clientes acessam o cardápio em segundos.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"/>
                    </svg>
                </div>
                <h4>Delivery integrado</h4>
                <p>Gerenciador de rotas, status de entrega e notificações em tempo real.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h4>Suporte 24/7</h4>
                <p>Equipe pronta para ajudar. Chat, email e telefone disponíveis.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" data-reveal>
        <h2 class="section-title">Que dizem sobre nós</h2>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-text">"Aumentamos em 40% as vendas no primeiro mês. Zife Order é realmente simples de usar e o suporte é impecável."</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">JP</div>
                    <div class="testimonial-info">
                        <h5>João Paula</h5>
                        <p>Restaurante Sabor da Casa</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-text">"Não preciso mais depender do iFood. Meus clientes acessam direto pelo QR Code na mesa e fazem pedidos com facilidade."</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">MC</div>
                    <div class="testimonial-info">
                        <h5>Marina Costa</h5>
                        <p>Lanchonete Do Bairro</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-text">"A integração com PIX foi o diferencial. Recebo pagamento na hora e sem taxa. Voltei a gostar de trabalhar com delivery."</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">RS</div>
                    <div class="testimonial-info">
                        <h5>Roberto Silva</h5>
                        <p>Pizzaria Premium</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="pricing" id="planos" data-reveal>
        <h2 class="section-title">Planos simples</h2>
        <p class="section-subtitle">Escolha o plano certo para seu restaurante</p>

        <div class="pricing-table">
            <div class="pricing-header">
                <div class="pricing-header-cell">Recurso</div>
                <div class="pricing-header-cell">Grátis</div>
                <div class="pricing-header-cell" style="background: var(--color-bg-light);">Starter</div>
                <div class="pricing-header-cell">Pro</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Pedidos/mês</div>
                <div class="pricing-row-cell">Até 50</div>
                <div class="pricing-row-cell" style="background: var(--color-bg-light);">Até 500</div>
                <div class="pricing-row-cell">Ilimitados</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Categorias</div>
                <div class="pricing-row-cell">5</div>
                <div class="pricing-row-cell" style="background: var(--color-bg-light);">20</div>
                <div class="pricing-row-cell">Ilimitadas</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Produtos</div>
                <div class="pricing-row-cell">20</div>
                <div class="pricing-row-cell" style="background: var(--color-bg-light);">100</div>
                <div class="pricing-row-cell">Ilimitados</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">QR Code</div>
                <div class="pricing-row-cell included">✓</div>
                <div class="pricing-row-cell included" style="background: var(--color-bg-light);">✓</div>
                <div class="pricing-row-cell included">✓</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">PIX</div>
                <div class="pricing-row-cell included">✓</div>
                <div class="pricing-row-cell included" style="background: var(--color-bg-light);">✓</div>
                <div class="pricing-row-cell included">✓</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Relatórios</div>
                <div class="pricing-row-cell">Básicos</div>
                <div class="pricing-row-cell" style="background: var(--color-bg-light);">Avançados</div>
                <div class="pricing-row-cell included">✓</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Suporte</div>
                <div class="pricing-row-cell">Email</div>
                <div class="pricing-row-cell" style="background: var(--color-bg-light);">Email</div>
                <div class="pricing-row-cell">Prioritário</div>
            </div>

            <div class="pricing-row">
                <div class="pricing-row-label">Preço</div>
                <div class="pricing-plan-card">
                    <div class="pricing-plan-price">R$ 0</div>
                    <a href="/auth/register?plan=free" class="btn btn-secondary">Começar</a>
                </div>
                <div class="pricing-plan-card pricing-plan-featured">
                    <div class="pricing-badge">Mais escolhido</div>
                    <div class="pricing-plan-price">R$ 49</div>
                    <div class="pricing-plan-price-period">/mês</div>
                    <a href="/auth/register?plan=starter" class="btn btn-primary">Começar</a>
                </div>
                <div class="pricing-plan-card">
                    <div class="pricing-plan-price">R$ 99</div>
                    <div class="pricing-plan-price-period">/mês</div>
                    <a href="/auth/register?plan=pro" class="btn btn-primary">Começar</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="cta-final" data-reveal>
        <h2>Pronto para vender mais todos os dias?</h2>
        <p>Seu restaurante merece uma plataforma profissional. Comece agora, sem cartão de crédito.</p>
        <a href="/auth/register" class="btn btn-primary btn-lg">Começar gratuitamente</a>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h4>Zife Order</h4>
                    <p style="font-size: 14px; color: #999; margin-bottom: 1.5rem;">Cardápio digital e pedidos online para restaurantes modernos.</p>
                </div>
                <div class="footer-column">
                    <h4>Produto</h4>
                    <ul>
                        <li><a href="#planos">Planos</a></li>
                        <li><a href="#recursos">Recursos</a></li>
                        <li><a href="#como-funciona">Como funciona</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="#">Sobre</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Carreiras</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacidade</a></li>
                        <li><a href="#">Termos</a></li>
                        <li><a href="#">Contato</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Zife Order. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Scroll reveal animation
        const revealElements = document.querySelectorAll('[data-reveal]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, { threshold: 0.1 });

        revealElements.forEach(el => observer.observe(el));
    </script>
</body>
</html>
