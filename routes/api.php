<?php

use App\Http\Controllers\LinkController;
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
});
