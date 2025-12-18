<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

class MoraSmsClient
{
    public function send(string|array $to, string $message): array
    {
        $cfg = config('services.mora');

        if (! $cfg['enabled']) {
            return ['ok' => true, 'code' => null, 'raw' => 'disabled', 'error' => null];
        }

        $payload = [
            'api_key'  => $cfg['api_key'],
            'username' => $cfg['username'],
            'message'  => $message,
            'sender'   => $cfg['sender'],
            'numbers'  => is_array($to) ? implode(',', $to) : $to,
            'response' => $cfg['response'],
            'unicode'  => $cfg['unicode'] ? 1 : 0,
        ];

        $resp = Http::asForm()
            ->timeout($cfg['timeout'])
            ->retry(2, 500)
            ->post($cfg['url'], $payload);

        return [
            'ok'    => $resp->successful(),
            'code'  => $resp->status(),
            'raw'   => $resp->body(),
            'error' => $resp->successful() ? null : ($resp->json('message') ?? $resp->body()),
        ];
    }
}
