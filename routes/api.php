<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MarqueTextController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\HijriDateEventController;
use App\Http\Controllers\Api\EnglishCategoryController;
use App\Http\Controllers\Api\EnglishPostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['ApiTokenMiddleware'])->group(function () {

    Route::get('/marque-texts', [MarqueTextController::class, 'index']);

    Route::get('/menu', [MenuController::class, 'getContent']);

    Route::get('/hijri-events', [HijriDateEventController::class, 'index']);
    Route::post('/hijri-events', [HijriDateEventController::class, 'store']);

    Route::get('english/categories', [EnglishCategoryController::class, 'index']);


    Route::get('english/posts', [EnglishPostController::class, 'SahifasShlulbayt']);
    Route::get('english/posts/{id}', [EnglishPostController::class, 'show']);
});
