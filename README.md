# 🍽️ Zife Order - Cardápio Digital + Plataforma de Pedidos

**Zife Order** é uma plataforma SaaS completa de cardápio digital para pequenos e médios restaurantes. Permite que donos de restaurantes criem, gerenciem e vendam seus cardápios online com pedidos, pagamento por Pix/Cartão e um painel administrativo intuitivo.

---

## ✨ Recursos Principais

### 🛍️ Para Clientes
- **Cardápio Digital Responsivo**: App mobile-first em browser (sem instalação)
- **Múltiplos Tipos de Pedido**: Delivery, mesa (dine-in), balcão
- **Pagamento Seguro**: Pix + Cartão de Crédito (Mercado Pago/PagSeguro)
- **Rastreamento em Tempo Real**: Status do pedido com ETA
- **Carrinho Inteligente**: Salvo no navegador (localStorage)

### 🏪 Para Restaurantes (Admin)
- **Dashboard em Tempo Real**: Visão geral de pedidos, faturamento, estatísticas
- **Gerenciamento de Cardápio**: CRUD de categorias, itens, preços, fotos
- **Sistema Kanban de Pedidos**: Arrastar pedidos entre Novo → Preparando → Pronto → Entregue
- **Relatórios de Vendas**: Gráficos de revenue, top itens, histórico 7 dias
- **Configurações de Loja**: Nome, endereço, horário, descrição
- **Gerenciamento de Pagamentos**: Ativar/desativar Pix e Cartão, listar transações
- **Gestão de Mesas**: QR Codes gerados automaticamente para dine-in

### 💰 Planos de Assinatura
- **Grátis**: 5 categorias, 20 itens, 50 pedidos/mês, sem suporte
- **Starter (R$49/mês)**: 20 categorias, 100 itens, 500 pedidos/mês, suporte por email
- **Pro (R$99/mês)**: Ilimitado, relatórios avançados, suporte prioritário

---

## 🏗️ Arquitetura & Stack

### Backend
- **PHP 7.4+** (puro, sem framework pesado - para compatibilidade com hospedagem compartilhada)
- **MySQL 5.7+**
- **PDO** para queries preparadas e segurança

### Frontend
- **HTML5 + CSS3 + Vanilla JavaScript** (sem dependencies)
- **Google Fonts** (Bricolage Grotesque, Instrument Sans)
- **Chart.js** para gráficos
- **Responsive Design**: Mobile-first, desktop-friendly

### Payment Gateway
- **Mercado Pago** (Pix + Cartão)
- **PagSeguro** (alternativo, não implementado ainda)
- **Webhooks** para notificações de pagamento

### Hospedagem
- **Hostinger Single Web Hosting** (PHP + MySQL nativo)
- **FTP/SFTP** para deploy
- **phpMyAdmin** para gerenciamento de BD

---

## 📁 Estrutura de Diretórios

