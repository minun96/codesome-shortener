<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessGeolocation;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function redirect(Request $request, $short_code) {
        $link = Link::where('short_code', $short_code)->firstOrFail();
        $ip = $request->ip();
        $click = $link->registerClick($ip);

        $response = redirect($link->long_url);
        ProcessGeolocation::dispatch($click);

        return $response;
    }
}
