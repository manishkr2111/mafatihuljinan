<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\HijriDateEventController;
use App\Http\Controllers\Admin\MarqueeTextController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\English\EnglishPostController;
use App\Http\Controllers\Admin\Gujarati\GujaratiPostController;
use App\Http\Controllers\UserController;


Route::get('/greet', function () {
    return greet_user('Manish');
});

Route::get('/home', function () {
   return view('welcome');
});
Route::get('/', function () {
    return redirect('/login');
});
Route::middleware('auth')->get('/home', function () {
    return view('welcome');  // A view for logged-in users
});


// Show the registration form
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Handle the registration form submission
Route::post('/register', [AuthController::class, 'register']);

// Show the login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle the login form submission
Route::post('/login', [AuthController::class, 'login']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard-start', [AdminController::class, 'dashboard'])->name('dashboard-start');
});



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/regenerateToken', [DashboardController::class, 'GenerateAPItoken'])->name('regenerateToken');

    Route::get('/hijri-date-event', [HijriDateEventController::class, 'index'])->name('hijri.date.event');
    Route::post('/hijri-date-event/store', [HijriDateEventController::class, 'store'])->name('hijri.date.event.store');
    Route::get('/hijri-date-event/edit/{hijriEvent}', [HijriDateEventController::class, 'edit'])->name('hijri.date.event.edit');
    Route::post('/hijri-date-event/update/{hijriEvent}', [HijriDateEventController::class, 'update'])->name('hijri.date.event.update');
    Route::delete('/hijri-date-event/delete/{hijriEvent}', [HijriDateEventController::class, 'destroy'])->name('hijri.date.event.delete');

    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');

    Route::get('/marquee', [MarqueeTextController::class, 'index'])->name('marquee.index');
    Route::post('/marquee/store', [MarqueeTextController::class, 'store'])->name('marquee.store');
    Route::get('/marquee/edit/{marqueeText}', [MarqueeTextController::class, 'edit'])->name('marquee.edit');
    Route::post('/marquee/update/{marqueeText}', [MarqueeTextController::class, 'update'])->name('marquee.update');
    Route::delete('/marquee/delete/{marqueeText}', [MarqueeTextController::class, 'destroy'])->name('marquee.delete');

    Route::prefix('english/categories')->name('english.category.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\English\CategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\English\CategoryController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\English\CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\English\CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\English\CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\English\CategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [\App\Http\Controllers\Admin\English\CategoryController::class, 'getParentCategories'])->name('parents');
    });

    Route::prefix('english/post')->name('english.post.')->group(function () {
        Route::get('/', [EnglishPostController::class, 'index'])->name('index');
        Route::get('/create', [EnglishPostController::class, 'create'])->name('create');
        Route::post('/store', [EnglishPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [EnglishPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [EnglishPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [EnglishPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gujarati/post')->name('gujarati.post.')->group(function () {
        Route::get('/', [GujaratiPostController::class, 'index'])->name('index');
        Route::get('/create', [GujaratiPostController::class, 'create'])->name('create');
        Route::post('/store', [GujaratiPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [GujaratiPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [GujaratiPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [GujaratiPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gujarati/categories')->name('gujarati.category.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [\App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController::class, 'getParentCategories'])->name('parents');
    });
});
