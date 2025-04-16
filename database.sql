CREATE DATABASE IF NOT EXISTS nexora;
USE nexora;

CREATE TABLE IF NOT EXISTS authors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  position VARCHAR(100),
  phone VARCHAR(20) NOT NULL,
  whatsapp VARCHAR(20),
  address VARCHAR(255),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS websites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  domain VARCHAR(255) NOT NULL UNIQUE,
  author_id INT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  payment_id VARCHAR(255) NOT NULL UNIQUE,
  amount DECIMAL(10, 2) NOT NULL,
  currency VARCHAR(10) NOT NULL,
  payment_method VARCHAR(50),
  reference VARCHAR(200) NOT NULL UNIQUE,
  status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  receipt_number VARCHAR(255),
  ip_address VARCHAR(50) NOT NULL,
  author_id INT,
  website_id INT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (website_id) REFERENCES websites(id) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL
);

CREATE UNIQUE INDEX idx_domain_unique ON websites((LOWER(domain)));
CREATE UNIQUE INDEX idx_payment_id_unique ON payments(payment_id);
CREATE UNIQUE INDEX idx_references_unique ON payments(reference);
CREATE UNIQUE INDEX idx_email_unique ON authors(email);
CREATE UNIQUE INDEX idx_phone_unique ON authors(phone);