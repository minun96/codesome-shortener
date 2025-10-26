<?php

use App\Http\Controllers\RedirectController;
use App\Mail\WeeklyStatsDigest;
use App\Models\Click;
use App\Models\Link;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{short_code}', [RedirectController::class, 'redirect']);