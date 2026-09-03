<?php
// ── Database credentials ───────────────────────────────────────────────────
// Local (Laragon): leave as-is if you use the default root/no-password setup.
// Hostinger: replace with the credentials from your hosting control panel.
define('DB_HOST',     'localhost');
define('DB_NAME',     'tryon');
define('DB_USER',     'root');       // Hostinger: change to your DB username
define('DB_PASS',     '');           // Hostinger: change to your DB password

// ── Upload paths ───────────────────────────────────────────────────────────
// APP_URL: the public base URL of your site (no trailing slash).
// Local Laragon example: 'http://localhost/virtual-try-on'
// Hostinger example:     'https://yourdomain.com'
define('APP_URL',     'https://ai.loopsintegrated.co/fiton');

define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', APP_URL . '/api/uploads/');

// ── DB connection ──────────────────────────────────────────────────────────
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
