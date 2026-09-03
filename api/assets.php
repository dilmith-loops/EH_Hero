<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/config.php';

// Ensure uploads directory exists
if (!is_dir(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}

$method = $_SERVER['REQUEST_METHOD'];

// ── GET: list assets ───────────────────────────────────────────────────────
if ($method === 'GET') {
    $valid = ['upper', 'lower', 'overall'];
    $category = isset($_GET['category']) && in_array($_GET['category'], $valid)
        ? $_GET['category'] : null;

    if ($category) {
        $stmt = $pdo->prepare(
            'SELECT id, name, category, file_name, mime_type, uploaded_at
             FROM clothing_assets WHERE category = ? ORDER BY uploaded_at DESC'
        );
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query(
            'SELECT id, name, category, file_name, mime_type, uploaded_at
             FROM clothing_assets ORDER BY uploaded_at DESC'
        );
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $assets = array_map(function ($row) {
        return [
            'id'         => $row['id'],
            'name'       => $row['name'],
            'category'   => $row['category'],
            'url'        => UPLOADS_URL . $row['file_name'],
            'mimeType'   => $row['mime_type'],
            'uploadedAt' => (int) $row['uploaded_at'],
        ];
    }, $rows);

    echo json_encode($assets);
    exit;
}

// ── POST: upload asset ─────────────────────────────────────────────────────
if ($method === 'POST') {
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded or upload error']);
        exit;
    }

    $allowed_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mime = mime_content_type($_FILES['file']['tmp_name']);
    if (!in_array($mime, $allowed_mime)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }

    $valid_cats = ['upper', 'lower', 'overall'];
    $category = $_POST['category'] ?? 'overall';
    if (!in_array($category, $valid_cats)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid category']);
        exit;
    }

    $name = htmlspecialchars(trim($_POST['name'] ?? 'Untitled'), ENT_QUOTES, 'UTF-8');
    $ext_map = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $ext = $ext_map[$mime] ?? 'jpg';

    $id        = bin2hex(random_bytes(12));
    $file_name = $id . '.' . $ext;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], UPLOADS_DIR . $file_name)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save file']);
        exit;
    }

    $uploaded_at = (int) (microtime(true) * 1000);

    $stmt = $pdo->prepare(
        'INSERT INTO clothing_assets (id, name, category, file_name, mime_type, uploaded_at)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$id, $name, $category, $file_name, $mime, $uploaded_at]);

    echo json_encode([
        'id'         => $id,
        'name'       => $name,
        'category'   => $category,
        'url'        => UPLOADS_URL . $file_name,
        'mimeType'   => $mime,
        'uploadedAt' => $uploaded_at,
    ]);
    exit;
}

// ── DELETE: remove asset ───────────────────────────────────────────────────
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? '';
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT file_name FROM clothing_assets WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        $path = UPLOADS_DIR . $row['file_name'];
        if (file_exists($path)) {
            unlink($path);
        }
        $pdo->prepare('DELETE FROM clothing_assets WHERE id = ?')->execute([$id]);
    }

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
