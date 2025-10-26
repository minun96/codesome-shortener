<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RedirectController extends Controller
{
    public function redirect(Request $request, $short_code) {
        $link = Link::where('short_code', $short_code)->firstOrFail();
        $ip = $request->ip();
        $geolocalization = Http::get("http://ip-api.com/json/{$ip}")->json(); // https://ip-api.com/docs/api:json basta passare $ip
        $link->clicks()->create([
            'ip_address' => $ip,
            'country' => $geolocalization['country'] ?? null,
            'city' => $geolocalization['city'] ?? null,
        ]);
        return redirect($link->long_url);
    }
}
