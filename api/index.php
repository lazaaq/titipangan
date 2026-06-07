<?php

declare(strict_types=1);

$apiPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '';

if ($apiPath === '/api/health') {
    jsonResponse([
        'status' => 'ok',
        'app' => env('APP_NAME', 'FoodBank'),
        'time' => date('c'),
    ]);
}

if ($apiPath === '/api/users/identify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '[]', true);
    $phone = trim((string) ($payload['phone'] ?? ''));

    if ($phone === '') {
        jsonResponse(['message' => 'Phone is required'], 422);
    }

    $stmt = $pdo->prepare('SELECT id, full_name, phone, address, created_at FROM users WHERE phone = ? LIMIT 1');
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if (!$user) {
        jsonResponse(['message' => 'User not found'], 404);
    }

    jsonResponse(['data' => $user]);
}

if ($apiPath === '/api/claims' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '[]', true);
    $userId = (int) ($payload['user_id'] ?? 0);

    if ($userId <= 0) {
        jsonResponse(['message' => 'user_id is required'], 422);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM food_claims WHERE user_id = ? AND week_key = ?');
    $stmt->execute([$userId, weekKey()]);
    $claimed = (int) $stmt->fetch()['total'] > 0;

    if ($claimed) {
        jsonResponse(['message' => 'Limit reached: user already claimed this week'], 409);
    }

    $stmt = $pdo->prepare('INSERT INTO food_claims (user_id, claimed_at, week_key) VALUES (?, ?, ?)');
    $stmt->execute([$userId, date('Y-m-d H:i:s'), weekKey()]);

    jsonResponse(['message' => 'Claim success']);
}

if ($apiPath === '/api/inventory' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $items = $pdo->query('SELECT * FROM inventory_items ORDER BY updated_at DESC')->fetchAll();
    jsonResponse(['data' => $items]);
}

if ($apiPath === '/api/admin/inventory' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '[]', true);
    $name = trim((string) ($payload['item_name'] ?? ''));
    $stock = (int) ($payload['stock'] ?? 0);
    $unit = trim((string) ($payload['unit'] ?? 'pcs'));

    if ($name === '') {
        jsonResponse(['message' => 'item_name is required'], 422);
    }

    $stmt = $pdo->prepare('INSERT INTO inventory_items (item_name, stock, unit, updated_at) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $stock, $unit, date('Y-m-d H:i:s')]);
    jsonResponse(['message' => 'Inventory created'], 201);
}

jsonResponse(['message' => 'API endpoint not found'], 404);
