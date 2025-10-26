<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index () {
        return response()->json([
            'total_links' => Link::count(),
            'total_clicks' => Click::count(),
            'last_click' => Click::latestClick(),
            'top_links' => Link::topLinks(),
        ], 200);
    }

    public function show(Link $link)
    {
        return response()->json([
            'clicks_count' => $link->clicksCount(),
            'last_click' => $link->latestClick(),
            'clicks_today' => $link->clicksSince(now()->startOfDay()),
            'clicks_this_week' => $link->clicksSince(now()->startOfWeek()),
            'clicks_this_month' => $link->clicksSince(now()->startOfMonth()),
        ], 200);
    }
}
