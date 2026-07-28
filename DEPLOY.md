# 🚀 Guia de Deploy - Zife Order na Hostinger

Este guia detalha como fazer o deploy do Zife Order na Hostinger Single Web Hosting (hospedagem compartilhada com PHP + MySQL).

## ✅ Pré-requisitos

- Plano de hospedagem **Hostinger Single Web Hosting** (ou superior)
- Acesso ao **hPanel** (painel de controle Hostinger)
- Acesso via **FTP/SFTP** ou **File Manager** do hPanel
- Credenciais MySQL criadas

---

## 1️⃣ Criar Banco de Dados MySQL no hPanel

1. Acesse o **hPanel** da sua conta Hostinger
2. Vá para **Banco de Dados** → **MySQL**
3. Clique em **Criar Novo Banco de Dados**
4. Preencha os dados:
   - **Nome do Banco**: `zife_order` (ou o nome que preferir)
   - **Prefixo da Tabela**: deixar vazio
5. Clique em **Criar Banco de Dados**
6. Anote as credenciais:
   - **Banco de Dados**: `seu_usuario_zife_order`
   - **Usuário**: `seu_usuario_mysql`
   - **Senha**: `sua_senha_gerada`
   - **Host**: `localhost` (geralmente)

> **Nota**: A Hostinger pode gerar nomes automáticos com prefixo. Copie exatamente como aparece.

### Criar as Tabelas

1. Ainda no hPanel, acesse **Banco de Dados** → **phpMyAdmin**
2. Faça login com as credenciais MySQL criadas
3. Selecione o banco de dados `zife_order`
4. Acesse a aba **SQL**
5. Copie e cole o conteúdo de `public_html/sql/schema.sql`
6. Clique em **Executar**

✅ Tabelas criadas com sucesso!

---

## 2️⃣ Fazer Upload dos Arquivos via FTP

### Opção A: Usar Gerenciador de Arquivos do hPanel (Recomendado)

1. No hPanel, vá para **Arquivos** → **Gerenciador de Arquivos**
2. Navegue até a pasta `public_html`
3. Clique em **Fazer Upload** (ou drag & drop)
4. Selecione os arquivos da pasta `public_html` do seu projeto local:
   - `index.php`
   - `.htaccess`
   - `config/` (pasta inteira)
   - `sql/` (pasta inteira)
   - `api/` (pasta inteira)
   - `public/` (pasta inteira)
   - `views/` (pasta inteira)

> **Dica**: Comprima tudo em um ZIP, faça upload do ZIP, e depois descompacte no hPanel.

### Opção B: Usar FTP/SFTP

1. Obtenha as credenciais FTP no hPanel (**Contas** → **FTP**)
2. Use um cliente FTP (FileZilla, Cyberduck, etc.)
3. Conecte aos servidores FTP da Hostinger
4. Navegue até `public_html`
5. Faça upload de todos os arquivos

---

## 3️⃣ Configurar Variáveis de Ambiente

1. No hPanel → **Gerenciador de Arquivos** → `public_html`
2. Clique em **Criar Novo** → **Arquivo**
3. Nome: `.env`
4. Conteúdo: (copie de `.env.example` e preencha com seus dados)

```env
APP_ENV=production

DB_HOST=localhost
DB_USER=seu_usuario_mysql_aqui
DB_PASS=sua_senha_mysql_aqui
DB_NAME=zife_order

MERCADO_PAGO_PUBLIC_KEY=sua_public_key_mercado_pago
MERCADO_PAGO_ACCESS_TOKEN=seu_access_token_mercado_pago
```

5. Clique em **Salvar**

---

## 4️⃣ Configurar Permissões de Pasta

1. No Gerenciador de Arquivos do hPanel:
2. Clique com botão direito na pasta `public_html`
3. Selecione **Permissões**
4. Defina como:
   - **Pastas**: `755`
   - **Arquivos**: `644`

> Isso garante que o servidor web possa ler e executar os arquivos PHP.

---

## 5️⃣ Configurar Mercado Pago

### Obter Credenciais Mercado Pago

