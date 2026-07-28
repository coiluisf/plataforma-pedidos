-- Dados de Demo para Zife Order
-- Execute este arquivo no phpMyAdmin para adicionar produtos de teste

-- Verificar e criar restaurante demo (ID = 1)
INSERT IGNORE INTO restaurants (id, name, email, password, phone, address, city, state, description, active)
VALUES (1, 'Zife Demo', 'demo@zife.local', '$2y$10$3/xqFqsw.YQn9j.k3QzGYu2Z2Q2Z2Q2Z2Q2Z2Q2Z2Q2Z2Q2Z2Q2Z2', '(11) 99999-0000', 'Rua Demo, 123', 'São Paulo', 'SP', 'Restaurante de demonstração - Teste o Zife Order', TRUE);

-- Limpar categorias e itens anteriores de demo (se existirem)
DELETE FROM menu_items WHERE restaurant_id = 1;
DELETE FROM categories WHERE restaurant_id = 1;

-- CATEGORIA 1: Entradas
INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, 'Entradas', 0);
SET @cat_entrada = LAST_INSERT_ID();

INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available) VALUES
(1, @cat_entrada, 'Bruschetta Italiana', 'Pão tostado com tomate, alho e manjericão fresco', 24.90, 0, TRUE),
(1, @cat_entrada, 'Camarones al Ajillo', 'Camarões suculentos salteados no alho e azeite', 32.90, 1, TRUE),
(1, @cat_entrada, 'Tábua de Queijos Artesanais', 'Seleção de queijos e complementos gourmet', 38.90, 2, TRUE);

-- CATEGORIA 2: Pratos Principais
INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, 'Pratos Principais', 1);
SET @cat_prato = LAST_INSERT_ID();

INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available) VALUES
(1, @cat_prato, 'Burger Premium', 'Carne 180g, queijo cheddar, bacon crocante e tomate', 45.90, 0, TRUE),
(1, @cat_prato, 'Salmão Grelhado', 'Filé de salmão com limão siciliano e legumes frescos', 52.90, 1, TRUE),
(1, @cat_prato, 'Risoto de Cogumelos', 'Risoto cremoso com cogumelos frescos e sálvia', 38.90, 2, TRUE),
(1, @cat_prato, 'Frango à Parmesana', 'Peito de frango empanado com molho e queijo derretido', 42.90, 3, TRUE),
(1, @cat_prato, 'Espaguete Carbonara', 'Receita italiana autêntica com bacon e ovos', 36.90, 4, TRUE);

-- CATEGORIA 3: Bebidas
INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, 'Bebidas', 2);
SET @cat_bebida = LAST_INSERT_ID();

INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available) VALUES
(1, @cat_bebida, 'Refrigerante Lata', 'Refrigerante gelado 350ml - vários sabores', 7.90, 0, TRUE),
(1, @cat_bebida, 'Suco Natural Laranja', 'Suco natural prensado na hora - 500ml', 12.90, 1, TRUE),
(1, @cat_bebida, 'Cerveja Artesanal', 'Cerveja artesanal premium 500ml', 16.90, 2, TRUE),
(1, @cat_bebida, 'Vinho Tinto', 'Vinho tinto selecionado 750ml', 35.90, 3, TRUE);

-- CATEGORIA 4: Sobremesas
INSERT INTO categories (restaurant_id, name, display_order) VALUES (1, 'Sobremesas', 3);
SET @cat_sobremesa = LAST_INSERT_ID();

INSERT INTO menu_items (restaurant_id, category_id, name, description, price, display_order, available) VALUES
(1, @cat_sobremesa, 'Tiramisú', 'Doce italiano clássico com café e mascarpone', 18.90, 0, TRUE),
(1, @cat_sobremesa, 'Brownie Quente', 'Brownie de chocolate intenso com sorvete de baunilha', 15.90, 1, TRUE),
(1, @cat_sobremesa, 'Pavê de Chocolate', 'Pavê delicioso com cobertura de chocolate belga', 14.90, 2, TRUE);

-- Confirmação
SELECT COUNT(*) as 'Total de Itens Adicionados' FROM menu_items WHERE restaurant_id = 1;
SELECT name, COUNT(*) as itens FROM menu_items mi
JOIN categories c ON mi.category_id = c.id
WHERE mi.restaurant_id = 1
GROUP BY c.name
ORDER BY c.display_order;
