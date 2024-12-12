CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(255),
  role VARCHAR(50),
  auth_key VARCHAR(255), -- Clé unique pour chaque utilisateur
  key_expiration TIMESTAMP, -- Date d'expiration de la clé
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, name, role) VALUES
('admin@example.com', 'Admin User', 'admin'),
('user1@example.com', 'User One', 'user'),
('user2@example.com', 'User Two', 'user');
