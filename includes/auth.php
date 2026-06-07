<?php

declare(strict_types=1);

function startSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $lifetime = 86400; // 1 hari
        ini_set('session.gc_maxlifetime', (string) $lifetime);
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function requireAdmin(): void
{
    startSession();
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login');
        exit;
    }
}

function isAdminLoggedIn(): bool
{
    startSession();
    return !empty($_SESSION['admin_logged_in']);
}

function isUserLoggedIn(): bool
{
    startSession();
    return !empty($_SESSION['user_logged_in']) && !empty($_SESSION['user_id']);
}

function requireUser(): void
{
    startSession();
    if (!isUserLoggedIn()) {
        header('Location: /user/login');
        exit;
    }
}