```
plataforma-pedidos/
├── public_html/                 # Raiz do web server
│   ├── index.php                # Router principal
│   ├── .htaccess                # Regras Apache (mod_rewrite)
│   ├── .env.example             # Template de variáveis de ambiente
│   ├── config/
│   │   ├── constants.php        # Constantes (cores, fonts, planos)
│   │   └── database.php         # Classe PDO Database
│   ├── sql/
│   │   └── schema.sql           # Schema MySQL (10 tabelas)
│   ├── src/                     # (Reservado para helpers no futuro)
│   ├── api/
│   │   ├── auth.php             # Login/Register
│   │   ├── menu.php             # CRUD cardápio
│   │   ├── orders.php           # Criar pedido, atualizar status
│   │   ├── payments.php         # Webhook Mercado Pago
│   │   └── admin.php            # Dashboard stats, settings
│   ├── public/
│   │   ├── css/
│   │   │   ├── landing.css      # Landing page
│   │   │   ├── admin.css        # Painel admin
│   │   │   └── app.css          # App mobile (se necessário)
│   │   ├── js/
│   │   │   ├── landing.js       # Interações landing
│   │   │   ├── admin.js         # Admin utils
│   │   │   └── api-client.js    # Fetch helper
│   │   └── images/              # Assets estáticos
│   ├── views/
│   │   ├── landing.php          # Landing page (home)
│   │   ├── auth/
│   │   │   ├── login.php        # Tela de login
│   │   │   └── register.php     # Tela de registro
│   │   ├── admin/
│   │   │   ├── dashboard.php    # Visão geral + stats
│   │   │   ├── orders.php       # Kanban de pedidos
│   │   │   ├── menu.php         # Gerenciar cardápio
│   │   │   ├── payments.php     # Config de pagamentos
│   │   │   ├── tables.php       # Gestão de mesas + QR
│   │   │   ├── reports.php      # Relatórios vendas
│   │   │   └── settings.php     # Config da loja
│   │   └── app/
│   │       ├── menu.php         # Cardápio do cliente (iframe mobile)
│   │       ├── cart.php         # Sacola/carrinho
│   │       ├── checkout.php     # Formulário pedido + pagamento
│   │       └── confirmation.php # Confirmação + status tracker
│   └── .env                     # Variáveis de ambiente (NÃO commitar)
├── DEPLOY.md                    # Guia passo a passo Hostinger
├── README.md                    # Este arquivo
└── design_handoff_zife_order/   # Arquivos de design original (referência)
    ├── README.md
    ├── Zife Order Landing - standalone.html
    ├── Zife Order Admin.dc.html
    └── Zife Order Cardapio App.dc.html
```

---

## 🗄️ Schema MySQL

### Tabelas Principais

1. **restaurants**: Contas de restaurante (email, senha, plano)
2. **categories**: Categorias do cardápio (Lanches, Bebidas, etc.)
3. **menu_items**: Itens do cardápio (nome, preço, descrição, foto, disponibilidade)
4. **orders**: Pedidos dos clientes (status, total, endereço)
5. **order_items**: Itens dentro de cada pedido
6. **restaurant_tables**: Mesas para dine-in + QR codes
7. **transactions**: Histórico de transações de pagamento
8. **payment_methods**: Métodos habilitados (Pix/Cartão) por restaurante
9. **subscriptions**: Histórico de assinaturas/planos
10. **categories, menu_items, orders, order_items**: Com índices para performance

Ver `public_html/sql/schema.sql` para DDL completo.

---

## 🚀 Quick Start (Local Development)

### Pré-requisitos
- PHP 7.4+ com extensão PDO MySQL
- MySQL 5.7+
- Um gerenciador de dependências (opcional, projeto é PHP puro)

### Setup

1. **Clone o repositório**
   ```bash
   git clone <repo-url>
   cd plataforma-pedidos
   ```

2. **Crie o banco de dados**
   ```bash
   mysql -u root -p < public_html/sql/schema.sql
   ```

3. **Configure variáveis de ambiente**
   ```bash
   cp public_html/.env.example public_html/.env
   # Edite public_html/.env com suas credenciais MySQL e Mercado Pago
   ```

4. **Inicie o servidor PHP**
   ```bash
   cd public_html
   php -S localhost:8000
   ```

5. **Acesse no navegador**
   - Landing: http://localhost:8000
   - Registre-se em http://localhost:8000/auth/register
   - Admin: http://localhost:8000/admin/dashboard
   - Cardápio cliente: http://localhost:8000/app/menu

---

## 🔐 Segurança

- ✅ **Prepared Statements**: Todas as queries usam PDO prepared statements (prevenção SQL injection)
- ✅ **Password Hashing**: Senhas com `password_hash(password_verify())`
- ✅ **Session Management**: Verificação de `$_SESSION['restaurant_id']`
- ✅ **HTTPS Only**: Configurado para produção (Force HTTPS no .htaccess)
- ✅ **CORS Simples**: API acessa apenas resources próprios
- ❌ **Rate Limiting**: Não implementado (adicionar em futuro)
- ❌ **CSRF Tokens**: Não implementado (PHP puro não tem, adicionar se necessário)

