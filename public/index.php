<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/otp.php';
require_once __DIR__ . '/../includes/icons.php';

startSession();
$pdo = db();

function storeInventoryImage(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gambar gagal. Silakan coba lagi.');
    }

    $maxSize = 2 * 1024 * 1024;
    if (((int) ($file['size'] ?? 0)) > $maxSize) {
        throw new RuntimeException('Ukuran gambar maksimal 2MB.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $mimeType = (string) (mime_content_type($tmpName) ?: '');
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
    ];

    if (!isset($allowed[$mimeType])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, WEBP, atau AVIF.');
    }

    $uploadDir = dirname(__DIR__) . '/assets/inventory';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Folder upload gambar tidak tersedia.');
    }

    $fileName = 'inv_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mimeType];
    $destination = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Gagal menyimpan gambar.');
    }

    return '/assets/inventory/' . $fileName;
}

function storeVolunteerExperiencePhoto(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload foto pengalaman gagal. Silakan coba lagi.');
    }

    $maxSize = 2 * 1024 * 1024;
    if (((int) ($file['size'] ?? 0)) > $maxSize) {
        throw new RuntimeException('Ukuran foto pengalaman maksimal 2MB.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $mimeType = (string) (mime_content_type($tmpName) ?: '');
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
    ];

    if (!isset($allowed[$mimeType])) {
        throw new RuntimeException('Format foto pengalaman harus JPG, PNG, WEBP, atau AVIF.');
    }

    $uploadDir = dirname(__DIR__) . '/assets/volunteer';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Folder upload foto volunteer tidak tersedia.');
    }

    $fileName = 'exp_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mimeType];
    $destination = $uploadDir . '/' . $fileName;
    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Gagal menyimpan foto pengalaman volunteer.');
    }

    return '/assets/volunteer/' . $fileName;
}

function storeRewardImage(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gambar reward gagal. Silakan coba lagi.');
    }

    $maxSize = 2 * 1024 * 1024;
    if (((int) ($file['size'] ?? 0)) > $maxSize) {
        throw new RuntimeException('Ukuran gambar reward maksimal 2MB.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $mimeType = (string) (mime_content_type($tmpName) ?: '');
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
    ];

    if (!isset($allowed[$mimeType])) {
        throw new RuntimeException('Format gambar reward harus JPG, PNG, WEBP, atau AVIF.');
    }

    $uploadDir = dirname(__DIR__) . '/assets/rewards';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Folder upload gambar reward tidak tersedia.');
    }

    $fileName = 'reward_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mimeType];
    $destination = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Gagal menyimpan gambar reward.');
    }

    return '/assets/rewards/' . $fileName;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

if (str_starts_with($uri, '/api/')) {
    require __DIR__ . '/../api/index.php';
    exit;
}

$title = 'Titipangan';
$role = 'user';
$message = $_SESSION['flash'] ?? null;
$pickupQr = $_SESSION['pickup_qr'] ?? null;
unset($_SESSION['flash']);
unset($_SESSION['pickup_qr']);

