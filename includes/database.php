<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '3306');
    $database = env('DB_DATABASE', 'foodbank');
    $username = env('DB_USERNAME', 'root');
    $password = env('DB_PASSWORD', '');

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    migrate($pdo);
    seed($pdo);

    return $pdo;
}

function migrate(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(30) NOT NULL UNIQUE,
        nik VARCHAR(32) NOT NULL UNIQUE,
        points BIGINT UNSIGNED NOT NULL DEFAULT 0,
        address VARCHAR(255) NULL,
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    ensureUsersNikColumn($pdo);
    ensureUsersPointsColumn($pdo);

    $pdo->exec('CREATE TABLE IF NOT EXISTS food_claims (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        claimed_at DATETIME NOT NULL,
        week_key VARCHAR(16) NOT NULL,
        INDEX idx_food_claims_user_week (user_id, week_key),
        CONSTRAINT fk_food_claims_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS pickup_redeems (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        redeem_code VARCHAR(12) NOT NULL,
        code_date DATE NOT NULL,
        week_key VARCHAR(16) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT "pending",
        created_at DATETIME NOT NULL,
        redeemed_at DATETIME NULL,
        redeemed_by VARCHAR(64) NULL,
        INDEX idx_pickup_redeems_code_status (redeem_code, status),
        INDEX idx_pickup_redeems_user_date (user_id, code_date),
        CONSTRAINT fk_pickup_redeems_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(255) NOT NULL,
        stock INTEGER NOT NULL DEFAULT 0,
        unit VARCHAR(30) NOT NULL,
        per_pcs_info VARCHAR(60) NULL,
        category VARCHAR(120) NOT NULL DEFAULT "Lainnya",
        image_path VARCHAR(255) NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    ensureInventoryImageColumn($pdo);
    ensureInventoryPerPcsInfoColumn($pdo);
    ensureInventoryCategoryColumn($pdo);
    backfillInventoryCategories($pdo);

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_locations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        location_name VARCHAR(255) NOT NULL,
        address TEXT NULL,
        city VARCHAR(120) NULL,
        province VARCHAR(120) NULL,
        google_maps_url VARCHAR(255) NULL,
        latitude DECIMAL(10,7) NULL,
        longitude DECIMAL(10,7) NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_item_location_stocks (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        inventory_item_id BIGINT UNSIGNED NOT NULL,
        location_id BIGINT UNSIGNED NOT NULL,
        stock INTEGER NOT NULL DEFAULT 0,
        updated_at DATETIME NOT NULL,
        UNIQUE KEY uk_inventory_item_location (inventory_item_id, location_id),
        INDEX idx_inventory_location (location_id),
        CONSTRAINT fk_inventory_item_location_stocks_item FOREIGN KEY (inventory_item_id) REFERENCES inventory_items(id) ON DELETE CASCADE,
        CONSTRAINT fk_inventory_item_location_stocks_location FOREIGN KEY (location_id) REFERENCES inventory_locations(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_otps (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        otp_code VARCHAR(10) NOT NULL,
        expires_at DATETIME NOT NULL,
        is_used TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        INDEX idx_user_otps_user_used (user_id, is_used),
        CONSTRAINT fk_user_otps_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS profile_phone_otps (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        phone VARCHAR(30) NOT NULL,
        otp_code VARCHAR(10) NOT NULL,
        expires_at DATETIME NOT NULL,
        is_used TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        INDEX idx_profile_phone_otps_user_phone_used (user_id, phone, is_used),
        CONSTRAINT fk_profile_phone_otps_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS registration_otps (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(30) NOT NULL,
        nik VARCHAR(32) NOT NULL,
        otp_code VARCHAR(10) NOT NULL,
        expires_at DATETIME NOT NULL,
        is_used TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        INDEX idx_registration_otps_phone_nik_used (phone, nik, is_used)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_donations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        donation_type VARCHAR(80) NOT NULL,
        item_name VARCHAR(255) NOT NULL,
        quantity INT NOT NULL,
        unit VARCHAR(40) NOT NULL,
        item_condition VARCHAR(60) NULL,
        pickup_address TEXT NULL,
        notes TEXT NULL,
        points_awarded INT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        INDEX idx_user_donations_user (user_id),
        CONSTRAINT fk_user_donations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_volunteers (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        activity_type VARCHAR(120) NOT NULL,
        volunteer_date DATE NOT NULL,
        duration_hours INT NOT NULL DEFAULT 1,
        location VARCHAR(255) NULL,
        experience_text TEXT NULL,
        experience_photo_path VARCHAR(255) NULL,
        notes TEXT NULL,
        points_awarded INT NOT NULL DEFAULT 0,
        approval_status VARCHAR(20) NOT NULL DEFAULT "pending",
        approved_at DATETIME NULL,
        approved_by VARCHAR(64) NULL,
        created_at DATETIME NOT NULL,
        INDEX idx_user_volunteers_user (user_id),
        CONSTRAINT fk_user_volunteers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_reward_redemptions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        reward_code VARCHAR(60) NOT NULL,
        reward_name VARCHAR(255) NOT NULL,
        points_spent INT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        INDEX idx_user_reward_redemptions_user (user_id),
        CONSTRAINT fk_user_reward_redemptions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS reward_catalog (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        reward_code VARCHAR(60) NOT NULL UNIQUE,
        reward_name VARCHAR(255) NOT NULL,
        category VARCHAR(120) NOT NULL,
        points_needed INT NOT NULL DEFAULT 0,
        description TEXT NULL,
        image_path VARCHAR(255) NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS donation_catalog_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(255) NOT NULL UNIQUE,
        unit VARCHAR(40) NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS volunteer_locations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        location_name VARCHAR(255) NOT NULL,
        address TEXT NULL,
        city VARCHAR(120) NULL,
        province VARCHAR(120) NULL,
        google_maps_url VARCHAR(255) NULL,
        latitude DECIMAL(10,7) NULL,
        longitude DECIMAL(10,7) NULL,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_volunteer_experiences (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        volunteer_id BIGINT UNSIGNED NOT NULL,
        experience_text TEXT NULL,
        experience_photo_path VARCHAR(255) NULL,
        created_at DATETIME NOT NULL,
        INDEX idx_user_volunteer_experiences_volunteer (volunteer_id),
        CONSTRAINT fk_user_volunteer_experiences_volunteer FOREIGN KEY (volunteer_id) REFERENCES user_volunteers(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    ensureVolunteerApprovalColumns($pdo);
    ensureVolunteerExperienceColumns($pdo);
    ensureInventoryLocationBackfill($pdo);
}

function ensureUsersNikColumn(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $check->execute([$dbName, 'users', 'nik']);
    $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

    if ($exists) {
        return;
    }

    $pdo->exec('ALTER TABLE users ADD COLUMN nik VARCHAR(32) NULL AFTER phone');
    $pdo->exec("UPDATE users SET nik = CONCAT('NIK', LPAD(id, 13, '0')) WHERE nik IS NULL OR nik = ''");
    $pdo->exec('ALTER TABLE users MODIFY nik VARCHAR(32) NOT NULL');
    $pdo->exec('ALTER TABLE users ADD UNIQUE KEY uk_users_nik (nik)');
}

function ensureUsersPointsColumn(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $check->execute([$dbName, 'users', 'points']);
    $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

    if ($exists) {
        return;
    }

    $pdo->exec('ALTER TABLE users ADD COLUMN points BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER nik');
}

function ensureVolunteerApprovalColumns(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $columns = ['approval_status', 'approved_at', 'approved_by'];

    foreach ($columns as $column) {
        $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
        $check->execute([$dbName, 'user_volunteers', $column]);
        $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

        if ($exists) {
            continue;
        }

        if ($column === 'approval_status') {
            $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approval_status VARCHAR(20) NOT NULL DEFAULT "pending" AFTER points_awarded');
        } elseif ($column === 'approved_at') {
            $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approved_at DATETIME NULL AFTER approval_status');
        } elseif ($column === 'approved_by') {
            $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approved_by VARCHAR(64) NULL AFTER approved_at');
        }
    }
}

function ensureInventoryImageColumn(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $check->execute([$dbName, 'inventory_items', 'image_path']);
    $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

    if ($exists) {
        return;
    }

    $pdo->exec('ALTER TABLE inventory_items ADD COLUMN image_path VARCHAR(255) NULL AFTER unit');
}

function ensureInventoryPerPcsInfoColumn(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $check->execute([$dbName, 'inventory_items', 'per_pcs_info']);
    $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

    if ($exists) {
        return;
    }

    $pdo->exec('ALTER TABLE inventory_items ADD COLUMN per_pcs_info VARCHAR(60) NULL AFTER unit');
}

function inventoryCategoryMap(): array
{
    return [
        'Sayuran' => 'Sayuran',
        'Buah Buahan' => 'Buah Buahan',
        'Bahan Masak' => 'Bahan Masak',
        'Lainnya' => 'Lainnya',
    ];
}

function normalizeInventoryCategory(string $category): string
{
    $value = trim($category);
    return array_key_exists($value, inventoryCategoryMap()) ? $value : 'Lainnya';
}

function inferInventoryCategory(string $itemName): string
{
    $name = mb_strtolower($itemName);

    $sayuranKeywords = ['sayur', 'bayam', 'kangkung', 'sawi', 'selada', 'wortel', 'kol', 'brokoli', 'timun', 'tomat', 'kentang', 'buncis', 'labu', 'terong', 'paprika'];
    foreach ($sayuranKeywords as $keyword) {
        if (str_contains($name, $keyword)) {
            return 'Sayuran';
        }
    }

    $buahKeywords = ['buah', 'apel', 'pisang', 'jeruk', 'anggur', 'mangga', 'melon', 'semangka', 'pepaya', 'nanas', 'alpukat', 'salak', 'jambu', 'pir'];
    foreach ($buahKeywords as $keyword) {
        if (str_contains($name, $keyword)) {
            return 'Buah Buahan';
        }
    }

    $bahanMasakKeywords = ['beras', 'minyak', 'gula', 'garam', 'tepung', 'bumbu', 'kecap', 'saus', 'saos', 'santan', 'mie', 'bihun', 'telur', 'daging', 'ayam', 'ikan', 'sarden', 'terigu', 'rempah'];
    foreach ($bahanMasakKeywords as $keyword) {
        if (str_contains($name, $keyword)) {
            return 'Bahan Masak';
        }
    }

    return 'Lainnya';
}

function ensureInventoryCategoryColumn(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $check->execute([$dbName, 'inventory_items', 'category']);
    $exists = (int) ($check->fetch()['total'] ?? 0) > 0;

    if ($exists) {
        return;
    }

    $pdo->exec('ALTER TABLE inventory_items ADD COLUMN category VARCHAR(120) NOT NULL DEFAULT "Lainnya" AFTER per_pcs_info');
}

function backfillInventoryCategories(PDO $pdo): void
{
    $stmt = $pdo->query('SELECT id, item_name, category FROM inventory_items');
    $items = $stmt ? $stmt->fetchAll() : [];
    if (empty($items)) {
        return;
    }

    $update = $pdo->prepare('UPDATE inventory_items SET category = ?, updated_at = updated_at WHERE id = ?');
    foreach ($items as $item) {
        $currentCategory = trim((string) ($item['category'] ?? ''));
        if ($currentCategory !== '' && array_key_exists($currentCategory, inventoryCategoryMap())) {
            continue;
        }

        $category = inferInventoryCategory((string) ($item['item_name'] ?? ''));
        $update->execute([$category, (int) $item['id']]);
    }
}

function ensureVolunteerExperienceColumns(PDO $pdo): void
{
    $dbName = env('DB_DATABASE', 'foodbank');
    $columns = ['experience_text', 'experience_photo_path'];

    foreach ($columns as $column) {
        $check = $pdo->prepare('SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?');
        $check->execute([$dbName, 'user_volunteers', $column]);
        $exists = (int) ($check->fetch()['total'] ?? 0) > 0;
        if ($exists) {
            continue;
        }

        if ($column === 'experience_text') {
            $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN experience_text TEXT NULL AFTER location');
        } else {
            $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN experience_photo_path VARCHAR(255) NULL AFTER experience_text');
        }
    }
}

function ensureInventoryLocationBackfill(PDO $pdo): void
{
    $locationCount = (int) $pdo->query('SELECT COUNT(*) AS total FROM inventory_locations')->fetch()['total'];
    if ($locationCount === 0) {
        $now = date('Y-m-d H:i:s');
        $seedStmt = $pdo->prepare('INSERT INTO inventory_locations (location_name, address, city, province, google_maps_url, latitude, longitude, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)');
        $seedStmt->execute([
            'Gudang Titipangan Jakarta',
            'Jl. TB Simatupang No. 88',
            'Jakarta Selatan',
            'DKI Jakarta',
            'https://maps.google.com/',
            -6.2915060,
            106.7942390,
            $now,
            $now,
        ]);
    }

    $defaultLocationId = (int) $pdo->query('SELECT id FROM inventory_locations ORDER BY id ASC LIMIT 1')->fetch()['id'];
    $items = $pdo->query('SELECT id, stock FROM inventory_items')->fetchAll();
    $checkStmt = $pdo->prepare('SELECT id FROM inventory_item_location_stocks WHERE inventory_item_id = ? LIMIT 1');
    $insertStmt = $pdo->prepare('INSERT INTO inventory_item_location_stocks (inventory_item_id, location_id, stock, updated_at) VALUES (?, ?, ?, ?)');
    $now = date('Y-m-d H:i:s');

    foreach ($items as $item) {
        $itemId = (int) $item['id'];
        $itemStock = max(0, (int) $item['stock']);
        $checkStmt->execute([$itemId]);
        $exists = $checkStmt->fetch();
        if ($exists) {
            continue;
        }
        $insertStmt->execute([$itemId, $defaultLocationId, $itemStock, $now]);
    }
}

function seed(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) as total FROM users')->fetch()['total'];
    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (full_name, phone, nik, address, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['Budi Santoso', '081234567890', '3174010101010001', 'Jakarta', date('Y-m-d H:i:s')]);
        $stmt->execute(['Siti Aminah', '081298765432', '3273010101010002', 'Bandung', date('Y-m-d H:i:s')]);
    }

    $invCount = (int) $pdo->query('SELECT COUNT(*) as total FROM inventory_items')->fetch()['total'];
    if ($invCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO inventory_items (item_name, stock, unit, category, updated_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['Beras Premium', 120, 'kg', 'Bahan Masak', date('Y-m-d H:i:s')]);
        $stmt->execute(['Minyak Goreng', 80, 'liter', 'Bahan Masak', date('Y-m-d H:i:s')]);
        $stmt->execute(['Mie Instan', 300, 'pcs', 'Bahan Masak', date('Y-m-d H:i:s')]);
    }

    $invLocCount = (int) $pdo->query('SELECT COUNT(*) as total FROM inventory_locations')->fetch()['total'];
    if ($invLocCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO inventory_locations (location_name, address, city, province, google_maps_url, latitude, longitude, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute(['Gudang Titipangan Jakarta', 'Jl. TB Simatupang No. 88', 'Jakarta Selatan', 'DKI Jakarta', 'https://maps.google.com/', -6.2915060, 106.7942390, $now, $now]);
        $stmt->execute(['Pusat Distribusi Titipangan Bandung', 'Jl. Soekarno Hatta No. 210', 'Bandung', 'Jawa Barat', 'https://maps.google.com/', -6.9379230, 107.6342110, $now, $now]);
    }

    $invStockCount = (int) $pdo->query('SELECT COUNT(*) as total FROM inventory_item_location_stocks')->fetch()['total'];
    if ($invStockCount === 0) {
        $locations = $pdo->query('SELECT id FROM inventory_locations ORDER BY id ASC')->fetchAll();
        $items = $pdo->query('SELECT id, stock FROM inventory_items ORDER BY id ASC')->fetchAll();
        if (!empty($locations) && !empty($items)) {
            $primary = (int) $locations[0]['id'];
            $secondary = isset($locations[1]) ? (int) $locations[1]['id'] : 0;
            $stmt = $pdo->prepare('INSERT INTO inventory_item_location_stocks (inventory_item_id, location_id, stock, updated_at) VALUES (?, ?, ?, ?)');
            $now = date('Y-m-d H:i:s');
            foreach ($items as $item) {
                $stock = max(0, (int) $item['stock']);
                if ($secondary > 0) {
                    $primaryStock = (int) ceil($stock * 0.6);
                    $secondaryStock = max(0, $stock - $primaryStock);
                    $stmt->execute([(int) $item['id'], $primary, $primaryStock, $now]);
                    $stmt->execute([(int) $item['id'], $secondary, $secondaryStock, $now]);
                } else {
                    $stmt->execute([(int) $item['id'], $primary, $stock, $now]);
                }
            }
        }
    }

    $donationItemCount = (int) $pdo->query('SELECT COUNT(*) as total FROM donation_catalog_items')->fetch()['total'];
    if ($donationItemCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO donation_catalog_items (item_name, unit, created_at, updated_at) VALUES (?, ?, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute(['Beras Kemasan', 'pcs', $now, $now]);
        $stmt->execute(['Minyak Goreng Botol', 'pcs', $now, $now]);
        $stmt->execute(['Mie Instan', 'pcs', $now, $now]);
        $stmt->execute(['Air Mineral', 'pcs', $now, $now]);
        $stmt->execute(['Kaos Kaki', 'pcs', $now, $now]);
        $stmt->execute(['ATK Paket', 'pcs', $now, $now]);
    }

    $locationCount = (int) $pdo->query('SELECT COUNT(*) as total FROM volunteer_locations')->fetch()['total'];
    if ($locationCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO volunteer_locations (location_name, address, city, province, google_maps_url, latitude, longitude, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([
            'Gudang Titipangan Jakarta Selatan',
            'Jl. TB Simatupang No. 88',
            'Jakarta Selatan',
            'DKI Jakarta',
            'https://maps.google.com/',
            -6.2915060,
            106.7942390,
            $now,
            $now,
        ]);
        $stmt->execute([
            'Pusat Distribusi Titipangan Bandung',
            'Jl. Soekarno Hatta No. 210',
            'Bandung',
            'Jawa Barat',
            'https://maps.google.com/',
            -6.9379230,
            107.6342110,
            $now,
            $now,
        ]);
    }

    $rewardCount = (int) $pdo->query('SELECT COUNT(*) as total FROM reward_catalog')->fetch()['total'];
    if ($rewardCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO reward_catalog (reward_code, reward_name, category, points_needed, description, image_path, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute(['PULSA_5K', 'Pulsa Telkomsel 5.000', 'Pulsa', 500, 'Voucher pulsa regular Rp5.000.', null, $now, $now]);
        $stmt->execute(['PULSA_10K', 'Pulsa Telkomsel 10.000', 'Pulsa', 1000, 'Voucher pulsa regular Rp10.000.', null, $now, $now]);
        $stmt->execute(['KUOTA_2GB', 'Paket Kuota 2 GB', 'Kuota', 1200, 'Kuota internet nasional 2 GB.', null, $now, $now]);
        $stmt->execute(['KUOTA_5GB', 'Paket Kuota 5 GB', 'Kuota', 2200, 'Kuota internet nasional 5 GB.', null, $now, $now]);
        $stmt->execute(['BYU_DATA', 'Voucher Data by.U', 'by.U', 1500, 'Voucher data untuk produk by.U.', null, $now, $now]);
        $stmt->execute(['KUNCIE_CLASS', 'Voucher Kuncie Belajar', 'Kuncie', 1800, 'Akses voucher pembelajaran Kuncie.', null, $now, $now]);
        $stmt->execute(['DUNIA_GAMES', 'Voucher Dunia Games', 'Dunia Games', 2000, 'Voucher top up Dunia Games.', null, $now, $now]);
        $stmt->execute(['INDIHOME_DISC', 'Voucher Diskon IndiHome', 'IndiHome', 2500, 'Potongan biaya berlangganan tertentu.', null, $now, $now]);
    }
}
