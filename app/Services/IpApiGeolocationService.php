<?php

namespace App\Services;

use App\Contracts\GeolocationProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpApiGeolocationService implements GeolocationProvider
{
    public function getGeolocation(string $ip): array {
        try {
            $defaultData = ['country' => null, 'city' => null];
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                ];
            }

            Log::error("ip-api request failed: ", [
                'ip' => $ip,
                'status' => $response->status(),
            ]);

        } catch (\Exception $e) {
            Log::error("ip-api request failed: ", [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        return $defaultData;
    }
}