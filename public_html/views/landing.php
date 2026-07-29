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
