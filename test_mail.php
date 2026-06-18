<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test OTP via App\\Mail\\SendOtpMail ===\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n\n";

$otp = 654321;
$name = "Test User";
$toEmail = config('mail.mailers.smtp.username');

try {
    \Illuminate\Support\Facades\Mail::to($toEmail)->send(new \App\Mail\SendOtpMail($otp, $name));
    echo "✅ OTP EMAIL BERHASIL DIKIRIM ke: $toEmail\n";
} catch (\Exception $e) {
    echo "❌ GAGAL: " . $e->getMessage() . "\n\n";
    echo "Class: " . get_class($e) . "\n";
    
    // Tulis ke log juga
    \Illuminate\Support\Facades\Log::error("OTP Test Email Error: " . $e->getMessage());
}
