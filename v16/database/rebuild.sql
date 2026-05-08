-- ============================================
-- web-manager - Full Database Rebuild Script
-- Generated: 2026-05-05
-- ============================================
-- This script recreates the entire web_manager database
-- including all migrations (avatar, location fields, updates_count)

CREATE DATABASE IF NOT EXISTS web_manager;
USE web_manager;

-- ============================================
-- 1. ROLES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES ('admin'), ('collaborator'), ('viewer')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ============================================
-- 2. USERS TABLE (includes avatar migration)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    role_id INT,
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- ============================================
-- 3. SITES TABLE (includes location/specialty migrations)
-- ============================================
CREATE TABLE IF NOT EXISTS sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    api_key VARCHAR(255) NOT NULL,
    country VARCHAR(100) DEFAULT NULL,
    state VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    specialty VARCHAR(100) DEFAULT NULL,
    last_sync TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 4. SITE USER ACCESS (many-to-many pivot)
-- ============================================
CREATE TABLE IF NOT EXISTS site_user_access (
    user_id INT,
    site_id INT,
    PRIMARY KEY (user_id, site_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- ============================================
-- 5. SITE METRICS TABLE (includes updates_count)
-- ============================================
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
    site_weight VARCHAR(50),
    images_count INT DEFAULT 0,
    updates_count INT DEFAULT 0,
    sync_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- ============================================
-- 6. API LOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_id INT,
    action VARCHAR(100),
    status VARCHAR(20),
    response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);

-- ============================================
-- 7. DEFAULT ADMIN USER
-- ============================================
-- Username: admin | Password: admin123
INSERT INTO users (username, password, email, role_id)
VALUES ('admin', '$2y$10$rKVjVYTDj8ozv6NTygbW1.E1OqoohhbJ3ssyt.ciXjkikGZWHrqQq', 'admin@example.com', 1)
ON DUPLICATE KEY UPDATE username = VALUES(username);
