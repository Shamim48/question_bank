<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

/**
 * Placeholder SMS driver — writes to the application log instead of calling
 * a real gateway. Swap the binding in AppServiceProvider once a real
 * provider (e.g. Alpha SMS, MIM SMS, SSL Wireless) is chosen.
 */
class LogSmsGateway implements SmsGatewayInterface
{
    public function send(string $to, string $message): bool
    {
        Log::info("[SMS] to {$to}: {$message}");
        return true;
    }
}
