# ⚡ Quick Start - Zife Order

## 📦 O que você tem

Um sistema **completo e pronto para produção** com:

✅ **Landing Page** - Marketing profissional do produto  
✅ **Admin Dashboard** - Gerenciar tudo (pedidos, cardápio, pagamentos, relatórios)  
✅ **App do Cliente** - Mobile-first para ordenar (sem instalação)  
✅ **Backend API** - Todas as funcionalidades com PHP puro  
✅ **Banco de Dados** - Schema MySQL otimizado  
✅ **Pagamento Real** - Integração Mercado Pago (Pix + Cartão)  
✅ **Deploy Guide** - Passo a passo para Hostinger  

---

## 🚀 Deployment em 8 Passos (Hostinger)

### 1️⃣ Criar Banco de Dados
```
hPanel → Banco de Dados → MySQL
Nome: zife_order
Copie as credenciais (usuário, senha, host)
```

### 2️⃣ Criar as Tabelas
```
hPanel → phpMyAdmin
Banco: zife_order
Colar conteúdo de: public_html/sql/schema.sql
Executar
```

### 3️⃣ Fazer Upload dos Arquivos
```
hPanel → Gerenciador de Arquivos → public_html
Enviar todos os arquivos de public_html/
(Dica: comprimir em ZIP, enviar ZIP, depois descompactar)
```

### 4️⃣ Criar .env
```
hPanel → Gerenciador de Arquivos → public_html
Novo arquivo: .env
Copiar de .env.example
Preencher: DB_USER, DB_PASS, DB_NAME, MERCADO_PAGO_*
```

### 5️⃣ Configurar Permissões
```
hPanel → Gerenciador de Arquivos
Clique direito em public_html
Permissões: Pastas 755, Arquivos 644
```

### 6️⃣ Adicionar Credenciais Mercado Pago
```
https://www.mercadopago.com.br
Seu negócio → Credenciais de produção
Copiar PUBLIC_KEY e ACCESS_TOKEN
Colar em .env
```

### 7️⃣ Configurar Webhook (opcional, para pedidos em tempo real)
```
Mercado Pago → Configurações → Webhooks
Adicionar: https://seudominio.com.br/api/payments/webhook
Eventos: payment.created, payment.updated
```

### 8️⃣ Testar!
```
https://seudominio.com.br/
Registre conta de teste
Crie cardápio
Faça pedido de teste
Verifique painel admin
```

---

## 🧪 Testing Local (Desenvolvimento)

```bash
# 1. MySQL setup
mysql -u root -p < public_html/sql/schema.sql

# 2. .env config
cp public_html/.env.example public_html/.env
# Editar: DB_USER, DB_PASS, DB_NAME

# 3. PHP server
cd public_html
php -S localhost:8000

# 4. Access
http://localhost:8000/auth/register
```

---

## 📊 Rotas Principais

| URL | Descrição |
|-----|-----------|
| `/` | Landing page (marketing) |
| `/auth/register` | Registrar restaurante |
| `/auth/login` | Login restaurante |
| `/admin/dashboard` | Painel do dono (precisa login) |
| `/admin/orders` | Kanban de pedidos |
| `/admin/menu` | Gerenciar cardápio |
| `/admin/payments` | Config Pix/Cartão |
| `/admin/tables` | Mesas + QR codes |
| `/admin/reports` | Relatórios vendas |
| `/admin/settings` | Config da loja |
| `/app/menu?restaurant=1` | Cardápio cliente |
| `/app/cart` | Sacola |
| `/app/checkout` | Pagamento |
| `/app/confirmation` | Confirmação pedido |

---

## 🔑 Credenciais de Teste

### Restaurante Test
- Email: `teste@restaurante.com`
- Senha: `123456`
- Plano: Grátis

(Auto-criado ao registrar)

### Mercado Pago
- Usar conta de teste no dashboard Mercado Pago
- Criar cartão fake para testes: `4111111111111111` (exp: 12/25)

---

## 🎨 Design Customização

### Cores (edite `config/constants.php`)
```php
define('COLORS', [
    'primary' => '#E8491D',      // Laranja chili
    'cream' => '#FBEEE3',         // Fundo creme
    'dark' => '#1B1512',          // Texto escuro
    'success' => '#4B7F52',       // Verde
    'warning' => '#C98A1D',       // Âmbar
]);
```

### Fonts (Google Fonts)
- Titles: `Bricolage Grotesque` (600-700)
- Body: `Instrument Sans` (400-700)

### Logo/Branding
- Hoje: Emoji 🍽️
- Depois: Adicionar upload de logo em `admin/settings`

---

## ✨ Features Prontos para MVP

- ✅ Landing page com CTA
- ✅ Registro/Login seguro
- ✅ Cardápio CRUD completo
- ✅ Carrinho com localStorage
- ✅ Checkout com 3 tipos pedido
- ✅ Pagamento Mercado Pago real
- ✅ Painel admin com Kanban
- ✅ Status em tempo real (com reload)
- ✅ Relatórios de vendas
- ✅ Gestão de pagamentos
- ✅ Planos com limite mensal

---

## 🔒 Próximos Passos (Nice to Have)

1. **Melhorias UI/UX**
   - Uploador de fotos real (S3)
   - Ícones melhores (FontAwesome)
   - Animações (Framer Motion)

2. **Features Avançados**
   - Notificações WhatsApp
   - Pix dinâmico com QR
   - WebSocket tempo real
   - Multi-idioma

3. **Performance**
   - Caching (Redis)
   - CDN para imagens
   - Lazy loading

4. **Monetização**
   - Cobrar taxa por pedido (% ou fixa)
   - Planos anuais com desconto
   - Add-ons (promoções, loyalty)

---

## 🐛 Troubleshooting

### Erro de Conexão Banco
```
Verificar: DB_HOST, DB_USER, DB_PASS em .env
Testar no phpMyAdmin do hPanel
```

### 404 Not Found
```
Verificar: .htaccess está em public_html/
mod_rewrite habilitado (contatar suporte Hostinger)
```

### Mercado Pago não funciona
```
Verificar: MERCADO_PAGO_* em .env
Teste com cartão fake 4111111111111111
Verifique webhook URL em dashboard MP
```

### Página em branco
```
Verificar: error.log em hPanel
Confirmar PHP 7.4+ habilitado
Verificar permissões 755/644
```

---

## 📚 Documentação Completa

- **README.md** - Visão geral, arquitetura, schema
- **DEPLOY.md** - Guia passo-a-passo Hostinger
- **Code Comments** - Explicação de funções principais

---

## 💡 Dicas de Ouro

1. **Sempre faça backup** antes de atualizar
2. **Teste em staging** antes de ir pro ar
3. **Use HTTPS** (Let's Encrypt grátis na Hostinger)
4. **Monitore logs** (error.log do hPanel)
5. **Configure backup automático** no hPanel
6. **Acompanhe métricas** (pedidos/mês, revenue)

---

## 🎉 Sucesso!

Seu Zife Order está pronto para 🚀

Qualquer dúvida, consulte os guias acima ou contate o suporte Hostinger.

**Bom negócio!** 🍽️
