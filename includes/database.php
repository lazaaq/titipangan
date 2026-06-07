<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databasePath = resolveDatabasePath();
    $databaseDir = dirname($databasePath);
    if (!is_dir($databaseDir)) {
        mkdir($databaseDir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA busy_timeout = 5000');

    migrate($pdo);
    seed($pdo);

    return $pdo;
}

function resolveDatabasePath(): string
{
    $configured = trim((string) env('DB_DATABASE', 'database/foodbank.sqlite'));
    if ($configured === '') {
        $configured = 'database/foodbank.sqlite';
    }

    if (str_starts_with($configured, '/')) {
        return $configured;
    }

    return dirname(__DIR__) . '/' . ltrim($configured, '/');
}

function migrate(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        full_name TEXT NOT NULL,
        phone TEXT NOT NULL UNIQUE,
        nik TEXT UNIQUE,
        points INTEGER NOT NULL DEFAULT 0,
        address TEXT,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS food_claims (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        claimed_at TEXT NOT NULL,
        week_key TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS pickup_redeems (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        redeem_code TEXT NOT NULL,
        code_date TEXT NOT NULL,
        week_key TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT "pending",
        created_at TEXT NOT NULL,
        redeemed_at TEXT,
        redeemed_by TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        item_name TEXT NOT NULL,
        stock INTEGER NOT NULL DEFAULT 0,
        unit TEXT NOT NULL,
        per_pcs_info TEXT,
        category TEXT NOT NULL DEFAULT "Lainnya",
        image_path TEXT,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_locations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        location_name TEXT NOT NULL,
        address TEXT,
        city TEXT,
        province TEXT,
        google_maps_url TEXT,
        latitude REAL,
        longitude REAL,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS inventory_item_location_stocks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        inventory_item_id INTEGER NOT NULL,
        location_id INTEGER NOT NULL,
        stock INTEGER NOT NULL DEFAULT 0,
        updated_at TEXT NOT NULL,
        FOREIGN KEY (inventory_item_id) REFERENCES inventory_items(id) ON DELETE CASCADE,
        FOREIGN KEY (location_id) REFERENCES inventory_locations(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_otps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        otp_code TEXT NOT NULL,
        expires_at TEXT NOT NULL,
        is_used INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS profile_phone_otps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        phone TEXT NOT NULL,
        otp_code TEXT NOT NULL,
        expires_at TEXT NOT NULL,
        is_used INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS registration_otps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        phone TEXT NOT NULL,
        nik TEXT NOT NULL,
        otp_code TEXT NOT NULL,
        expires_at TEXT NOT NULL,
        is_used INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_donations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        donation_type TEXT NOT NULL,
        item_name TEXT NOT NULL,
        quantity INTEGER NOT NULL,
        unit TEXT NOT NULL,
        item_condition TEXT,
        pickup_address TEXT,
        notes TEXT,
        points_awarded INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_volunteers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        activity_type TEXT NOT NULL,
        volunteer_date TEXT NOT NULL,
        duration_hours INTEGER NOT NULL DEFAULT 1,
        location TEXT,
        experience_text TEXT,
        experience_photo_path TEXT,
        notes TEXT,
        points_awarded INTEGER NOT NULL DEFAULT 0,
        approval_status TEXT NOT NULL DEFAULT "pending",
        approved_at TEXT,
        approved_by TEXT,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_reward_redemptions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        reward_code TEXT NOT NULL,
        reward_name TEXT NOT NULL,
        points_spent INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS reward_catalog (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        reward_code TEXT NOT NULL UNIQUE,
        reward_name TEXT NOT NULL,
        category TEXT NOT NULL,
        points_needed INTEGER NOT NULL DEFAULT 0,
        description TEXT,
        image_path TEXT,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS donation_catalog_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        item_name TEXT NOT NULL UNIQUE,
        unit TEXT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS volunteer_locations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        location_name TEXT NOT NULL,
        address TEXT,
        city TEXT,
        province TEXT,
        google_maps_url TEXT,
        latitude REAL,
        longitude REAL,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user_volunteer_experiences (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        volunteer_id INTEGER NOT NULL,
        experience_text TEXT,
        experience_photo_path TEXT,
        created_at TEXT NOT NULL,
        FOREIGN KEY (volunteer_id) REFERENCES user_volunteers(id) ON DELETE CASCADE
    )');

    ensureUsersNikColumn($pdo);
    ensureUsersPointsColumn($pdo);
    ensureVolunteerApprovalColumns($pdo);
    ensureInventoryImageColumn($pdo);
    ensureInventoryPerPcsInfoColumn($pdo);
    ensureInventoryCategoryColumn($pdo);
    backfillInventoryCategories($pdo);
    ensureVolunteerExperienceColumns($pdo);
    deduplicateInventoryLocationStocks($pdo);
    createIndexes($pdo);
    ensureInventoryLocationBackfill($pdo);
}

function createIndexes(PDO $pdo): void
{
    $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS uk_users_nik ON users (nik)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_food_claims_user_week ON food_claims (user_id, week_key)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_pickup_redeems_code_status ON pickup_redeems (redeem_code, status)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_pickup_redeems_user_date ON pickup_redeems (user_id, code_date)');
    $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS uk_inventory_item_location ON inventory_item_location_stocks (inventory_item_id, location_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_inventory_location ON inventory_item_location_stocks (location_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_user_otps_user_used ON user_otps (user_id, is_used)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_profile_phone_otps_user_phone_used ON profile_phone_otps (user_id, phone, is_used)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_registration_otps_phone_nik_used ON registration_otps (phone, nik, is_used)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_user_donations_user ON user_donations (user_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_user_volunteers_user ON user_volunteers (user_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_user_reward_redemptions_user ON user_reward_redemptions (user_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_user_volunteer_experiences_volunteer ON user_volunteer_experiences (volunteer_id)');
}

function columnExists(PDO $pdo, string $table, string $column): bool
{
    $statement = $pdo->query(sprintf('PRAGMA table_info(%s)', $table));
    $columns = $statement ? $statement->fetchAll() : [];

    foreach ($columns as $columnInfo) {
        if (($columnInfo['name'] ?? null) === $column) {
            return true;
        }
    }

    return false;
}

function ensureUsersNikColumn(PDO $pdo): void
{
    if (!columnExists($pdo, 'users', 'nik')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN nik TEXT');
    }

    $rows = $pdo->query('SELECT id, nik FROM users')->fetchAll();
    if (empty($rows)) {
        return;
    }

    $update = $pdo->prepare('UPDATE users SET nik = ? WHERE id = ?');
    foreach ($rows as $row) {
        $nik = trim((string) ($row['nik'] ?? ''));
        if ($nik !== '') {
            continue;
        }

        $generatedNik = 'NIK' . str_pad((string) ($row['id'] ?? 0), 13, '0', STR_PAD_LEFT);
        $update->execute([$generatedNik, (int) $row['id']]);
    }
}

function ensureUsersPointsColumn(PDO $pdo): void
{
    if (!columnExists($pdo, 'users', 'points')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN points INTEGER NOT NULL DEFAULT 0');
    }
}

function ensureVolunteerApprovalColumns(PDO $pdo): void
{
    if (!columnExists($pdo, 'user_volunteers', 'approval_status')) {
        $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approval_status TEXT NOT NULL DEFAULT "pending"');
    }

    if (!columnExists($pdo, 'user_volunteers', 'approved_at')) {
        $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approved_at TEXT');
    }

    if (!columnExists($pdo, 'user_volunteers', 'approved_by')) {
        $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN approved_by TEXT');
    }
}

function ensureInventoryImageColumn(PDO $pdo): void
{
    if (!columnExists($pdo, 'inventory_items', 'image_path')) {
        $pdo->exec('ALTER TABLE inventory_items ADD COLUMN image_path TEXT');
    }
}

function ensureInventoryPerPcsInfoColumn(PDO $pdo): void
{
    if (!columnExists($pdo, 'inventory_items', 'per_pcs_info')) {
        $pdo->exec('ALTER TABLE inventory_items ADD COLUMN per_pcs_info TEXT');
    }
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
    if (!columnExists($pdo, 'inventory_items', 'category')) {
        $pdo->exec('ALTER TABLE inventory_items ADD COLUMN category TEXT NOT NULL DEFAULT "Lainnya"');
    }
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
    if (!columnExists($pdo, 'user_volunteers', 'experience_text')) {
        $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN experience_text TEXT');
    }

    if (!columnExists($pdo, 'user_volunteers', 'experience_photo_path')) {
        $pdo->exec('ALTER TABLE user_volunteers ADD COLUMN experience_photo_path TEXT');
    }
}

function deduplicateInventoryLocationStocks(PDO $pdo): void
{
    if (!columnExists($pdo, 'inventory_item_location_stocks', 'inventory_item_id') || !columnExists($pdo, 'inventory_item_location_stocks', 'location_id')) {
        return;
    }

    $pdo->exec('DELETE FROM inventory_item_location_stocks
        WHERE id NOT IN (
            SELECT MIN(id)
            FROM inventory_item_location_stocks
            GROUP BY inventory_item_id, location_id
        )');
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

    $defaultLocation = $pdo->query('SELECT id FROM inventory_locations ORDER BY id ASC LIMIT 1')->fetch();
    if (!$defaultLocation) {
        return;
    }

    $defaultLocationId = (int) $defaultLocation['id'];
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
