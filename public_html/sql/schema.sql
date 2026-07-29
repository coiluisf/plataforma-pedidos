-- Zife Order Database Schema

-- Restaurants (conta do restaurante)
CREATE TABLE restaurants (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address VARCHAR(500),
  city VARCHAR(100),
  state VARCHAR(2),
  zip_code VARCHAR(10),
  business_hours_open TIME,
  business_hours_close TIME,
  description TEXT,
  logo_url VARCHAR(500),
  banner_url VARCHAR(500),
  plan VARCHAR(20) DEFAULT 'free',
  stripe_customer_id VARCHAR(255),
  pix_key VARCHAR(255),
  order_count_this_month INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  active BOOLEAN DEFAULT TRUE
);

-- Categories (categorias do cardápio)
CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  display_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  UNIQUE KEY unique_category (restaurant_id, name)
);

-- Menu Items (itens do cardápio)
CREATE TABLE menu_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  category_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  image_url VARCHAR(500),
  available BOOLEAN DEFAULT TRUE,
  display_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  INDEX idx_restaurant (restaurant_id),
  INDEX idx_category (category_id)
);

-- Orders (pedidos)
CREATE TABLE orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  order_number VARCHAR(20) NOT NULL,
  customer_name VARCHAR(255) NOT NULL,
  customer_phone VARCHAR(20),
  customer_email VARCHAR(255),
  order_type ENUM('delivery', 'table', 'counter') DEFAULT 'delivery',
  status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
  delivery_address VARCHAR(500),
  delivery_fee DECIMAL(10, 2) DEFAULT 0,
  subtotal DECIMAL(10, 2) NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  payment_method VARCHAR(50),
  payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  payment_id VARCHAR(255),
  notes TEXT,
  estimated_delivery_time INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  INDEX idx_restaurant (restaurant_id),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
);

-- Order Items (itens do pedido)
CREATE TABLE order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  menu_item_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  subtotal DECIMAL(10, 2) NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id),
  INDEX idx_order (order_id)
);

-- Tables (mesas para dine-in)
CREATE TABLE restaurant_tables (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  table_number INT NOT NULL,
  capacity INT DEFAULT 2,
  status ENUM('free', 'occupied', 'waiting_payment') DEFAULT 'free',
  qr_code VARCHAR(500),
  current_order_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  FOREIGN KEY (current_order_id) REFERENCES orders(id),
  UNIQUE KEY unique_table (restaurant_id, table_number)
);

-- Transactions (transações de pagamento)
CREATE TABLE transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  order_id INT,
  amount DECIMAL(10, 2) NOT NULL,
  payment_method ENUM('pix', 'credit_card', 'debit_card') NOT NULL,
  status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  payment_gateway VARCHAR(50),
  gateway_transaction_id VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  INDEX idx_restaurant (restaurant_id),
  INDEX idx_created_at (created_at)
);

-- Plan Subscriptions (histórico de planos)
CREATE TABLE subscriptions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  plan VARCHAR(20) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  billing_date DATE NOT NULL,
  next_billing_date DATE,
  status ENUM('active', 'cancelled', 'past_due') DEFAULT 'active',
  stripe_subscription_id VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

-- Payment Methods (métodos de pagamento habilitados)
CREATE TABLE payment_methods (
  id INT PRIMARY KEY AUTO_INCREMENT,
  restaurant_id INT NOT NULL,
  method ENUM('pix', 'credit_card') NOT NULL,
  enabled BOOLEAN DEFAULT TRUE,
  gateway VARCHAR(50),
  gateway_settings JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
  UNIQUE KEY unique_method (restaurant_id, method)
);

-- Add indexes for common queries
CREATE INDEX idx_order_status_date ON orders(restaurant_id, status, created_at);
CREATE INDEX idx_menu_restaurant_available ON menu_items(restaurant_id, available);
CREATE INDEX idx_transaction_date ON transactions(restaurant_id, created_at);
