<?php

namespace App\Http\Controllers;

use App\Contracts\GeolocationProvider;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function redirect(Request $request, $short_code, GeolocationProvider $geolocationProvider) {
        $link = Link::where('short_code', $short_code)->firstOrFail();
        $ip = $request->ip();
        $geoloc = $geolocationProvider->getGeolocation($ip);

        $link->registerClick($ip, $geoloc);

        return redirect($link->long_url);
    }
}
