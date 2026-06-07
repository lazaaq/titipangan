<?php

declare(strict_types=1);

function weekKey(?DateTimeInterface $date = null): string
{
    $target = $date ?? new DateTimeImmutable('now');
    return $target->format('o-W');
}

function jsonResponse(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function dailyPickupCode(int $userId, ?DateTimeInterface $date = null): string
{
    $target = $date ?? new DateTimeImmutable('now');
    $seed = $userId . '|' . $target->format('Y-m-d');
    $hash = strtoupper(hash('sha256', $seed));
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $code = '';

    for ($i = 0; $i < 6; $i++) {
        $pair = substr($hash, $i * 2, 2);
        $index = hexdec($pair) % strlen($alphabet);
        $code .= $alphabet[$index];
    }

    return $code;
}
