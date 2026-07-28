<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zife Order - Cardápio Digital para Restaurantes</title>
    <link rel="stylesheet" href="/public/css/landing.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky">
        <div class="container">
            <div class="navbar-content">
                <div class="logo">
                    <span class="logo-icon">🍽️</span>
                    <span class="logo-text">Zife Order</span>
                </div>
                <div class="nav-links">
                    <a href="#features">Recursos</a>
                    <a href="#how-it-works">Como Funciona</a>
                    <a href="#pricing">Preços</a>
                    <a href="#testimonials">Depoimentos</a>
                </div>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Cardápio Digital Profissional para Seu Restaurante</h1>
                <p class="hero-subtitle">Venda mais, reduza custos, gerencie pedidos em tempo real. Tudo em uma plataforma simples.</p>
                <div class="hero-cta">
                    <?php if (!isset($_SESSION['restaurant_id'])): ?>
                        <a href="/auth/register" class="btn btn-primary btn-large">Começar Grátis</a>
                        <a href="/app/menu" class="btn btn-secondary btn-large">Ver Demo</a>
                    <?php else: ?>
                        <a href="/admin/dashboard" class="btn btn-primary btn-large">Ir para Dashboard</a>
                    <?php endif; ?>
                </div>
                <div class="hero-mockup">
                    <div class="phone-frame">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">
                            <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=800&fit=crop" alt="Cardápio Digital">
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
                    <div class="value-icon">💰</div>
                    <h3>Preço Justo</h3>
                    <p>Plano Grátis com tudo que você precisa. Atualize quando crescer.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🔗</div>
                    <h3>Integrações</h3>
                    <p>Pagamento por Pix e cartão. Notificações em tempo real.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🎯</div>
                    <h3>Suporte</h3>
                    <p>Equipe pronta para ajudar no seu sucesso.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bento -->
    <section class="features" id="features">
        <div class="container">
            <h2>Recursos Completos</h2>
            <div class="bento-grid">
                <div class="bento-card bento-large">
                    <h3>Cardápio Digital</h3>
                    <p>Cardápio bonito e fácil de usar, com fotos dos pratos e disponibilidade em tempo real.</p>
                </div>
                <div class="bento-card">
                    <h3>Pedidos Online</h3>
                    <p>Delivery, mesa ou balcão. Tudo integrado.</p>
                </div>
                <div class="bento-card">
                    <h3>Pagamento Seguro</h3>
                    <p>Pix e cartão com segurança garantida.</p>
                </div>
                <div class="bento-card">
                    <h3>Dashboard Admin</h3>
                    <p>Gerencie pedidos, cardápio e relatórios em um só lugar.</p>
                </div>
                <div class="bento-card bento-large">
                    <h3>Notificações em Tempo Real</h3>
                    <p>Receba alertas de novos pedidos e atualizações de status ao vivo.</p>
                </div>
                <div class="bento-card">
                    <h3>Relatórios</h3>
                    <p>Veja o desempenho do seu negócio com dados detalhados.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2>Como Funciona em 3 Passos</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Cadastre seu Restaurante</h3>
                    <p>Registre-se em minutos e comece com o plano Grátis.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Suba seu Cardápio</h3>
                    <p>Adicione categorias, itens, fotos e preços facilmente.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Comece a Vender</h3>
                    <p>Compartilhe o link do seu cardápio e receba pedidos online.</p>
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
                        <li>✓ Cardápio digital</li>
                        <li>✓ Até 5 categorias</li>
                        <li>✓ Até 20 itens</li>
                        <li>✓ Até 50 pedidos/mês</li>
                        <li>✗ Suporte</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-secondary btn-full">Começar Agora</a>
                </div>
                <div class="pricing-card featured">
                    <div class="badge">Mais Escolhido</div>
                    <h3>Starter</h3>
                    <p class="price">R$ 49<span>/mês</span></p>
                    <ul class="features-list">
                        <li>✓ Tudo do Grátis</li>
                        <li>✓ Até 20 categorias</li>
                        <li>✓ Até 100 itens</li>
                        <li>✓ Até 500 pedidos/mês</li>
                        <li>✓ Suporte por email</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-primary btn-full">Começar Agora</a>
                </div>
                <div class="pricing-card">
                    <h3>Pro</h3>
                    <p class="price">R$ 99<span>/mês</span></p>
                    <ul class="features-list">
                        <li>✓ Tudo do Starter</li>
                        <li>✓ Ilimitado</li>
                        <li>✓ Suporte prioritário</li>
                        <li>✓ Relatórios avançados</li>
                        <li>✓ Integrações customizadas</li>
                    </ul>
                    <a href="/auth/register" class="btn btn-primary btn-full">Começar Agora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <h2>O que Dizem Sobre Nós</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p>"Aumentei minhas vendas online em 40% no primeiro mês. Recomendo muito!"</p>
                    <div class="author">
                        <strong>João Silva</strong>
                        <p>Dono de Pizzaria, São Paulo</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p>"Simples de usar, mas muito poderoso. Melhor custo-benefício do mercado."</p>
                    <div class="author">
                        <strong>Maria Santos</strong>
                        <p>Proprietária de Restaurante, Rio de Janeiro</p>
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
                <p>&copy; 2024 Zife Order. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="/public/js/landing.js"></script>
</body>
</html>
