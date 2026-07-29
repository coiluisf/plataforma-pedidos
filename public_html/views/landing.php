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
    <title>Zife Order - Cardápio Digital para Restaurantes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <link rel="stylesheet" href="/assets/css/landing.css">
</head>
<body>
    <!-- NAV STICKY -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <h2>Zife Order</h2>
            </div>
            <a href="/auth/login" class="btn btn-primary">Testar grátis</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>Cardápio Digital + Pedidos Online</h1>
            <p>Aumente suas vendas com um cardápio online profissional. Receba pedidos de delivery, mesa e balcão tudo em um lugar.</p>
            <div class="hero-ctas">
                <a href="/auth/register" class="btn btn-primary btn-lg">Começar Grátis</a>
                <a href="#planos" class="btn btn-secondary btn-lg">Ver Planos</a>
            </div>
        </div>
        <div class="hero-mockup">
            <div class="phone-frame">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=600&fit=crop" alt="App Mockup">
            </div>
        </div>
    </section>

    <!-- TIRA DE VALOR -->
    <section class="value-strip">
        <div class="value-item">
            <div class="value-icon">💰</div>
            <h4>Preço Justo</h4>
            <p>A partir de R$ 0 com nosso plano grátis</p>
        </div>
        <div class="value-item">
            <div class="value-icon">🔗</div>
            <h4>Integrado</h4>
            <p>Conecte com Mercado Pago, Pix e mais</p>
        </div>
        <div class="value-item">
            <div class="value-icon">🤝</div>
            <h4>Suporte</h4>
            <p>Equipe pronta para ajudar seu sucesso</p>
        </div>
    </section>

    <!-- BENTO DE FEATURES (6 blocos) -->
    <section class="features-bento">
        <h2>Tudo que seu restaurante precisa</h2>
        <div class="bento-grid">
            <div class="bento-card">
                <h3>Cardápio Digital</h3>
                <p>Organize seus itens em categorias, com fotos profissionais e preços atualizáveis em tempo real.</p>
            </div>
            <div class="bento-card">
                <h3>Pedidos Online</h3>
                <p>Receba pedidos 24/7 de delivery, mesa e balcão.</p>
            </div>
            <div class="bento-card">
                <h3>Pagamento Seguro</h3>
                <p>Integrado com Mercado Pago, Pix e cartão.</p>
            </div>
            <div class="bento-card">
                <h3>Relatórios</h3>
                <p>Veja seus melhores produtos e horários de pico.</p>
            </div>
            <div class="bento-card">
                <h3>Mesas com QR Code</h3>
                <p>Clientes escanéiam e pedem direto da mesa.</p>
            </div>
            <div class="bento-card">
                <h3>Painel Admin Intuitivo</h3>
                <p>Controle tudo de um dashboard simples: pedidos, cardápio, pagamentos, configurações.</p>
            </div>
        </div>
    </section>

    <!-- COMO FUNCIONA (3 PASSOS) -->
    <section class="how-it-works">
        <h2>Como Funciona</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Criar Conta</h4>
                <p>Registre seu restaurante em 2 minutos</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Adicionar Cardápio</h4>
                <p>Envie seus produtos com fotos e preços</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Começar a Vender</h4>
                <p>Compartilhe o link ou QR code com clientes</p>
            </div>
        </div>
    </section>

    <!-- PREÇOS (3 PLANOS) -->
    <section id="planos" class="pricing">
        <h2>Planos Simples e Transparentes</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Grátis</h3>
                <div class="price">R$ 0<span>/mês</span></div>
                <ul class="features-list">
                    <li>✓ Até 50 pedidos/mês</li>
                    <li>✓ 5 categorias</li>
                    <li>✓ 20 itens no cardápio</li>
                    <li>✓ Cardápio digital</li>
                </ul>
                <a href="/auth/register" class="btn btn-secondary btn-lg">Começar Grátis</a>
            </div>

            <div class="pricing-card pricing-featured">
                <div class="badge-featured">Mais Escolhido</div>
                <h3>Starter</h3>
                <div class="price">R$ 49<span>/mês</span></div>
                <ul class="features-list">
                    <li>✓ Até 500 pedidos/mês</li>
                    <li>✓ 20 categorias</li>
                    <li>✓ 100 itens no cardápio</li>
                    <li>✓ Tudo do Grátis</li>
                    <li>✓ Suporte por email</li>
                </ul>
                <a href="/auth/register" class="btn btn-primary btn-lg">Começar Agora</a>
            </div>

            <div class="pricing-card">
                <h3>Pro</h3>
                <div class="price">R$ 99<span>/mês</span></div>
                <ul class="features-list">
                    <li>✓ Pedidos ilimitados</li>
                    <li>✓ Categorias ilimitadas</li>
                    <li>✓ Itens ilimitados</li>
                    <li>✓ Tudo do Starter</li>
                    <li>✓ Suporte prioritário</li>
                    <li>✓ Relatórios avançados</li>
                </ul>
                <a href="/auth/register" class="btn btn-secondary btn-lg">Começar Agora</a>
            </div>
        </div>
    </section>

    <!-- DEPOIMENTOS (2 CARDS) -->
    <section class="testimonials">
        <h2>O que Nossos Clientes Dizem</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <p>"Aumentei meus pedidos em 40% no primeiro mês. Zife Order é simples de usar!"</p>
                <strong>João Silva</strong>
                <small>Restaurante Sabor da Casa</small>
            </div>
            <div class="testimonial-card">
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <p>"Meus clientes adoram o cardápio digital. Não consigo viver sem!"</p>
                <strong>Marina Costa</strong>
                <small>Lanchonete Do Bairro</small>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section class="cta-final">
        <h2>Pronto para Aumentar suas Vendas?</h2>
        <p>Comece grátis, sem cartão de crédito.</p>
        <a href="/auth/register" class="btn btn-primary btn-lg">Criar Conta Grátis Agora</a>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Zife Order</h4>
                <p>Cardápio digital para restaurantes modernos.</p>
            </div>
            <div class="footer-section">
                <h4>Produto</h4>
                <ul>
                    <li><a href="#planos">Planos</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Empresa</h4>
                <ul>
                    <li><a href="#">Sobre</a></li>
                    <li><a href="#">Contato</a></li>
                    <li><a href="#">Suporte</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Zife Order. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
            padding: 16px 0;
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid #E0D5CA;
            font-size: 16px;
        }
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 998;
        }
        .mobile-menu-overlay.open {
            display: block;
        }
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky">
        <div class="container">
            <div class="navbar-content">
                <div class="logo">
                    <span class="logo-text">Zife Order</span>
                </div>
                <div class="nav-links">
                    <a href="#features">Recursos</a>
                    <a href="#how-it-works">Como Funciona</a>
                    <a href="#pricing">Preços</a>
                    <a href="#testimonials">Depoimentos</a>
                </div>
                <button class="mobile-menu-toggle" onclick="openMobileMenu()">☰</button>
                <div class="nav-cta">
                    <?php if (isset($_SESSION['restaurant_id'])): ?>
                        <a href="/admin/dashboard" class="btn btn-primary">Painel Admin</a>
                        <a href="/auth/logout" class="btn btn-secondary">Sair</a>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-primary">Testar Grátis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" onclick="closeMobileMenu()">×</button>
        <a href="#features" onclick="closeMobileMenu()">Recursos</a>
        <a href="#how-it-works" onclick="closeMobileMenu()">Como Funciona</a>
        <a href="#pricing" onclick="closeMobileMenu()">Preços</a>
        <a href="#testimonials" onclick="closeMobileMenu()">Depoimentos</a>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Solução Digital Completa para Seu Restaurante</h1>
                <p class="hero-subtitle">Transforme seu cardápio em uma máquina de vendas. Gerenciamento de pedidos, pagamentos online e relatórios em tempo real, tudo integrado.</p>
                <div class="hero-cta">
                    <?php if (!isset($_SESSION['restaurant_id'])): ?>
                        <a href="/auth/register" class="btn btn-primary btn-large">Começar Grátis</a>
                        <a href="/app/menu?restaurant=1" class="btn btn-secondary btn-large">Ver Demo</a>
                    <?php else: ?>
                        <a href="/admin/dashboard" class="btn btn-primary btn-large">Acessar Painel</a>
                    <?php endif; ?>
                </div>
                <div class="hero-mockup">
                    <div class="phone-frame">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">
                            <div class="phone-content">
                                <div class="phone-header">
                                    <span class="phone-time">9:41</span>
                                </div>
                                <div class="phone-menu">
                                    <div class="menu-header">
                                        <h2>Seu Cardápio</h2>
                                    </div>
                                    <div class="menu-categories">
                                        <div class="category-pill active">Todos</div>
                                        <div class="category-pill">Prato Principal</div>
                                        <div class="category-pill">Bebidas</div>
                                    </div>
                                    <div class="menu-items">
                                        <div class="menu-item">
                                            <div class="item-image" style="background: linear-gradient(135deg, #C98A1D 0%, #E8491D 100%);"></div>
                                            <div class="item-info">
                                                <h3>Prato Especial</h3>
                                                <p class="item-price">R$ 38,90</p>
                                            </div>
                                        </div>
                                        <div class="menu-item">
                                            <div class="item-image" style="background: linear-gradient(135deg, #4B7F52 0%, #6BA873 100%);"></div>
                                            <div class="item-info">
                                                <h3>Bebida Refrescante</h3>
                                                <p class="item-price">R$ 8,90</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Proposition -->
    <section class="value-props">
        <div class="container">
            <div class="value-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5m-5 6h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <h3>Preço Justo</h3>
                    <p>Comece grátis, sem cartão de crédito. Pagamento transparente e sem surpresas quando precisar escalar.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83M22 4l-7 7 2.12-7z"/>
                        </svg>
                    </div>
                    <h3>Integração Completa</h3>
                    <p>Pix e cartão integrados. Pagamentos instantâneos e notificações automáticas de pedidos em tempo real.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="1"/><path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24M1 12h6M17 12h6M4.22 19.78l4.24-4.24M15.54 8.46l4.24-4.24"/>
                        </svg>
                    </div>
                    <h3>Sempre Online</h3>
                    <p>Infraestrutura confiável que funciona 24/7. Seu cardápio disponível quando seus clientes precisarem.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bento -->
    <section class="features" id="features">
        <div class="container">
            <h2>Tudo que Você Precisa</h2>
            <div class="bento-grid">
                <div class="bento-card bento-large">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12" y2="18.01"/>
                        </svg>
                    </div>
                    <h3>Cardápio Mobile-First</h3>
                    <p>Interface otimizada para celular que seus clientes acessam direto do navegador. Sem app para baixar, sem complicações.</p>
                </div>
                <div class="bento-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                    </div>
                    <h3>Carrinho Inteligente</h3>
                    <p>Adicionar itens é simples e rápido. Salvo automaticamente no celular.</p>
                </div>
                <div class="bento-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    <h3>Pagamento Integrado</h3>
                    <p>Pix ou cartão. Seguro e instantâneo com Mercado Pago.</p>
                </div>
                <div class="bento-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <h3>Painel Administrativo</h3>
                    <p>Controle total: pedidos, cardápio, pagamentos e relatórios em um único lugar.</p>
                </div>
                <div class="bento-card bento-large">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3>Gerenciamento de Pedidos em Tempo Real</h3>
                    <p>Kanban visual com arrastar e soltar. Mova pedidos de novo para pronto enquanto seus clientes acompanham o status em tempo real.</p>
                </div>
                <div class="bento-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
                        </svg>
                    </div>
                    <h3>Dados e Analytics</h3>
                    <p>Gráficos detalhados de vendas, itens mais populares e métricas de desempenho.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2>Processo Simples</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Registre seu Restaurante</h3>
                    <p>Crie sua conta em 2 minutos. Sem cartão de crédito necessário. Comece com o plano Grátis incluindo 5 categorias e até 20 itens.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Configure seu Cardápio</h3>
                    <p>Adicione seus pratos com nome, descrição, preço e foto. Interface intuitiva que qualquer um pode usar. Ative ou desative itens quando quiser.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Receba Pedidos</h3>
                    <p>Compartilhe um link com seus clientes. Eles acessam pelo celular, fazem o pedido, pagam online. Você recebe em tempo real no seu painel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="pricing" id="pricing">
        <div class="container">
            <h2>Planos Simples e Transparentes</h2>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Grátis</h3>
                    <p class="price">R$ 0<span>/mês</span></p>
                    <ul class="features-list">
                        <li>Cardápio digital completo</li>
                        <li>Até 5 categorias</li>
                        <li>Até 20 itens</li>
                        <li>Até 50 pedidos/mês</li>
                        <li class="unavailable">Suporte exclusivo</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-secondary btn-full">Começar Agora</a>
                </div>
                <div class="pricing-card featured">
                    <div class="badge">Mais Escolhido</div>
                    <h3>Starter</h3>
                    <p class="price">R$ 49<span>/mês</span></p>
                    <ul class="features-list">
                        <li>Tudo do plano Grátis</li>
                        <li>Até 20 categorias</li>
                        <li>Até 100 itens</li>
                        <li>Até 500 pedidos/mês</li>
                        <li>Suporte por email</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-primary btn-full">Começar Agora</a>
                </div>
                <div class="pricing-card">
                    <h3>Pro</h3>
                    <p class="price">R$ 99<span>/mês</span></p>
                    <ul class="features-list">
                        <li>Tudo do plano Starter</li>
                        <li>Itens e categorias ilimitados</li>
                        <li>Suporte prioritário 24/7</li>
                        <li>Relatórios avançados</li>
                        <li>Integrações customizadas</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-primary btn-full">Começar Agora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2>Resultado Real de Clientes</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">
                        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                    </div>
                    <p class="testimonial-text">"Recebo os pedidos em tempo real no meu telefone, consigo gerenciar tudo da cozinha. Meus clientes amam a facilidade de encomendar pelo celular."</p>
                    <div class="author">
                        <strong>Restaurante Bom Paladar</strong>
                        <p>Praça da República, São Paulo</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">
                        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                    </div>
                    <p class="testimonial-text">"A implementação foi rápida, o painel é intuitivo e o suporte responde rápido. Já aumentei meu cardápio e estou usando o plano Starter."</p>
                    <div class="author">
                        <strong>Pizzaria Da Nonna</strong>
                        <p>Vila Mariana, São Paulo</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <div class="container">
            <h2>Pronto para Transformar Seu Restaurante?</h2>
            <p>Teste grátis. Sem cartão de crédito.</p>
            <?php if (!isset($_SESSION['restaurant_id'])): ?>
                <a href="/auth/register" class="btn btn-primary btn-large">Registrar Agora</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Zife Order</h4>
                    <p>Cardápio digital para restaurantes modernos.</p>
                </div>
                <div class="footer-section">
                    <h4>Links</h4>
                    <ul>
                        <li><a href="#features">Recursos</a></li>
                        <li><a href="#pricing">Preços</a></li>
                        <li><a href="#testimonials">Depoimentos</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacidade</a></li>
                        <li><a href="#">Termos de Serviço</a></li>
                        <li><a href="#">Contato</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <span id="year"></span> Zife Order. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Atualizar ano do copyright automaticamente
        document.getElementById('year').textContent = new Date().getFullYear();

        function openMobileMenu() {
            document.getElementById('mobileMenu').classList.add('open');
            document.getElementById('mobileMenuOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('open');
            document.getElementById('mobileMenuOverlay').classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        // Fechar menu ao clicar em um link
        document.querySelectorAll('.mobile-menu a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
    </script>
    <script src="/public/js/landing.js"></script>
</body>
</html>
