-- ========================================
-- BDE Ynot - Script MySQL (InfinityFree)
-- ========================================
-- Importer ce fichier dans phpMyAdmin sur InfinityFree

SET NAMES utf8mb4;

-- Table des admins
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    member_id INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des membres
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    role VARCHAR(200) NOT NULL,
    pole VARCHAR(100) NOT NULL,
    image LONGTEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des activités (événements + sports)
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(300) NOT NULL,
    activity_date DATE NOT NULL,
    price DECIMAL(10,2) NULL,
    description TEXT NULL,
    image LONGTEXT NULL,
    pole VARCHAR(50) NOT NULL COMMENT 'event ou sport',
    admin_id INT NULL,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des partenaires
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    address VARCHAR(500) NULL,
    telephone VARCHAR(50) NULL,
    logo LONGTEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des contacts
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(300) NOT NULL,
    subject VARCHAR(300) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clé étrangère admins -> members
ALTER TABLE admins ADD FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL;

-- ========================================
-- Données initiales
-- ========================================

-- Admin (mot de passe: admin123, hashé en bcrypt)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$12$JZ0b8qH1jqkaJy2Jui.K6eeMjh56Q3CA9zWGrrdeulIBcVVeANd1a');
