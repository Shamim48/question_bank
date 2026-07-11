<?php

namespace App\Contracts;

interface SmsGatewayInterface
{
    public function send(string $to, string $message): bool;
}
