-- Run this once in your MySQL database (tryon)
-- Laragon: open phpMyAdmin → select "tryon" DB → run this SQL
-- Hostinger: open phpMyAdmin in your hosting panel → run this SQL

CREATE TABLE IF NOT EXISTS clothing_assets (
    id          VARCHAR(24)                        NOT NULL PRIMARY KEY,
    name        VARCHAR(255)                       NOT NULL,
    category    ENUM('upper','lower','overall')    NOT NULL,
    file_name   VARCHAR(500)                       NOT NULL,
    mime_type   VARCHAR(100)                       NOT NULL,
    uploaded_at BIGINT                             NOT NULL,
    INDEX idx_category (category),
    INDEX idx_uploaded_at (uploaded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