if ($uri === '/katalog-publik' && $method === 'GET') {
    $inventoryLocations = $pdo->query('SELECT id, location_name, city, province FROM inventory_locations WHERE is_active = 1 ORDER BY location_name ASC')->fetchAll();
    $inventoryCategories = inventoryCategoryMap();
    $selectedLocationId = (int) ($_GET['location_id'] ?? 0);
    $selectedCategory = trim((string) ($_GET['category'] ?? ''));
    if ($selectedCategory !== '' && !array_key_exists($selectedCategory, $inventoryCategories)) {
        $selectedCategory = '';
    }
    $searchQuery = trim((string) ($_GET['q'] ?? ''));
    $inventory = [];

    if ($selectedLocationId > 0) {
        $stmt = $pdo->prepare('
            SELECT ii.id, ii.item_name, ii.unit, ii.per_pcs_info, ii.category, ii.image_path, ils.stock, ils.updated_at
            FROM inventory_item_location_stocks ils
            JOIN inventory_items ii ON ii.id = ils.inventory_item_id
            WHERE ils.location_id = ?
            AND (? = "" OR ii.category = ?)
            AND (? = "" OR ii.item_name LIKE ?)
            ORDER BY ii.item_name ASC
        ');
        $searchLike = $searchQuery !== '' ? '%' . $searchQuery . '%' : '';
        $stmt->execute([
            $selectedLocationId,
            $selectedCategory,
            $selectedCategory,
            $searchQuery,
            $searchLike,
        ]);
        $inventory = $stmt->fetchAll();
    }
    require __DIR__ . '/../katalog-publik.php';
    exit;
}

if (($uri === '/' || $uri === '/landing') && $method === 'GET') {
    $foodbankSpots = $pdo->query('SELECT id, location_name, address, city, province, latitude, longitude, google_maps_url FROM inventory_locations WHERE is_active = 1 ORDER BY updated_at DESC, id DESC LIMIT 12')->fetchAll();

    $totalDistributed = (int) ($pdo->query('SELECT COUNT(*) AS total FROM food_claims')->fetch()['total'] ?? 0);
    $totalParticipatingDonors = (int) ($pdo->query('SELECT COUNT(DISTINCT user_id) AS total FROM user_donations')->fetch()['total'] ?? 0);

    $kgRow = $pdo->query('
        SELECT
            SUM(
                CASE
                    WHEN LOWER(unit) IN ("kg", "kilogram") THEN quantity
                    WHEN LOWER(unit) = "gram" THEN quantity / 1000
                    WHEN LOWER(unit) = "pcs" THEN quantity * 0.25
                    ELSE 0
                END
            ) AS total_kg
        FROM user_donations
    ')->fetch();
    $totalFoodKg = (float) ($kgRow['total_kg'] ?? 0.0);
    $totalCarbonSaved = $totalFoodKg * 2.5;

    $individualLeaderboard = $pdo->query('
        SELECT u.full_name AS donor_name, SUM(ud.quantity) AS total_items, SUM(ud.points_awarded) AS total_points
        FROM user_donations ud
        JOIN users u ON u.id = ud.user_id
        GROUP BY ud.user_id, u.full_name
        ORDER BY total_points DESC, total_items DESC
        LIMIT 8
    ')->fetchAll();

    $brandLeaderboard = $pdo->query('
        SELECT
            ud.donation_type AS brand_name,
            SUM(ud.quantity) AS total_items,
            SUM(ud.points_awarded) AS total_points
        FROM user_donations ud
        WHERE LOWER(ud.donation_type) LIKE "%brand%"
           OR LOWER(ud.donation_type) LIKE "%perusahaan%"
           OR LOWER(ud.donation_type) LIKE "%corporate%"
        GROUP BY ud.donation_type
        ORDER BY total_points DESC, total_items DESC
        LIMIT 8
    ')->fetchAll();

    if (empty($brandLeaderboard)) {
        $brandLeaderboard = $pdo->query('
            SELECT
                CONCAT("Brand ", UPPER(SUBSTRING(MD5(ud.donation_type), 1, 4))) AS brand_name,
                SUM(ud.quantity) AS total_items,
                SUM(ud.points_awarded) AS total_points
            FROM user_donations ud
            GROUP BY ud.donation_type
            ORDER BY total_points DESC, total_items DESC
            LIMIT 8
        ')->fetchAll();
    }

    $testimonialsRaw = $pdo->query('
        SELECT u.full_name, fc.claimed_at
        FROM food_claims fc
        JOIN users u ON u.id = fc.user_id
        ORDER BY fc.claimed_at DESC
        LIMIT 3
    ')->fetchAll();

    $testimonials = [];
    foreach ($testimonialsRaw as $row) {
        $name = (string) ($row['full_name'] ?? 'Penerima Manfaat');
        $initial = strtoupper(substr($name, 0, 1));
        $masked = $initial . str_repeat('*', max(2, min(6, strlen($name) - 1)));
        $testimonials[] = [
            'name' => $masked,
            'quote' => 'Bantuan dari Titipangan membantu keluarga kami memenuhi kebutuhan makan dengan lebih tenang dan layak.',
            'time' => date('d M Y', strtotime((string) $row['claimed_at'])),
        ];
    }
    if (empty($testimonials)) {
        $testimonials = [
            ['name' => 'A***', 'quote' => 'Program ini sangat membantu kebutuhan pangan harian keluarga kami.', 'time' => date('d M Y')],
            ['name' => 'B***', 'quote' => 'Distribusi cepat, tertib, dan transparan. Sangat bermanfaat.', 'time' => date('d M Y')],
            ['name' => 'C***', 'quote' => 'Terima kasih untuk semua donatur dan relawan Titipangan.', 'time' => date('d M Y')],
        ];
    }

    require __DIR__ . '/../landing.php';
    exit;
}

if ($uri === '/landing2' && $method === 'GET') {
    require __DIR__ . '/../landing.php';
    exit;
}

if ($uri === '/user/login' && $method === 'GET') {
    if (isUserLoggedIn()) {
        header('Location: /user/');
        exit;
    }
    require __DIR__ . '/../user/login.php';
    exit;
}

if ($uri === '/user/register' && $method === 'GET') {
    if (isUserLoggedIn()) {
        header('Location: /user/');
        exit;
    }
    require __DIR__ . '/../user/register.php';
    exit;
}

if ($uri === '/user/register/send-otp' && $method === 'POST') {
    $phoneInput = trim((string) ($_POST['phone'] ?? ''));
    $normalized = normalizePhone($phoneInput);
    $registrationKey = 'HP_' . $normalized;

    if ($normalized === '' || strlen($normalized) < 10) {
        $_SESSION['flash'] = 'Nomor HP tidak valid.';
        header('Location: /user/register');
        exit;
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM users WHERE phone = ? OR phone = ?');
    $stmt->execute([$phoneInput, $normalized]);
    if ((int) ($stmt->fetch()['total'] ?? 0) > 0) {
        $_SESSION['flash'] = 'Nomor HP sudah terdaftar.';
        header('Location: /user/register');
        exit;
    }

    $otp = generateOtpCode();
    $expires = (new DateTimeImmutable('+5 minutes'))->format('Y-m-d H:i:s');

    $pdo->prepare('UPDATE registration_otps SET is_used = 1 WHERE (phone = ? OR phone = ?) AND nik = ? AND is_used = 0')
        ->execute([$phoneInput, $normalized, $registrationKey]);

    $insert = $pdo->prepare('INSERT INTO registration_otps (phone, nik, otp_code, expires_at, is_used, created_at) VALUES (?, ?, ?, ?, 0, ?)');
    $insert->execute([$normalized, $registrationKey, $otp, $expires, date('Y-m-d H:i:s')]);

    $sendResult = sendOtpViaWhatsApp($normalized, $otp);
    if (!$sendResult['ok']) {
        $_SESSION['flash'] = $sendResult['message'];
        header('Location: /user/register');
        exit;
    }

    $_SESSION['register_phone'] = $normalized;
    $_SESSION['flash'] = $sendResult['message'];
    header('Location: /user/register/verify');
    exit;
}

if ($uri === '/user/register/verify' && $method === 'GET') {
    if (isUserLoggedIn()) {
        header('Location: /user/');
        exit;
    }
    $phone = (string) ($_SESSION['register_phone'] ?? '');
    if ($phone === '') {
        $_SESSION['flash'] = 'Silakan isi data registrasi terlebih dulu.';
        header('Location: /user/register');
        exit;
    }
    require __DIR__ . '/../user/register-verify.php';
    exit;
}

if ($uri === '/user/register/verify' && $method === 'POST') {
    $phone = normalizePhone(trim((string) ($_POST['phone'] ?? '')));
    $registrationKey = 'HP_' . $phone;
    $otp = trim((string) ($_POST['otp'] ?? ''));

    $stmt = $pdo->prepare('SELECT * FROM registration_otps WHERE phone = ? AND nik = ? AND is_used = 0 ORDER BY id DESC LIMIT 1');
    $stmt->execute([$phone, $registrationKey]);
    $otpRow = $stmt->fetch();

    if (!$otpRow) {
        $_SESSION['flash'] = 'OTP registrasi tidak ditemukan. Silakan kirim ulang.';
        header('Location: /user/register');
        exit;
    }

    if ((string) $otpRow['otp_code'] !== $otp) {
        $_SESSION['flash'] = 'OTP tidak valid.';
        header('Location: /user/register/verify');
        exit;
    }

    if (strtotime((string) $otpRow['expires_at']) < time()) {
        $_SESSION['flash'] = 'OTP kadaluarsa. Silakan kirim ulang OTP registrasi.';
        header('Location: /user/register');
        exit;
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM users WHERE phone = ?');
    $stmt->execute([$phone]);
    if ((int) ($stmt->fetch()['total'] ?? 0) > 0) {
        $_SESSION['flash'] = 'Nomor HP sudah terdaftar. Silakan login.';
        header('Location: /user/login');
        exit;
    }

    $fullName = 'User ' . substr($phone, -4);
    $nikSynthetic = 'HP' . preg_replace('/\D/', '', $phone);
    $create = $pdo->prepare('INSERT INTO users (full_name, phone, nik, address, created_at) VALUES (?, ?, ?, ?, ?)');
    $create->execute([$fullName, $phone, $nikSynthetic, null, date('Y-m-d H:i:s')]);
    $userId = (int) $pdo->lastInsertId();

    $pdo->prepare('UPDATE registration_otps SET is_used = 1 WHERE id = ?')->execute([(int) $otpRow['id']]);

    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_phone'] = $phone;
    unset($_SESSION['register_phone']);

    $_SESSION['flash'] = 'Registrasi berhasil, Anda sudah login.';
    header('Location: /user/');
    exit;
}

if ($uri === '/user/login/send-otp' && $method === 'POST') {
    $phoneInput = trim((string) ($_POST['phone'] ?? ''));
    $normalized = normalizePhone($phoneInput);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
    $stmt->execute([$phoneInput]);
    $user = $stmt->fetch();

    if (!$user) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
        $stmt->execute([$normalized]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        $_SESSION['flash'] = 'Nomor handphone belum terdaftar.';
        header('Location: /user/login');
        exit;
    }

    $otp = generateOtpCode();
    $expires = (new DateTimeImmutable('+5 minutes'))->format('Y-m-d H:i:s');

    $pdo->prepare('UPDATE user_otps SET is_used = 1 WHERE user_id = ? AND is_used = 0')
        ->execute([(int) $user['id']]);

    $insert = $pdo->prepare('INSERT INTO user_otps (user_id, otp_code, expires_at, is_used, created_at) VALUES (?, ?, ?, 0, ?)');
    $insert->execute([(int) $user['id'], $otp, $expires, date('Y-m-d H:i:s')]);

    $sendResult = sendOtpViaWhatsApp((string) $user['phone'], $otp);

    if (!$sendResult['ok']) {
        $_SESSION['flash'] = $sendResult['message'];
        header('Location: /user/login');
        exit;
    }

    $_SESSION['otp_phone'] = (string) $user['phone'];
    $_SESSION['flash'] = $sendResult['message'];
    header('Location: /user/login/verify');
    exit;
}

if ($uri === '/user/login/resend-otp' && $method === 'POST') {
    if (isUserLoggedIn()) {
        header('Location: /user/');
        exit;
    }

    $phone = (string) ($_SESSION['otp_phone'] ?? '');
    if ($phone === '') {
        $_SESSION['flash'] = 'Silakan login dan kirim OTP terlebih dulu.';
        header('Location: /user/login');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['flash'] = 'User tidak ditemukan. Silakan login ulang.';
        header('Location: /user/login');
        exit;
    }

    $otp = generateOtpCode();
    $expires = (new DateTimeImmutable('+5 minutes'))->format('Y-m-d H:i:s');

    $pdo->prepare('UPDATE user_otps SET is_used = 1 WHERE user_id = ? AND is_used = 0')
        ->execute([(int) $user['id']]);

    $insert = $pdo->prepare('INSERT INTO user_otps (user_id, otp_code, expires_at, is_used, created_at) VALUES (?, ?, ?, 0, ?)');
    $insert->execute([(int) $user['id'], $otp, $expires, date('Y-m-d H:i:s')]);

    $sendResult = sendOtpViaWhatsApp((string) $user['phone'], $otp);
    if (!$sendResult['ok']) {
        $_SESSION['flash'] = $sendResult['message'];
        header('Location: /user/login/verify');
        exit;
    }

    $_SESSION['flash'] = 'OTP baru berhasil dikirim.';
    header('Location: /user/login/verify');
    exit;
}

if ($uri === '/user/login/verify' && $method === 'GET') {
    if (isUserLoggedIn()) {
        header('Location: /user/');
        exit;
    }
    $phone = (string) ($_SESSION['otp_phone'] ?? '');
    if ($phone === '') {
        $_SESSION['flash'] = 'Silakan kirim OTP terlebih dulu.';
        header('Location: /user/login');
        exit;
    }
    require __DIR__ . '/../user/login-verify.php';
    exit;
}

if ($uri === '/user/login/verify' && $method === 'POST') {
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $otp = trim((string) ($_POST['otp'] ?? ''));

    $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['flash'] = 'User tidak ditemukan.';
        header('Location: /user/login');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM user_otps WHERE user_id = ? AND is_used = 0 ORDER BY id DESC LIMIT 1');
    $stmt->execute([(int) $user['id']]);
    $otpRow = $stmt->fetch();

    if (!$otpRow) {
        $_SESSION['flash'] = 'OTP tidak ditemukan. Silakan kirim ulang.';
        header('Location: /user/login');
        exit;
    }

    if ((string) $otpRow['otp_code'] !== $otp) {
        $_SESSION['flash'] = 'OTP tidak valid.';
        header('Location: /user/login/verify');
        exit;
    }

    if (strtotime((string) $otpRow['expires_at']) < time()) {
        $_SESSION['flash'] = 'OTP kadaluarsa. Silakan kirim ulang OTP.';
        header('Location: /user/login');
        exit;
    }

    $pdo->prepare('UPDATE user_otps SET is_used = 1 WHERE id = ?')->execute([(int) $otpRow['id']]);

    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_phone'] = (string) $user['phone'];
    unset($_SESSION['otp_phone']);

    $_SESSION['flash'] = 'Login berhasil.';
    header('Location: /user/');
    exit;
}

if ($uri === '/user/logout') {
    unset($_SESSION['user_logged_in'], $_SESSION['user_id'], $_SESSION['user_phone'], $_SESSION['otp_phone']);
    unset($_SESSION['register_phone'], $_SESSION['register_nik']);
    $_SESSION['flash'] = 'Anda berhasil logout.';
    header('Location: /user/login');
    exit;
}

if (($uri === '/user' || $uri === '/user/') && $method === 'GET') {
    $title = 'Dashboard User - Titipangan';
    $isGuest = !isUserLoggedIn();
    $userProfile = null;
    $alreadyClaimed = false;
    $claimHistory = [];
    $pickupLocations = $pdo->query('SELECT id, location_name, address, city, province, google_maps_url, latitude, longitude FROM inventory_locations WHERE is_active = 1 AND latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY updated_at DESC, id DESC')->fetchAll();
    $dashboardStats = [
        'total_received' => 0,
        'claims_this_month' => 0,
        'telkomsel_points' => 0,
    ];

    if (!$isGuest) {
        $userId = (int) $_SESSION['user_id'];
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $userProfile = $stmt->fetch();

        if (!$userProfile) {
            unset($_SESSION['user_logged_in'], $_SESSION['user_id'], $_SESSION['user_phone']);
            $_SESSION['flash'] = 'Session user tidak valid. Silakan login ulang.';
            header('Location: /user/login');
            exit;
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM food_claims WHERE user_id = ? AND week_key = ?');
        $stmt->execute([$userProfile['id'], weekKey()]);
        $alreadyClaimed = (int) $stmt->fetch()['total'] > 0;

        $historyStmt = $pdo->prepare('SELECT claimed_at, week_key FROM food_claims WHERE user_id = ? ORDER BY claimed_at DESC LIMIT 50');
        $historyStmt->execute([$userProfile['id']]);
        $claimHistory = $historyStmt->fetchAll();

        $statsStmt = $pdo->prepare('
            SELECT
                COUNT(*) AS total_received,
                SUM(CASE WHEN DATE_FORMAT(claimed_at, "%Y-%m") = ? THEN 1 ELSE 0 END) AS claims_this_month
            FROM food_claims
            WHERE user_id = ?
        ');
        $statsStmt->execute([date('Y-m'), $userId]);
        $statsRow = $statsStmt->fetch() ?: [];
        $totalReceived = (int) ($statsRow['total_received'] ?? 0);
        $claimsThisMonth = (int) ($statsRow['claims_this_month'] ?? 0);
        $estimatedSaving = $claimsThisMonth * 35000;
        $dashboardStats = [
            'total_received' => $totalReceived,
            'claims_this_month' => $claimsThisMonth,
            'telkomsel_points' => (int) ($userProfile['points'] ?? 0),
        ];
    }

    $contentView = __DIR__ . '/../user/dashboard.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/user/profil' && $method === 'GET') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT id, full_name, phone, nik, points, address FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $userProfile = $stmt->fetch();

    if (!$userProfile) {
        unset($_SESSION['user_logged_in'], $_SESSION['user_id'], $_SESSION['user_phone']);
        $_SESSION['flash'] = 'Profil user tidak ditemukan. Silakan login ulang.';
        header('Location: /user/login');
        exit;
    }

    $title = 'Profil User';
    $contentView = __DIR__ . '/../user/profil.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/user/profil/update' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $nik = preg_replace('/\D/', '', (string) ($_POST['nik'] ?? '')) ?? '';
    $phoneInput = trim((string) ($_POST['phone'] ?? ''));
    $phone = normalizePhone($phoneInput);
    $address = trim((string) ($_POST['address'] ?? ''));

    if ($fullName === '') {
        $_SESSION['flash'] = 'Nama lengkap wajib diisi.';
        header('Location: /user/profil');
        exit;
    }

    if (strlen($nik) !== 16) {
        $_SESSION['flash'] = 'NIK harus 16 digit.';
        header('Location: /user/profil');
        exit;
    }

    if ($phone === '' || strlen($phone) < 10) {
        $_SESSION['flash'] = 'Nomor HP tidak valid.';
        header('Location: /user/profil');
        exit;
    }

    $currentStmt = $pdo->prepare('SELECT phone, full_name FROM users WHERE id = ? LIMIT 1');
    $currentStmt->execute([$userId]);
    $current = $currentStmt->fetch();
    if (!$current) {
        $_SESSION['flash'] = 'Profil tidak ditemukan.';
        header('Location: /user/profil');
        exit;
    }

    $dupStmt = $pdo->prepare('SELECT id FROM users WHERE nik = ? AND id <> ? LIMIT 1');
    $dupStmt->execute([$nik, $userId]);
    if ($dupStmt->fetch()) {
        $_SESSION['flash'] = 'NIK sudah digunakan oleh pengguna lain.';
        header('Location: /user/profil');
        exit;
    }

    $phoneDupStmt = $pdo->prepare('SELECT id FROM users WHERE phone = ? AND id <> ? LIMIT 1');
    $phoneDupStmt->execute([$phone, $userId]);
    if ($phoneDupStmt->fetch()) {
        $_SESSION['flash'] = 'Nomor HP sudah digunakan oleh pengguna lain.';
        header('Location: /user/profil');
        exit;
    }

    if ((string) $phone !== (string) $current['phone']) {
        $verifiedUser = (int) ($_SESSION['profile_phone_verified_user'] ?? 0);
        $verifiedPhone = (string) ($_SESSION['profile_phone_verified'] ?? '');
        if ($verifiedUser !== $userId || $verifiedPhone !== $phone) {
            $_SESSION['flash'] = 'Nomor HP baru harus diverifikasi OTP terlebih dulu.';
            header('Location: /user/profil');
            exit;
        }
    }

    $stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone = ?, nik = ?, address = ? WHERE id = ?');
    $stmt->execute([$fullName, $phone, $nik, $address !== '' ? $address : null, $userId]);
    $_SESSION['user_phone'] = $phone;
    unset($_SESSION['profile_phone_verified_user'], $_SESSION['profile_phone_verified'], $_SESSION['profile_phone_pending']);

    $_SESSION['flash'] = 'Profil berhasil diperbarui.';
    header('Location: /user/profil');
    exit;
}

if ($uri === '/user/profil/verify-nik' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $nik = preg_replace('/\D/', '', (string) ($_POST['nik'] ?? '')) ?? '';

    if ($fullName === '' || strlen($nik) !== 16) {
        jsonResponse(['ok' => false, 'message' => 'Isi nama lengkap dan NIK 16 digit terlebih dulu.'], 422);
    }

    $stmt = $pdo->prepare('SELECT id, full_name FROM users WHERE nik = ? LIMIT 1');
    $stmt->execute([$nik]);
    $nikOwner = $stmt->fetch();

    if ($nikOwner && (int) $nikOwner['id'] !== $userId) {
        jsonResponse(['ok' => false, 'message' => 'NIK sudah digunakan oleh pengguna lain.'], 409);
    }

    $currentStmt = $pdo->prepare('SELECT full_name FROM users WHERE id = ? LIMIT 1');
    $currentStmt->execute([$userId]);
    $current = $currentStmt->fetch();
    if (!$current) {
        jsonResponse(['ok' => false, 'message' => 'Profil tidak ditemukan.'], 404);
    }

    if (strcasecmp((string) $fullName, (string) $current['full_name']) !== 0) {
        jsonResponse(['ok' => false, 'message' => 'Nama tidak cocok (non case-sensitive) dengan data profil saat ini.'], 422);
    }

    jsonResponse(['ok' => true, 'message' => 'Verifikasi NIK berhasil. Nama cocok (non case-sensitive).']);
}

if ($uri === '/user/profil/verify-phone/send-otp' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $phoneInput = trim((string) ($_POST['phone'] ?? ''));
    $phone = normalizePhone($phoneInput);

    if ($phone === '' || strlen($phone) < 10) {
        jsonResponse(['ok' => false, 'message' => 'Nomor HP tidak valid.'], 422);
    }

    $dupStmt = $pdo->prepare('SELECT id FROM users WHERE phone = ? AND id <> ? LIMIT 1');
    $dupStmt->execute([$phone, $userId]);
    if ($dupStmt->fetch()) {
        jsonResponse(['ok' => false, 'message' => 'Nomor HP sudah digunakan user lain.'], 409);
    }

    $otp = generateOtpCode();
    $expires = (new DateTimeImmutable('+5 minutes'))->format('Y-m-d H:i:s');
    $pdo->prepare('UPDATE profile_phone_otps SET is_used = 1 WHERE user_id = ? AND phone = ? AND is_used = 0')->execute([$userId, $phone]);
    $insert = $pdo->prepare('INSERT INTO profile_phone_otps (user_id, phone, otp_code, expires_at, is_used, created_at) VALUES (?, ?, ?, ?, 0, ?)');
    $insert->execute([$userId, $phone, $otp, $expires, date('Y-m-d H:i:s')]);

    $sendResult = sendOtpViaWhatsApp($phone, $otp);
    if (!$sendResult['ok']) {
        jsonResponse(['ok' => false, 'message' => $sendResult['message'] ?? 'Gagal kirim OTP.'], 500);
    }

    $_SESSION['profile_phone_pending'] = $phone;
    jsonResponse(['ok' => true, 'message' => 'OTP verifikasi nomor HP berhasil dikirim.']);
}

if ($uri === '/user/profil/verify-phone/confirm' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $phoneInput = trim((string) ($_POST['phone'] ?? ''));
    $phone = normalizePhone($phoneInput);
    $otp = trim((string) ($_POST['otp'] ?? ''));

    if ($phone === '' || $otp === '') {
        jsonResponse(['ok' => false, 'message' => 'Nomor HP dan OTP wajib diisi.'], 422);
    }

    $stmt = $pdo->prepare('SELECT * FROM profile_phone_otps WHERE user_id = ? AND phone = ? AND is_used = 0 ORDER BY id DESC LIMIT 1');
    $stmt->execute([$userId, $phone]);
    $otpRow = $stmt->fetch();
    if (!$otpRow) {
        jsonResponse(['ok' => false, 'message' => 'OTP tidak ditemukan. Kirim ulang OTP.'], 404);
    }

    if ((string) $otpRow['otp_code'] !== $otp) {
        jsonResponse(['ok' => false, 'message' => 'OTP tidak valid.'], 422);
    }

    if (strtotime((string) $otpRow['expires_at']) < time()) {
        jsonResponse(['ok' => false, 'message' => 'OTP kadaluarsa. Kirim ulang OTP.'], 422);
    }

    $pdo->prepare('UPDATE profile_phone_otps SET is_used = 1 WHERE id = ?')->execute([(int) $otpRow['id']]);
    $_SESSION['profile_phone_verified_user'] = $userId;
    $_SESSION['profile_phone_verified'] = $phone;

    jsonResponse(['ok' => true, 'message' => 'Nomor HP berhasil diverifikasi.']);
}

if ($uri === '/user/profil/delete' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$userId]);

    unset($_SESSION['user_logged_in'], $_SESSION['user_id'], $_SESSION['user_phone'], $_SESSION['otp_phone'], $_SESSION['register_phone'], $_SESSION['register_nik']);
    $_SESSION['flash'] = 'Akun berhasil dihapus.';
    header('Location: /user/register');
    exit;
}

if ($uri === '/user/donasi' && $method === 'GET') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $pointsStmt = $pdo->prepare('SELECT points FROM users WHERE id = ? LIMIT 1');
    $pointsStmt->execute([$userId]);
    $pointsRow = $pointsStmt->fetch();
    $userPoints = (int) ($pointsRow['points'] ?? 0);
    $donationHistoryStmt = $pdo->prepare('SELECT donation_type, item_name, quantity, unit, points_awarded, created_at FROM user_donations WHERE user_id = ? ORDER BY created_at DESC LIMIT 50');
    $donationHistoryStmt->execute([$userId]);
    $donationHistory = $donationHistoryStmt->fetchAll();
    $title = 'Donasi Barang';
    $contentView = __DIR__ . '/../user/donasi.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/user/donasi' && $method === 'POST') {
    requireUser();
    $_SESSION['flash'] = 'Form donasi hanya tersedia di sisi admin. Silakan hubungi admin untuk input donasi.';
    header('Location: /user/donasi');
    exit;
}

if ($uri === '/user/volunteer' && $method === 'GET') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $pointsStmt = $pdo->prepare('SELECT points FROM users WHERE id = ? LIMIT 1');
    $pointsStmt->execute([$userId]);
    $pointsRow = $pointsStmt->fetch();
    $userPoints = (int) ($pointsRow['points'] ?? 0);
    $volunteerLocations = $pdo->query('SELECT id, location_name, city, province FROM volunteer_locations WHERE is_active = 1 ORDER BY location_name ASC')->fetchAll();
    $title = 'Volunteer';
    $contentView = __DIR__ . '/../user/volunteer.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/user/reward' && $method === 'GET') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $pointsStmt = $pdo->prepare('SELECT points FROM users WHERE id = ? LIMIT 1');
    $pointsStmt->execute([$userId]);
    $pointsRow = $pointsStmt->fetch();
    $userPoints = (int) ($pointsRow['points'] ?? 0);

    $rewardCatalogStmt = $pdo->query('SELECT reward_code AS code, reward_name AS name, category, points_needed AS points, description, image_path FROM reward_catalog WHERE is_active = 1 ORDER BY points_needed ASC, id ASC');
    $rewardCatalog = $rewardCatalogStmt->fetchAll();

    $historyStmt = $pdo->prepare('SELECT reward_name, points_spent, created_at FROM user_reward_redemptions WHERE user_id = ? ORDER BY created_at DESC LIMIT 20');
    $historyStmt->execute([$userId]);
    $rewardHistory = $historyStmt->fetchAll();

    $title = 'Reward Points';
    $contentView = __DIR__ . '/../user/reward.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/user/reward/redeem' && $method === 'POST') {
    requireUser();
    $userId = (int) $_SESSION['user_id'];
    $rewardCode = trim((string) ($_POST['reward_code'] ?? ''));

    $rewardStmt = $pdo->prepare('SELECT reward_name, points_needed FROM reward_catalog WHERE reward_code = ? AND is_active = 1 LIMIT 1');
    $rewardStmt->execute([$rewardCode]);
    $rewardRow = $rewardStmt->fetch();
    if (!$rewardRow) {
        $_SESSION['flash'] = 'Reward tidak ditemukan.';
        header('Location: /user/reward');
        exit;
    }

    $pointsNeeded = (int) ($rewardRow['points_needed'] ?? 0);
    $userStmt = $pdo->prepare('SELECT points FROM users WHERE id = ? LIMIT 1');
    $userStmt->execute([$userId]);
    $userRow = $userStmt->fetch();
    $currentPoints = (int) ($userRow['points'] ?? 0);

    if ($currentPoints < $pointsNeeded) {
        $_SESSION['flash'] = 'Points Anda belum cukup untuk redeem reward ini.';
        header('Location: /user/reward');
        exit;
    }

    $pdo->beginTransaction();
    try {
        $updatePoints = $pdo->prepare('UPDATE users SET points = points - ? WHERE id = ?');
        $updatePoints->execute([$pointsNeeded, $userId]);

        $insertRedeem = $pdo->prepare('INSERT INTO user_reward_redemptions (user_id, reward_code, reward_name, points_spent, created_at) VALUES (?, ?, ?, ?, ?)');
        $insertRedeem->execute([$userId, $rewardCode, (string) $rewardRow['reward_name'], $pointsNeeded, date('Y-m-d H:i:s')]);

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }

    $_SESSION['flash'] = 'Redeem berhasil untuk ' . ((string) $rewardRow['reward_name']) . '.';
    header('Location: /user/reward');
    exit;
}

if ($uri === '/user/volunteer' && $method === 'POST') {
    requireUser();

    $activityType = trim((string) ($_POST['activity_type'] ?? ''));
    $volunteerDate = trim((string) ($_POST['volunteer_date'] ?? ''));
    $fixedDurationHours = 1;
    $locationId = (int) ($_POST['location_id'] ?? 0);
    $notes = trim((string) ($_POST['notes'] ?? ''));
    $experienceTexts = $_POST['experience_texts'] ?? [];
    if (!is_array($experienceTexts)) {
        $experienceTexts = [];
    }

    $experienceRecords = [];
    $maxRows = max(count($experienceTexts), (int) ($_FILES['experience_photos']['name'] ? count((array) $_FILES['experience_photos']['name']) : 0));
    for ($i = 0; $i < $maxRows; $i++) {
        $text = trim((string) ($experienceTexts[$i] ?? ''));
        $photoPath = null;

        $fileItem = [
            'name' => $_FILES['experience_photos']['name'][$i] ?? null,
            'type' => $_FILES['experience_photos']['type'][$i] ?? null,
            'tmp_name' => $_FILES['experience_photos']['tmp_name'][$i] ?? null,
            'error' => $_FILES['experience_photos']['error'][$i] ?? UPLOAD_ERR_NO_FILE,
            'size' => $_FILES['experience_photos']['size'][$i] ?? 0,
        ];

        try {
            $photoPath = storeVolunteerExperiencePhoto($fileItem);
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = $e->getMessage();
            header('Location: /user/volunteer');
            exit;
        }

        if ($text === '' && $photoPath === null) {
            continue;
        }
        $experienceRecords[] = ['text' => $text !== '' ? $text : null, 'photo' => $photoPath];
    }

    if ($activityType === '' || $volunteerDate === '' || $locationId <= 0) {
        $_SESSION['flash'] = 'Mohon isi jenis aktivitas, tanggal volunteer, dan lokasi.';
        header('Location: /user/volunteer');
        exit;
    }

    $locationStmt = $pdo->prepare('SELECT location_name, city, province FROM volunteer_locations WHERE id = ? AND is_active = 1 LIMIT 1');
    $locationStmt->execute([$locationId]);
    $locationRow = $locationStmt->fetch();
    if (!$locationRow) {
        $_SESSION['flash'] = 'Lokasi volunteer tidak valid.';
        header('Location: /user/volunteer');
        exit;
    }
    $locationParts = [(string) $locationRow['location_name']];
    if (!empty($locationRow['city'])) {
        $locationParts[] = (string) $locationRow['city'];
    }
    if (!empty($locationRow['province'])) {
        $locationParts[] = (string) $locationRow['province'];
    }
    $location = implode(', ', $locationParts);

    $pointsAwarded = 5000;

    $userId = (int) $_SESSION['user_id'];
    $pdo->beginTransaction();
    try {
        $volunteerStmt = $pdo->prepare('INSERT INTO user_volunteers (user_id, activity_type, volunteer_date, duration_hours, location, experience_text, experience_photo_path, notes, points_awarded, approval_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "pending", ?)');
        $volunteerStmt->execute([
            $userId,
            $activityType,
            $volunteerDate,
            $fixedDurationHours,
            $location !== '' ? $location : null,
            null,
            null,
            $notes !== '' ? $notes : null,
            $pointsAwarded,
            date('Y-m-d H:i:s'),
        ]);
        $volunteerId = (int) $pdo->lastInsertId();

        if (!empty($experienceRecords)) {
            $expStmt = $pdo->prepare('INSERT INTO user_volunteer_experiences (volunteer_id, experience_text, experience_photo_path, created_at) VALUES (?, ?, ?, ?)');
            foreach ($experienceRecords as $record) {
                $expStmt->execute([
                    $volunteerId,
                    $record['text'],
                    $record['photo'],
                    date('Y-m-d H:i:s'),
                ]);
            }
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
    $_SESSION['flash'] = 'Pengajuan volunteer berhasil dikirim. Menunggu ACC admin. Poin +' . $pointsAwarded . ' akan ditambahkan setelah disetujui.';
    header('Location: /user/volunteer');
    exit;
}

if ($uri === '/claim' && $method === 'POST') {
    requireUser();

    $userId = (int) $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM food_claims WHERE user_id = ? AND week_key = ?');
    $stmt->execute([$userId, weekKey()]);
    $claimed = (int) $stmt->fetch()['total'] > 0;

    if ($claimed) {
        $_SESSION['flash'] = 'Gagal: Anda sudah mengambil makanan pada minggu ini.';
        header('Location: /user/');
        exit;
    }

    $today = date('Y-m-d');
    $currentWeek = weekKey();
    $pdo->prepare('UPDATE pickup_redeems SET status = "expired" WHERE user_id = ? AND status = "pending" AND code_date < ?')
        ->execute([$userId, $today]);

    $pickupCode = dailyPickupCode($userId);
    $pendingStmt = $pdo->prepare('SELECT * FROM pickup_redeems WHERE user_id = ? AND code_date = ? AND week_key = ? AND status = "pending" ORDER BY id DESC LIMIT 1');
    $pendingStmt->execute([$userId, $today, $currentWeek]);
    $pending = $pendingStmt->fetch();

    if (!$pending) {
        $insert = $pdo->prepare('INSERT INTO pickup_redeems (user_id, redeem_code, code_date, week_key, status, created_at) VALUES (?, ?, ?, ?, "pending", ?)');
        $insert->execute([$userId, $pickupCode, $today, $currentWeek, date('Y-m-d H:i:s')]);
    } else {
        $pickupCode = (string) ($pending['redeem_code'] ?? $pickupCode);
    }

    $_SESSION['pickup_qr'] = [
        'code' => $pickupCode,
        'date' => $today,
    ];
    $_SESSION['flash'] = 'Kode pengambilan berhasil dibuat. Silakan redeem ke admin terlebih dulu.';

    header('Location: /user/');
    exit;
}

if ($uri === '/regulasi-donatur' && $method === 'GET') {
    header('Location: /dokumen-regulasi');
    exit;
}

if ($uri === '/regulasi-konsumen' && $method === 'GET') {
    requireUser();
    header('Location: /dokumen-regulasi');
    exit;
}

if ($uri === '/dokumen-regulasi' && $method === 'GET') {
    requireUser();
    $title = 'Dokumen & Regulasi';
    $contentView = __DIR__ . '/../user/dokumen-regulasi.php';
    require __DIR__ . '/../layouts/app.php';
    exit;
}

if ($uri === '/admin/login' && $method === 'GET') {
    if (isAdminLoggedIn()) {
        header('Location: /admin');
        exit;
    }

    $error = $_SESSION['login_error'] ?? null;
    unset($_SESSION['login_error']);
    require __DIR__ . '/../admin/login.php';
    exit;
}

if ($uri === '/admin/login' && $method === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === env('ADMIN_USERNAME', 'admin') && $password === env('ADMIN_PASSWORD', 'admin123')) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: /admin');
        exit;
    }

    $_SESSION['login_error'] = 'Username atau password salah.';
    header('Location: /admin/login');
    exit;
}

if ($uri === '/admin/logout') {
    unset($_SESSION['admin_logged_in']);
    header('Location: /admin/login');
    exit;
}

if (str_starts_with($uri, '/admin')) {
    requireAdmin();
    $role = 'admin';

    if ($uri === '/admin' && $method === 'GET') {
        $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM food_claims WHERE week_key = ?');
        $stmt->execute([weekKey()]);
        $stats = [
            'users' => (int) $pdo->query('SELECT COUNT(*) as total FROM users')->fetch()['total'],
            'inventory' => (int) $pdo->query('SELECT COUNT(*) as total FROM inventory_items')->fetch()['total'],
            'claims_week' => (int) $stmt->fetch()['total'],
        ];

        $title = 'Dashboard Admin';
        $contentView = __DIR__ . '/../admin/dashboard.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/inventory' && $method === 'GET') {
        $inventory = $pdo->query('SELECT * FROM inventory_items ORDER BY updated_at DESC')->fetchAll();
        $inventoryCategories = inventoryCategoryMap();
        $title = 'Kelola Inventori';
        $contentView = __DIR__ . '/../admin/inventory.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/inventory/create' && $method === 'POST') {
        $itemName = trim((string) ($_POST['item_name'] ?? ''));
        $stock = (int) ($_POST['stock'] ?? 0);
        $unit = 'pcs';
        $perPcsInfo = trim((string) ($_POST['per_pcs_info'] ?? ''));
        $category = normalizeInventoryCategory((string) ($_POST['category'] ?? ''));
        $imagePath = null;

        try {
            if (isset($_FILES['image'])) {
                $imagePath = storeInventoryImage($_FILES['image']);
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = $e->getMessage();
            header('Location: /admin/inventory');
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO inventory_items (item_name, stock, unit, per_pcs_info, category, image_path, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$itemName, $stock, $unit, $perPcsInfo !== '' ? $perPcsInfo : null, $category, $imagePath, date('Y-m-d H:i:s')]);

        $defaultLocation = $pdo->query('SELECT id FROM inventory_locations ORDER BY id ASC LIMIT 1')->fetch();
        if ($defaultLocation) {
            $mapStmt = $pdo->prepare('INSERT INTO inventory_item_location_stocks (inventory_item_id, location_id, stock, updated_at) VALUES (?, ?, ?, ?)');
            $mapStmt->execute([(int) $pdo->lastInsertId(), (int) $defaultLocation['id'], $stock, date('Y-m-d H:i:s')]);
        }
        header('Location: /admin/inventory');
        exit;
    }

    if ($uri === '/admin/inventory/delete' && $method === 'POST') {
        $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = ?');
        $stmt->execute([(int) ($_POST['id'] ?? 0)]);
        header('Location: /admin/inventory');
        exit;
    }

    if ($uri === '/admin/inventory/update' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $itemName = trim((string) ($_POST['item_name'] ?? ''));
        $stock = max(0, (int) ($_POST['stock'] ?? 0));
        $unit = 'pcs';
        $perPcsInfo = trim((string) ($_POST['per_pcs_info'] ?? ''));
        $category = normalizeInventoryCategory((string) ($_POST['category'] ?? ''));
        $imagePath = null;

        try {
            if (isset($_FILES['image'])) {
                $imagePath = storeInventoryImage($_FILES['image']);
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = $e->getMessage();
            header('Location: /admin/inventory');
            exit;
        }

        if ($id > 0 && $itemName !== '' && $unit !== '') {
            if ($imagePath !== null) {
                $stmt = $pdo->prepare('UPDATE inventory_items SET item_name = ?, stock = ?, unit = ?, per_pcs_info = ?, category = ?, image_path = ?, updated_at = ? WHERE id = ?');
                $stmt->execute([$itemName, $stock, $unit, $perPcsInfo !== '' ? $perPcsInfo : null, $category, $imagePath, date('Y-m-d H:i:s'), $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE inventory_items SET item_name = ?, stock = ?, unit = ?, per_pcs_info = ?, category = ?, updated_at = ? WHERE id = ?');
                $stmt->execute([$itemName, $stock, $unit, $perPcsInfo !== '' ? $perPcsInfo : null, $category, date('Y-m-d H:i:s'), $id]);
            }

            $defaultLocation = $pdo->query('SELECT id FROM inventory_locations ORDER BY id ASC LIMIT 1')->fetch();
            if ($defaultLocation) {
                $defaultLocationId = (int) $defaultLocation['id'];
                $pdo->prepare('DELETE FROM inventory_item_location_stocks WHERE inventory_item_id = ? AND location_id <> ?')->execute([$id, $defaultLocationId]);
                $upsert = $pdo->prepare('INSERT INTO inventory_item_location_stocks (inventory_item_id, location_id, stock, updated_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE stock = VALUES(stock), updated_at = VALUES(updated_at)');
                $upsert->execute([$id, $defaultLocationId, $stock, date('Y-m-d H:i:s')]);
            }
        }

        header('Location: /admin/inventory');
        exit;
    }

    if ($uri === '/admin/rewards' && $method === 'GET') {
        $rewardCatalog = $pdo->query('SELECT * FROM reward_catalog ORDER BY points_needed ASC, id ASC')->fetchAll();
        $title = 'Kelola Reward';
        $contentView = __DIR__ . '/../admin/rewards.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/rewards/create' && $method === 'POST') {
        $rewardCode = strtoupper(trim((string) ($_POST['reward_code'] ?? '')));
        $rewardName = trim((string) ($_POST['reward_name'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? ''));
        $pointsNeeded = max(0, (int) ($_POST['points_needed'] ?? 0));
        $description = trim((string) ($_POST['description'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $imagePath = null;

        if ($rewardCode === '' || $rewardName === '' || $category === '' || $pointsNeeded <= 0) {
            $_SESSION['flash'] = 'Mohon lengkapi kode, nama, kategori, dan points reward.';
            header('Location: /admin/rewards');
            exit;
        }

        try {
            if (isset($_FILES['image'])) {
                $imagePath = storeRewardImage($_FILES['image']);
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = $e->getMessage();
            header('Location: /admin/rewards');
            exit;
        }

        $existsStmt = $pdo->prepare('SELECT id FROM reward_catalog WHERE reward_code = ? LIMIT 1');
        $existsStmt->execute([$rewardCode]);
        if ($existsStmt->fetch()) {
            $_SESSION['flash'] = 'Kode reward sudah digunakan.';
            header('Location: /admin/rewards');
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO reward_catalog (reward_code, reward_name, category, points_needed, description, image_path, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$rewardCode, $rewardName, $category, $pointsNeeded, $description !== '' ? $description : null, $imagePath, $isActive, $now, $now]);
        $_SESSION['flash'] = 'Reward baru berhasil ditambahkan.';
        header('Location: /admin/rewards');
        exit;
    }

    if ($uri === '/admin/rewards/update' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $rewardCode = strtoupper(trim((string) ($_POST['reward_code'] ?? '')));
        $rewardName = trim((string) ($_POST['reward_name'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? ''));
        $pointsNeeded = max(0, (int) ($_POST['points_needed'] ?? 0));
        $description = trim((string) ($_POST['description'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $imagePath = null;

        if ($id <= 0 || $rewardCode === '' || $rewardName === '' || $category === '' || $pointsNeeded <= 0) {
            $_SESSION['flash'] = 'Data reward tidak valid.';
            header('Location: /admin/rewards');
            exit;
        }

        try {
            if (isset($_FILES['image'])) {
                $imagePath = storeRewardImage($_FILES['image']);
            }
        } catch (RuntimeException $e) {
            $_SESSION['flash'] = $e->getMessage();
            header('Location: /admin/rewards');
            exit;
        }

        $dupStmt = $pdo->prepare('SELECT id FROM reward_catalog WHERE reward_code = ? AND id <> ? LIMIT 1');
        $dupStmt->execute([$rewardCode, $id]);
        if ($dupStmt->fetch()) {
            $_SESSION['flash'] = 'Kode reward sudah digunakan item lain.';
            header('Location: /admin/rewards');
            exit;
        }

        if ($imagePath !== null) {
            $stmt = $pdo->prepare('UPDATE reward_catalog SET reward_code = ?, reward_name = ?, category = ?, points_needed = ?, description = ?, image_path = ?, is_active = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$rewardCode, $rewardName, $category, $pointsNeeded, $description !== '' ? $description : null, $imagePath, $isActive, date('Y-m-d H:i:s'), $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE reward_catalog SET reward_code = ?, reward_name = ?, category = ?, points_needed = ?, description = ?, is_active = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$rewardCode, $rewardName, $category, $pointsNeeded, $description !== '' ? $description : null, $isActive, date('Y-m-d H:i:s'), $id]);
        }

        $_SESSION['flash'] = 'Reward berhasil diperbarui.';
        header('Location: /admin/rewards');
        exit;
    }

    if ($uri === '/admin/rewards/delete' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM reward_catalog WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['flash'] = 'Reward berhasil dihapus.';
        }
        header('Location: /admin/rewards');
        exit;
    }

    if ($uri === '/admin/claims' && $method === 'GET') {
        $sql = 'SELECT fc.*, u.full_name, u.phone FROM food_claims fc JOIN users u ON u.id = fc.user_id ORDER BY fc.claimed_at DESC';
        $claims = $pdo->query($sql)->fetchAll();
        $pendingRedeems = $pdo->query('SELECT pr.*, u.full_name, u.phone FROM pickup_redeems pr JOIN users u ON u.id = pr.user_id WHERE pr.status = "pending" ORDER BY pr.created_at DESC LIMIT 50')->fetchAll();
        $title = 'Monitor Klaim';
        $contentView = __DIR__ . '/../admin/claims.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/claims/redeem' && $method === 'POST') {
        $redeemCode = strtoupper(trim((string) ($_POST['redeem_code'] ?? '')));
        if ($redeemCode === '') {
            $_SESSION['flash'] = 'Kode redeem wajib diisi.';
            header('Location: /admin/claims');
            exit;
        }

        $redeemStmt = $pdo->prepare('SELECT * FROM pickup_redeems WHERE redeem_code = ? AND status = "pending" ORDER BY id DESC LIMIT 1');
        $redeemStmt->execute([$redeemCode]);
        $redeem = $redeemStmt->fetch();

        if (!$redeem) {
            $_SESSION['flash'] = 'Kode redeem tidak ditemukan atau sudah digunakan.';
            header('Location: /admin/claims');
            exit;
        }

        $userId = (int) $redeem['user_id'];
        $week = (string) $redeem['week_key'];
        $checkClaim = $pdo->prepare('SELECT COUNT(*) AS total FROM food_claims WHERE user_id = ? AND week_key = ?');
        $checkClaim->execute([$userId, $week]);
        if ((int) ($checkClaim->fetch()['total'] ?? 0) > 0) {
            $pdo->prepare('UPDATE pickup_redeems SET status = "expired" WHERE id = ?')->execute([(int) $redeem['id']]);
            $_SESSION['flash'] = 'User sudah memiliki pengambilan pada minggu tersebut.';
            header('Location: /admin/claims');
            exit;
        }

        $pdo->beginTransaction();
        try {
            $insertClaim = $pdo->prepare('INSERT INTO food_claims (user_id, claimed_at, week_key) VALUES (?, ?, ?)');
            $insertClaim->execute([$userId, date('Y-m-d H:i:s'), $week]);

            $markRedeemed = $pdo->prepare('UPDATE pickup_redeems SET status = "redeemed", redeemed_at = ?, redeemed_by = ? WHERE id = ?');
            $markRedeemed->execute([date('Y-m-d H:i:s'), (string) env('ADMIN_USERNAME', 'admin'), (int) $redeem['id']]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        $userStmt = $pdo->prepare('SELECT full_name, phone FROM users WHERE id = ? LIMIT 1');
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();
        $waStatusSuffix = '';
        if ($user && !empty($user['phone'])) {
            $waMessage = "Halo {$user['full_name']}, pengambilan makanan Anda berhasil diredeem admin pada " . date('d-m-Y H:i') . ". Terima kasih telah menggunakan Titipangan.";
            $waResult = sendWhatsAppMessage(normalizePhone((string) $user['phone']), $waMessage, 'redeem_code=' . $redeemCode);
            if (!$waResult['ok']) {
                $waStatusSuffix = ' Namun notifikasi WhatsApp gagal dikirim.';
            }
        }

        $_SESSION['flash'] = 'Kode berhasil diredeem. Pengambilan makanan sukses dicatat.' . $waStatusSuffix;
        header('Location: /admin/claims');
        exit;
    }

    if ($uri === '/admin/claims/delete' && $method === 'POST') {
        $claimId = (int) ($_POST['claim_id'] ?? 0);
        if ($claimId > 0) {
            $deleteStmt = $pdo->prepare('DELETE FROM food_claims WHERE id = ?');
            $deleteStmt->execute([$claimId]);
            $_SESSION['flash'] = 'Riwayat pengambilan berhasil dihapus.';
        }
        header('Location: /admin/claims');
        exit;
    }

    if ($uri === '/admin/donasi' && $method === 'GET') {
        $sql = 'SELECT ud.*, u.full_name, u.phone FROM user_donations ud JOIN users u ON u.id = ud.user_id ORDER BY ud.created_at DESC';
        $adminDonations = $pdo->query($sql)->fetchAll();
        $donationCatalogItems = $pdo->query('SELECT id, item_name, unit FROM donation_catalog_items ORDER BY item_name ASC')->fetchAll();
        $title = 'Form Donasi';
        $contentView = __DIR__ . '/../admin/donasi.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/donasi/items/create' && $method === 'POST') {
        $itemName = trim((string) ($_POST['item_name'] ?? ''));
        $unit = trim((string) ($_POST['unit'] ?? 'pcs'));
        if ($itemName === '' || $unit === '') {
            $_SESSION['flash'] = 'Nama barang dan satuan wajib diisi.';
            header('Location: /admin/donasi');
            exit;
        }

        $existsStmt = $pdo->prepare('SELECT id FROM donation_catalog_items WHERE item_name = ? LIMIT 1');
        $existsStmt->execute([$itemName]);
        if ($existsStmt->fetch()) {
            $_SESSION['flash'] = 'Nama barang sudah ada di katalog.';
            header('Location: /admin/donasi');
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO donation_catalog_items (item_name, unit, created_at, updated_at) VALUES (?, ?, ?, ?)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$itemName, $unit, $now, $now]);
        $_SESSION['flash'] = 'Nama barang baru berhasil ditambahkan.';
        header('Location: /admin/donasi');
        exit;
    }

    if ($uri === '/admin/donasi/create' && $method === 'POST') {
        $phoneInput = trim((string) ($_POST['phone'] ?? ''));
        $normalizedPhone = normalizePhone($phoneInput);
        $donationType = trim((string) ($_POST['donation_type'] ?? ''));
        $itemId = (int) ($_POST['item_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
        $itemCondition = trim((string) ($_POST['item_condition'] ?? ''));
        $pickupAddress = trim((string) ($_POST['pickup_address'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        if ($phoneInput === '' || $donationType === '' || $itemId <= 0) {
            $_SESSION['flash'] = 'Mohon lengkapi nomor HP, jenis donasi, dan nama barang.';
            header('Location: /admin/donasi');
            exit;
        }

        $catalogStmt = $pdo->prepare('SELECT item_name, unit FROM donation_catalog_items WHERE id = ? LIMIT 1');
        $catalogStmt->execute([$itemId]);
        $catalogItem = $catalogStmt->fetch();
        if (!$catalogItem) {
            $_SESSION['flash'] = 'Barang donasi tidak ditemukan di katalog.';
            header('Location: /admin/donasi');
            exit;
        }
        $itemName = (string) $catalogItem['item_name'];
        $unit = (string) $catalogItem['unit'];

        $userStmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
        $userStmt->execute([$phoneInput]);
        $user = $userStmt->fetch();

        if (!$user) {
            $userStmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
            $userStmt->execute([$normalizedPhone]);
            $user = $userStmt->fetch();
        }

        if (!$user) {
            $_SESSION['flash'] = 'User dengan nomor handphone tersebut tidak ditemukan.';
            header('Location: /admin/donasi');
            exit;
        }

        $pointsAwarded = $quantity * 10;
        $userId = (int) $user['id'];

        $pdo->beginTransaction();
        try {
            $donationStmt = $pdo->prepare('INSERT INTO user_donations (user_id, donation_type, item_name, quantity, unit, item_condition, pickup_address, notes, points_awarded, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $donationStmt->execute([
                $userId,
                $donationType,
                $itemName,
                $quantity,
                $unit,
                $itemCondition !== '' ? $itemCondition : null,
                $pickupAddress !== '' ? $pickupAddress : null,
                $notes !== '' ? $notes : null,
                $pointsAwarded,
                date('Y-m-d H:i:s'),
            ]);

            $pointsStmt = $pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?');
            $pointsStmt->execute([$pointsAwarded, $userId]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        $_SESSION['flash'] = 'Donasi berhasil ditambahkan untuk ' . ((string) $user['full_name']) . '. Poin +' . $pointsAwarded . ' telah diberikan.';
        header('Location: /admin/donasi');
        exit;
    }

    if ($uri === '/admin/claims/create' && $method === 'POST') {
        $phoneInput = trim((string) ($_POST['phone'] ?? ''));
        $normalizedPhone = normalizePhone($phoneInput);

        if ($phoneInput === '') {
            $_SESSION['flash'] = 'Nomor handphone wajib diisi.';
            header('Location: /admin/claims');
            exit;
        }

        $userStmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
        $userStmt->execute([$phoneInput]);
        $user = $userStmt->fetch();

        if (!$user) {
            $userStmt = $pdo->prepare('SELECT * FROM users WHERE phone = ? LIMIT 1');
            $userStmt->execute([$normalizedPhone]);
            $user = $userStmt->fetch();
        }

        if (!$user) {
            $_SESSION['flash'] = 'User dengan nomor handphone tersebut tidak ditemukan.';
            header('Location: /admin/claims');
            exit;
        }

        $userId = (int) $user['id'];
        $claimCheckStmt = $pdo->prepare('SELECT COUNT(*) AS total FROM food_claims WHERE user_id = ? AND week_key = ?');
        $claimCheckStmt->execute([$userId, weekKey()]);
        $alreadyClaimed = (int) ($claimCheckStmt->fetch()['total'] ?? 0) > 0;

        if ($alreadyClaimed) {
            $_SESSION['flash'] = 'User sudah melakukan pengambilan pada minggu ini.';
            header('Location: /admin/claims');
            exit;
        }

        $pdo->beginTransaction();
        try {
            $insertClaimStmt = $pdo->prepare('INSERT INTO food_claims (user_id, claimed_at, week_key) VALUES (?, ?, ?)');
            $insertClaimStmt->execute([$userId, date('Y-m-d H:i:s'), weekKey()]);

            $pointsAwarded = 10;
            $pointsStmt = $pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?');
            $pointsStmt->execute([$pointsAwarded, $userId]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        $_SESSION['flash'] = 'Pengambilan baru berhasil ditambahkan untuk ' . ((string) $user['full_name']) . '.';
        header('Location: /admin/claims');
        exit;
    }

    if ($uri === '/admin/lokasi-volunteer' && $method === 'GET') {
        $volunteerLocations = $pdo->query('SELECT * FROM volunteer_locations ORDER BY updated_at DESC, id DESC')->fetchAll();
        $title = 'Master Lokasi Volunteer';
        $contentView = __DIR__ . '/../admin/lokasi-volunteer.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/lokasi-volunteer/create' && $method === 'POST') {
        $locationName = trim((string) ($_POST['location_name'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $city = trim((string) ($_POST['city'] ?? ''));
        $province = trim((string) ($_POST['province'] ?? ''));
        $mapsUrl = trim((string) ($_POST['google_maps_url'] ?? ''));
        $latitudeInput = trim((string) ($_POST['latitude'] ?? ''));
        $longitudeInput = trim((string) ($_POST['longitude'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($locationName === '') {
            $_SESSION['flash'] = 'Nama tempat wajib diisi.';
            header('Location: /admin/lokasi-volunteer');
            exit;
        }

        $latitude = $latitudeInput !== '' ? (float) $latitudeInput : null;
        $longitude = $longitudeInput !== '' ? (float) $longitudeInput : null;
        $now = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare('INSERT INTO volunteer_locations (location_name, address, city, province, google_maps_url, latitude, longitude, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $locationName,
            $address !== '' ? $address : null,
            $city !== '' ? $city : null,
            $province !== '' ? $province : null,
            $mapsUrl !== '' ? $mapsUrl : null,
            $latitude,
            $longitude,
            $isActive,
            $now,
            $now,
        ]);

        $_SESSION['flash'] = 'Lokasi volunteer baru berhasil ditambahkan.';
        header('Location: /admin/lokasi-volunteer');
        exit;
    }

    if ($uri === '/admin/lokasi-volunteer/update' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $locationName = trim((string) ($_POST['location_name'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $city = trim((string) ($_POST['city'] ?? ''));
        $province = trim((string) ($_POST['province'] ?? ''));
        $mapsUrl = trim((string) ($_POST['google_maps_url'] ?? ''));
        $latitudeInput = trim((string) ($_POST['latitude'] ?? ''));
        $longitudeInput = trim((string) ($_POST['longitude'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($id <= 0 || $locationName === '') {
            $_SESSION['flash'] = 'Data lokasi tidak valid.';
            header('Location: /admin/lokasi-volunteer');
            exit;
        }

        $latitude = $latitudeInput !== '' ? (float) $latitudeInput : null;
        $longitude = $longitudeInput !== '' ? (float) $longitudeInput : null;

        $stmt = $pdo->prepare('UPDATE volunteer_locations SET location_name = ?, address = ?, city = ?, province = ?, google_maps_url = ?, latitude = ?, longitude = ?, is_active = ?, updated_at = ? WHERE id = ?');
        $stmt->execute([
            $locationName,
            $address !== '' ? $address : null,
            $city !== '' ? $city : null,
            $province !== '' ? $province : null,
            $mapsUrl !== '' ? $mapsUrl : null,
            $latitude,
            $longitude,
            $isActive,
            date('Y-m-d H:i:s'),
            $id,
        ]);

        $_SESSION['flash'] = 'Lokasi volunteer berhasil diperbarui.';
        header('Location: /admin/lokasi-volunteer');
        exit;
    }

    if ($uri === '/admin/lokasi-volunteer/delete' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'Lokasi tidak valid.';
            header('Location: /admin/lokasi-volunteer');
            exit;
        }

        $stmt = $pdo->prepare('DELETE FROM volunteer_locations WHERE id = ?');
        $stmt->execute([$id]);

        $_SESSION['flash'] = 'Lokasi volunteer berhasil dihapus.';
        header('Location: /admin/lokasi-volunteer');
        exit;
    }

    if ($uri === '/admin/volunteers' && $method === 'GET') {
        $sql = 'SELECT uv.*, u.full_name, u.phone FROM user_volunteers uv JOIN users u ON u.id = uv.user_id ORDER BY uv.created_at DESC';
        $volunteerSubmissions = $pdo->query($sql)->fetchAll();
        $volunteerExperiencesMap = [];
        if (!empty($volunteerSubmissions)) {
            $ids = array_map(static fn(array $row): int => (int) $row['id'], $volunteerSubmissions);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $expStmt = $pdo->prepare("SELECT volunteer_id, experience_text, experience_photo_path FROM user_volunteer_experiences WHERE volunteer_id IN ($placeholders) ORDER BY id ASC");
            $expStmt->execute($ids);
            foreach ($expStmt->fetchAll() as $exp) {
                $volId = (int) $exp['volunteer_id'];
                if (!isset($volunteerExperiencesMap[$volId])) {
                    $volunteerExperiencesMap[$volId] = [];
                }
                $volunteerExperiencesMap[$volId][] = $exp;
            }
        }
        $title = 'ACC Volunteer';
        $contentView = __DIR__ . '/../admin/volunteers.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }

    if ($uri === '/admin/volunteers/status' && $method === 'POST') {
        $volunteerId = (int) ($_POST['volunteer_id'] ?? 0);
        $newStatus = strtolower(trim((string) ($_POST['approval_status'] ?? 'pending')));
        $allowed = ['pending', 'approved', 'rejected'];

        if ($volunteerId <= 0 || !in_array($newStatus, $allowed, true)) {
            jsonResponse(['ok' => false, 'message' => 'Data status tidak valid.'], 422);
        }

        $stmt = $pdo->prepare('SELECT * FROM user_volunteers WHERE id = ? LIMIT 1');
        $stmt->execute([$volunteerId]);
        $volunteer = $stmt->fetch();
        if (!$volunteer) {
            jsonResponse(['ok' => false, 'message' => 'Data volunteer tidak ditemukan.'], 404);
        }

        $oldStatus = strtolower((string) ($volunteer['approval_status'] ?? 'pending'));
        if ($oldStatus === $newStatus) {
            jsonResponse(['ok' => true, 'message' => 'Status tidak berubah.', 'status' => $newStatus]);
        }

        $userId = (int) $volunteer['user_id'];
        $points = max(0, (int) $volunteer['points_awarded']);

        $pdo->beginTransaction();
        try {
            $approvedAt = null;
            $approvedBy = null;
            if ($newStatus === 'approved') {
                $approvedAt = date('Y-m-d H:i:s');
                $approvedBy = (string) env('ADMIN_USERNAME', 'admin');
            }

            $updateStmt = $pdo->prepare('UPDATE user_volunteers SET approval_status = ?, approved_at = ?, approved_by = ? WHERE id = ?');
            $updateStmt->execute([$newStatus, $approvedAt, $approvedBy, $volunteerId]);

            if ($oldStatus !== 'approved' && $newStatus === 'approved') {
                $addStmt = $pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?');
                $addStmt->execute([$points, $userId]);
            } elseif ($oldStatus === 'approved' && $newStatus !== 'approved') {
                $subStmt = $pdo->prepare('UPDATE users SET points = GREATEST(points - ?, 0) WHERE id = ?');
                $subStmt->execute([$points, $userId]);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            jsonResponse(['ok' => false, 'message' => 'Gagal menyimpan status.'], 500);
        }

        jsonResponse([
            'ok' => true,
            'message' => 'Status volunteer berhasil diperbarui.',
            'status' => $newStatus,
            'approved_at' => $newStatus === 'approved' ? date('Y-m-d H:i:s') : null,
        ]);
    }

    if ($uri === '/admin/volunteers/approve' && $method === 'POST') {
        $volunteerId = (int) ($_POST['volunteer_id'] ?? 0);
        if ($volunteerId <= 0) {
            header('Location: /admin/volunteers');
            exit;
        }

        $stmt = $pdo->prepare('SELECT * FROM user_volunteers WHERE id = ? LIMIT 1');
        $stmt->execute([$volunteerId]);
        $volunteer = $stmt->fetch();

        if (!$volunteer) {
            header('Location: /admin/volunteers');
            exit;
        }

        if (($volunteer['approval_status'] ?? 'pending') !== 'approved') {
            $pdo->beginTransaction();
            try {
                $approveStmt = $pdo->prepare('UPDATE user_volunteers SET approval_status = "approved", approved_at = ?, approved_by = ? WHERE id = ?');
                $approveStmt->execute([date('Y-m-d H:i:s'), (string) env('ADMIN_USERNAME', 'admin'), $volunteerId]);

                $pointsStmt = $pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?');
                $pointsStmt->execute([(int) $volunteer['points_awarded'], (int) $volunteer['user_id']]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }
        }

        header('Location: /admin/volunteers');
        exit;
    }

    if ($uri === '/admin/regulasi-donatur' && $method === 'GET') {
        header('Location: /admin/dokumen-regulasi');
        exit;
    }

    if ($uri === '/admin/regulasi-konsumen' && $method === 'GET') {
        header('Location: /admin/dokumen-regulasi');
        exit;
    }

    if ($uri === '/admin/dokumen-regulasi' && $method === 'GET') {
        $title = 'Dokumen & Regulasi';
        $contentView = __DIR__ . '/../admin/dokumen-regulasi.php';
        require __DIR__ . '/../layouts/app.php';
        exit;
    }
}

http_response_code(404);
echo '404 Not Found';
