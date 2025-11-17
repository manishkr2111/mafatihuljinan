<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Common\MarqueTextController;
use App\Http\Controllers\Api\Common\MenuController;
use App\Http\Controllers\Api\Common\AuthController;
use App\Http\Controllers\Api\Common\HijriDateEventController;
use App\Http\Controllers\Api\English\EnglishCategoryController;
use App\Http\Controllers\Api\English\EnglishPostController;
use App\Http\Controllers\Api\Common\CustomUserPostController;
use App\Http\Controllers\Api\Common\FavoriteController;
use App\Http\Controllers\Api\Common\TafsirDataController;
use App\Http\Controllers\Api\Common\UserNotepadController;
use App\Http\Controllers\Admin\Common\DashboardController;


Route::post('/uploadAudio', [DashboardController::class, 'uploadAudio'])->name('uploadAudio');

// common Api's
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user/details', [AuthController::class, 'details']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/create/custom-post', [CustomUserPostController::class, 'store']);
    Route::put('/update/custom-posts/{id}', [CustomUserPostController::class, 'update']);

    // favprites
    Route::post('/create/favorite-post', [FavoriteController::class, 'store']);
    Route::get('/all/favorite/posts', [FavoriteController::class, 'getAllFavorites']);
    Route::post('/favorites/delete', [FavoriteController::class, 'destroy']);


    Route::get('all/notes', [UserNotepadController::class, 'index'])->name('notes.index');

    Route::post('create/notes', [UserNotepadController::class, 'store'])->name('notes.store');

    Route::post('update/notes/', [UserNotepadController::class, 'update'])->name('notes.update');

    Route::delete('delete/note', [UserNotepadController::class, 'destroy'])->name('notes.destroy');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['ApiTokenMiddleware'])->group(function () {

    Route::get('/marque-texts', [MarqueTextController::class, 'index']);

    Route::get('/menu', [MenuController::class, 'getContent']);

    Route::get('/hijri-events', [HijriDateEventController::class, 'index']);
    Route::post('/hijri-events', [HijriDateEventController::class, 'store']);

    Route::get('/hijri-date', [HijriDateEventController::class, 'getCurrentHijriDate']);
    Route::any('/hijri-date-with-events', [HijriDateEventController::class, 'getHijriDateWithEvents']);
    Route::post('/ramadan-date', [HijriDateEventController::class, 'getRamadanDate']);

    Route::get('english/categories', [EnglishCategoryController::class, 'index']);

    Route::get('english/all/categories', [EnglishCategoryController::class, 'allDualCategories']);


    Route::get('english/posts', [EnglishPostController::class, 'SahifasShlulbayt']);
    Route::get('english/posts/{id}', [EnglishPostController::class, 'show']);

    Route::get('english/DuaData', [EnglishPostController::class, 'DuaData']);
    Route::get('english/single-post/{id}', [EnglishPostController::class, 'singlepostdata']);

    Route::get('tafsir/data/{id}', [TafsirDataController::class, 'TafsirData']);
});