---

## 📱 Design & Fidelidade

Todas as telas foram criadas fielmente ao design handoff:

- **Paleta**: Creme `#FBEEE3`, Chili `#E8491D`, Dark `#1B1512`, etc.
- **Tipografia**: Bricolage Grotesque (títulos 700), Instrument Sans (corpo 400-700)
- **Components**: Buttons, cards, badges, status tags com cores semânticas
- **Layout**: Grid/Flex responsivo, mobile-first

---

## 🔄 Fluxo de Pedido

### Cliente
1. Acessa `https://seudominio/app/menu?restaurant=1`
2. Seleciona itens + quantidade
3. Revisa sacola
4. Checkout: Nome, telefone, email, tipo pedido, endereço
5. Escolhe pagamento (Pix/Cartão)
6. Confirmação com número pedido + status tracker

### Restaurante
1. Acessa painel admin (`/admin/dashboard`)
2. Vê novo pedido chegar em tempo real
3. Kanban: arrasta de "Novo" → "Preparando" → "Pronto" → "Entregue"
4. Status muda em tempo real para cliente
5. Relatorios atualizados automaticamente

---

## 📊 API Endpoints

### Autenticação
- `POST /api/auth.php?action=login` - Login restaurante
- `POST /api/auth.php?action=register` - Registrar restaurante

### Menu
- `GET /api/menu/restaurant/{id}` - Cardápio completo
- `POST /api/menu/add-item` - Adicionar item
- `PUT /api/menu/item/{id}` - Atualizar item
- `DELETE /api/menu/item/{id}` - Deletar item

### Pedidos
- `GET /api/orders` - Listar pedidos (admin)
- `POST /api/orders/create` - Criar novo pedido
- `POST /api/orders/update-status` - Atualizar status

### Pagamentos
- `POST /api/payments/create-preference` - Criar preferência Mercado Pago
- `POST /api/payments/webhook` - Webhook Mercado Pago

### Admin
- `GET /api/admin/dashboard` - Stats + recent orders
- `GET /api/admin/stats` - Relatórios avançados
- `POST /api/admin/settings` - Atualizar config loja
- `POST /api/admin/payment-method` - Toggle Pix/Cartão

---

## 🚨 Limitações & Roadmap

### Atual
- ✅ Suporte a 1 restaurante por login (não multi-tenant)
- ✅ Fotos de cardápio via URL (não upload próprio)
- ✅ Pix manual (não PIX dinâmico com QR automático)
- ✅ Status síncronizador por polling (não WebSocket)
- ✅ Sem notificações por email/SMS

### Roadmap (Futuro)
- [ ] Upload de fotos (AWS S3 ou similar)
- [ ] Pix dinâmico com QR automático
- [ ] WebSocket para pedidos em tempo real
- [ ] Notificações WhatsApp/Email
- [ ] App iOS/Android nativo
- [ ] Multi-tenancy (vários restaurantes em 1 servidor)
- [ ] Integração iFood/Uber Eats
- [ ] Cupons e promoções
- [ ] Loyalty program
- [ ] Histórico de clientes

---

## 📝 Licença

MIT License - Veja LICENSE para detalhes.

---

## 💬 Suporte & Contribuições

- 📧 Email: support@zife-order.com (fictício, adicionar seu contato)
- 🐛 Issues: GitHub issues
- 💡 Ideias: Discussões no GitHub

---

## 👨‍💻 Desenvolvido com ❤️

Feito para donos de restaurante que querem entrar na era digital sem complicações.

**Zife Order** - *Cardápio digital para restaurantes modernos* 🍽️
