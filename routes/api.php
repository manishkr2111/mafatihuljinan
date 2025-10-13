<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MarqueTextController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\HijriDateEventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['ApiTokenMiddleware'])->group(function () {

    Route::get('/marque-texts', [MarqueTextController::class, 'index']);

    Route::get('/menu', [MenuController::class, 'getContent']);


    Route::get('/hijri-events', [HijriDateEventController::class, 'index']);
    Route::post('/hijri-events', [HijriDateEventController::class, 'store']);
});
