<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'links'], function () {
    Route::get('/', [LinkController::class, 'index'])->name('links.index'); // index
    Route::get('/{link}', [LinkController::class, 'show'])->name('links.show'); // {link}
    Route::post('/', [LinkController::class, 'store'])->name('links.store'); // store
    Route::patch('/{link}', [LinkController::class, 'update'])->name('links.update'); // update
    Route::delete('/{link}', [LinkController::class, 'destroy'])->name('links.destroy'); // destroy 
    Route::put('/{link}/restore', [LinkController::class, 'restore'])->name('links.restore'); // restore
    Route::get('/trashed', [LinkController::class, 'trashed'])->name('links.trashed')->withTrashed(); // index thrashed
});

Route::group(['prefix' => 'stats'], function () {
    Route::get('/', [StatsController::class, 'index']);
    Route::get('/{link}', [StatsController::class, 'show']);
});