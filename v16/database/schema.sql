-- web-manager Database Schema

CREATE DATABASE IF NOT EXISTS web_manager;
USE web_manager;

-- Roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES ('admin'), ('collaborator'), ('viewer');

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    role_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Sites table
CREATE TABLE IF NOT EXISTS sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    api_key VARCHAR(255) NOT NULL,
    last_sync TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site Access table (Pivot for many-to-many)
CREATE TABLE IF NOT EXISTS site_user_access (
    user_id INT,
    site_id INT,
    PRIMARY KEY (user_id, site_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- Site Metrics table
CREATE TABLE IF NOT EXISTS site_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id INT,
    pages_count INT DEFAULT 0,
    posts_count INT DEFAULT 0,
    drafts_count INT DEFAULT 0,
    active_theme VARCHAR(100),
    themes_count INT DEFAULT 0,
    active_plugins_count INT DEFAULT 0,
    total_plugins_count INT DEFAULT 0,
    site_weight VARCHAR(50), -- e.g. "150MB"
    images_count INT DEFAULT 0,
    sync_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- API Logs
CREATE TABLE IF NOT EXISTS api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id INT,
    action VARCHAR(100),
    status VARCHAR(20), -- 'success', 'error'
    response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- Default Admin User (Password: admin123)
INSERT INTO users (username, password, email, role_id) 
VALUES ('admin', '$2y$10$rKVjVYTDj8ozv6NTygbW1.E1OqoohhbJ3ssyt.ciXjkikGZWHrqQq', 'admin@example.com', 1);
