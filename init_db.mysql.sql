-- init_db.mysql.sql – führe dies in deiner MySQL-DB aus (phpMyAdmin)
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Testnutzer (Passwort = Passw0rd!)
-- INSERT INTO users (email, password_hash) VALUES ('test@example.com', '$2y$10$4Z0r5mO2lI5zW5EqkOtzku8s3w3pQ9m3xQ2mK6ZQXfF0O1l8bF5l6');
