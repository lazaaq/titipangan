<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

function generateOtpCode(): string
{
    return (string) random_int(100000, 999999);
}

function normalizePhone(string $phone): string
{
    $clean = preg_replace('/[^0-9]/', '', $phone) ?? '';
    if (str_starts_with($clean, '0')) {
        return '62' . substr($clean, 1);
    }
    if (str_starts_with($clean, '62')) {
        return $clean;
    }
    return $clean;
}

function sendOtpViaWhatsApp(string $phone, string $otp): array
{
    $message = "Kode verifikasi Titipangan Anda: {$otp}. Demi keamanan, jangan bagikan kode ini kepada siapa pun. Berlaku selama 5 menit.";
    $result = sendWhatsAppMessage($phone, $message, 'otp=' . $otp);
    if (!$result['ok']) {
        return [
            'ok' => false,
            'message' => 'Gagal kirim OTP ke Fonnte.',
            'debug' => $result['debug'] ?? null,
        ];
    }

    return ['ok' => true, 'message' => 'OTP terkirim ke WhatsApp Anda.'];
}

function sendWhatsAppMessage(string $phone, string $message, string $devLogContext = ''): array
{
    $fonnteToken = env('FONNTE_TOKEN');

    if ($fonnteToken) {
        $payload = http_build_query([
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62',
        ]);

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: ' . $fonnteToken,
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 10,
        ]);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error !== '' || $httpCode >= 400) {
            return [
                'ok' => false,
                'message' => 'Gagal kirim WhatsApp ke Fonnte.',
                'debug' => $error !== '' ? $error : (string) $result,
            ];
        }

        return ['ok' => true, 'message' => 'Pesan WhatsApp terkirim.'];
    }

    $logPath = dirname(__DIR__) . '/storage/wa_otp.log';
    $line = sprintf("[%s] phone=%s message=%s %s\n", date('c'), $phone, $message, $devLogContext);
    file_put_contents($logPath, $line, FILE_APPEND);

    return [
        'ok' => true,
        'message' => 'Pesan dibuat (mode dev). Cek storage/wa_otp.log.',
    ];
}
