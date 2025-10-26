<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function redirect(Request $request, $short_code) {
        $link = Link::where('short_code', $short_code)->firstOrFail();
        $ip = $request->ip();

        // considero la possibilità che il servizio non risponda
        // RICORDA DI CREARE UN SERVICE
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                $data = $response->json();
                $geolocalization['country'] = $data['country'] ?? null;
                $geolocalization['city'] = $data['city'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error("Ip API request failed: ", [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        $link->clicks()->create([
            'ip_address' => $ip,
            'country' => $geolocalization['country'] ?? null,
            'city' => $geolocalization['city'] ?? null,
        ]);

        return redirect($link->long_url);
    }
}
