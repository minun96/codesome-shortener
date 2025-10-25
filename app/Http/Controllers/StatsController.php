<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index () {
        $totalLinks = Link::count();
        $totalClicks = Click::count();
        $lastClick = Click::latest()->first();
        $topLinks = Link::withCount('clicks')
            ->orderBy('clicks_count', 'desc') // ho già definito la relazione e posso accedere grazie a withCount()
            ->take(5)
            ->get();

        return response()->json([
            'total_links' => $totalLinks,
            'total_clicks' => $totalClicks,
            'last_click' => $lastClick,
            'top_links' => $topLinks,
        ], 200);
    }

    public function show(Link $link)
    {
        $clicksCount = $link->clicks()->count();
        $lastClick = $link->clicks()->latest()->first();
        $clicksToday = $link->clicks()
            ->where('created_at', '>=', now()->subDay())->count();
        $clicksThisWeek = $link->clicks()
            ->where('created_at', '>=', now()->subWeek())->count();
        $clicksThisMonth = $link->clicks()
            ->where('created_at', '>=', now()->subMonth())->count();

        return response()->json([
            'clicks_count' => $clicksCount,
            'last_click' => $lastClick,
            'clicks_today' => $clicksToday,
            'clicks_this_week' => $clicksThisWeek,
            'clicks_this_month' => $clicksThisMonth,
        ], 200);
    }
}
