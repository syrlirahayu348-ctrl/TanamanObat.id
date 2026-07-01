<?php
namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

/**
 * Service responsible for sending OTP messages via WhatsApp using Twilio.
 */
class WhatsAppService
{
    // Allow null values; will be normalized to empty strings
    protected ?string $sid;
    protected ?string $token;
    protected ?string $from; // e.g. whatsapp:+14155238886

    public function __construct()
    {
        // Use empty string fallback to satisfy the string type hint and avoid null assignment errors
        // Use empty string fallback to avoid null assignment errors
        $this->sid   = env('TWILIO_SID') ?? '';
        $this->token = env('TWILIO_TOKEN') ?? '';
        $this->from  = env('TWILIO_WHATSAPP_FROM') ?? '';
        // Ensure the from number is prefixed with "whatsapp:" for Twilio API
        if ($this->from !== '' && strpos($this->from, 'whatsapp:') !== 0) {
            $this->from = 'whatsapp:' . ltrim($this->from, '+');
        }
    }


    /**
     * Send a numeric OTP to a recipient phone number via WhatsApp.
     *
     * @param string $to  Recipient phone number in E.164 format (e.g. +628123456789)
     * @param string $otp The OTP code to send
     * @throws \Exception on failure
     */
    public function sendOtp(string $to, string $otp): void
    {
        // If Twilio credentials are not set, skip sending to avoid runtime errors
        if (empty($this->sid) || empty($this->token) || empty($this->from)) {
            Log::warning('[WhatsApp OTP] Missing Twilio credentials; OTP not sent.');
            return;
        }
        $message = "🔐 Kode OTP Anda untuk TanamanObat.id: {$otp}\n" .
                   "Gunakan kode ini dalam 5 menit. Jika bukan Anda, abaikan pesan ini.";
        $toWhatsApp = "whatsapp:{$to}";

                try {
            $client = new Client($this->sid, $this->token);
            $client->messages->create(
                $toWhatsApp,
                [
                    'from' => $this->from,
                    'body' => $message,
                ]
            );
            Log::info("[WhatsApp OTP] Sent OTP {$otp} to {$to}");
        } catch (\Exception $e) {
            Log::error('[WhatsApp OTP] Failed to send OTP: ' . $e->getMessage());
            // rethrow if needed
            throw $e;
        }
    }
}
?>