1. Acesse https://www.mercadopago.com.br e faça login/registre-se
2. Vá para **Seu negócio** → **Credenciais de produção**
3. Copie:
   - **PUBLIC_KEY** (começa com `APP_`)
   - **ACCESS_TOKEN** (começa com `APP_`)

### Configurar Webhook (para notificações de pagamento)

1. Em **Configurações de Negócio**, procure por **URLs de Notificação** ou **Webhooks**
2. Adicione a URL:
   ```
   https://seudominio.com.br/api/payments/webhook
   ```
3. Escolha os eventos: `payment.created`, `payment.updated`
4. Salve

> O Mercado Pago notificará seu servidor sempre que um pagamento for processado.

---

## 6️⃣ Testar o Deploy

### Verificar se Tudo Funciona

1. Acesse seu domínio: `https://seudominio.com.br`
2. Você deve ver a **Landing Page** do Zife Order
3. Clique em **"Registrar Agora"** e crie uma conta de teste
4. Verifique se consegue:
   - ✅ Fazer login
   - ✅ Adicionar itens ao cardápio
   - ✅ Criar um pedido
   - ✅ Acessar o painel admin

### Troubleshooting

#### "Erro de Conexão com Banco de Dados"
- Verifique as credenciais no `.env`
- Confirme que o banco foi criado no hPanel
- Teste a conexão no phpMyAdmin

#### "Página em branco / Erro 500"
- Verifique o log de erros do PHP:
  - hPanel → **Arquivos** → **error.log** (na pasta public_html)
- Certifique-se de que o `.htaccess` foi feito upload

#### "404 Not Found"
- Verifique se o mod_rewrite está habilitado:
  - Contate suporte Hostinger se necessário
- Confirme que o `.htaccess` está na pasta raiz

---

## 7️⃣ Configurações Extras Recomendadas

### SSL/HTTPS

- A Hostinger fornece SSL gratuito com Let's Encrypt
- No hPanel → **Segurança** → **SSL/TLS**
- Marque "Auto-renew"

### Backup Automático

- hPanel → **Backups**
- Configure backups automáticos semanais
- Sempre tenha um backup antes de fazer mudanças

### Monitoramento

- Agende um cron job para monitorar a saúde da aplicação:
  - hPanel → **Cron Jobs**
  - Configure um check que acessa `https://seudominio.com.br/api/health` (implementar endpoint se quiser)

---

## 8️⃣ Primeiro Acesso & Configuração Inicial

1. **Acesse a landing page**: `https://seudominio.com.br`
2. **Registre a primeira conta** de restaurante
3. **Faça login** no painel admin
4. **Configure as informações da loja**:
   - Nome, endereço, telefone, horário
   - Logo (opcional, por enquanto é texto)
5. **Adicione categorias e itens do cardápio**
6. **Habilite os métodos de pagamento**:
   - Configure sua chave Pix
   - Mercado Pago já está integrado via API
7. **Compartilhe o link do cardápio** com clientes: `https://seudominio.com.br/app/menu?restaurant=1`

---

## 🆘 Suporte & Troubleshooting

### Erros Comuns

| Erro | Solução |
|------|---------|
| "Call to undefined function" | Confirme que o PHP 7.4+ está habilitado no hPanel |
| "File not found" | Verifique se todos os arquivos foram feitos upload e permissões 755/644 |
| "CORS error" | Verifique se o `.env` foi criado corretamente (não .env.txt) |
| "Mercado Pago não funciona" | Confirme se as credenciais estão certas no `.env` |

### Logs Úteis

- **Erro PHP**: `/public_html/error.log` (hPanel → Gerenciador de Arquivos)
- **Erro Apache**: Contate suporte Hostinger
- **Erro Mercado Pago**: Acesse seu dashboard Mercado Pago para ver notificações

---

## ✨ Próximas Melhorias (Roadmap)

- [ ] Integração com WhatsApp para notificações
- [ ] App iOS/Android nativo
- [ ] Relatórios avançados e analytics
- [ ] Integração com serviços de entrega (iFood, Uber Eats)
- [ ] Suporte a multiple restaurantes em uma única conta
- [ ] Sistema de cupons e promoções

---

**Dúvidas?** Contate o suporte Hostinger ou abra uma issue no repositório do projeto.

Boa sorte! 🍽️🚀
