<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Common\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\Common\MenuController;
use App\Http\Controllers\Admin\Common\HijriDateEventController;
use App\Http\Controllers\Admin\Common\MarqueeTextController;
use App\Http\Controllers\Admin\Common\DashboardController;
use App\Http\Controllers\Admin\Common\UserController;
use App\Http\Controllers\Admin\Common\TafsirDataController;
use App\Http\Controllers\Admin\English\EnglishPostController;
use App\Http\Controllers\Admin\English\CategoryController;
use App\Http\Controllers\Admin\Gujarati\GujaratiPostController;
use App\Http\Controllers\Admin\Gujarati\GujaratiCategoryController;
use App\Http\Controllers\Admin\Hindi\HindiPostController;
use App\Http\Controllers\Admin\Hindi\HindiCategoryController;
use App\Http\Controllers\Admin\Urdu\UrduPostController;
use App\Http\Controllers\Admin\Urdu\UrduCategoryController;
use App\Http\Controllers\Admin\French\FrenchPostController;
use App\Http\Controllers\Admin\French\FrenchCategoryController;
use App\Http\Controllers\Admin\RomanUrdu\RomanUrduPostController;
use App\Http\Controllers\Admin\RomanUrdu\RomanUrduCategoryController;
use App\Http\Controllers\Admin\Swahili\SwahiliPostController;
use App\Http\Controllers\Admin\Swahili\SwahiliCategoryController;
use App\Http\Controllers\Admin\Common\EventPopupController;
use App\Http\Controllers\Admin\Common\PostSearchController;


use App\Http\Controllers\Admin\Common\NotificationController;

Route::get('/send-test-notification', [NotificationController::class, 'sendTestNotification']);


Route::get('/test-firebase-path', function () {
    dd(storage_path('app/firebase/firebase-auth.json'));
});

Route::get('/google/redirect', [App\Http\Controllers\SocialLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');

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


Route::get('/set-password/{token}', [App\Http\Controllers\Api\Common\AuthController::class, 'showSetPasswordForm']);
Route::get('/set-password', [App\Http\Controllers\Api\Common\AuthController::class, 'showSetPasswordForm'])
    ->name('set-password');
Route::post('/set-password', [App\Http\Controllers\Api\Common\AuthController::class, 'setPassword']);


Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// Handle the registration form submission
//Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/reset-password', [App\Http\Controllers\Api\Common\AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Api\Common\AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard-start', [AdminController::class, 'dashboard'])->name('dashboard-start');
});

Route::middleware(['auth', 'role:admin,editor'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}', [UserController::class, 'Details'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/users/{user}/edit-role', [UserController::class, 'editRole'])->name('users.edit-role');
    Route::post('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');


    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/regenerateToken', [DashboardController::class, 'GenerateAPItoken'])->name('regenerateToken');
    Route::get('/lrc-posts/{language}/{postType}/{lrcType}', [DashboardController::class, 'showLrcEnabledPosts'])
        ->name('lrc.posts');

    Route::post('/day-difference', [HijriDateEventController::class, 'dayDifference'])->name('hijri.date.difference.store');

    Route::get('/hijri-date-event', [HijriDateEventController::class, 'index'])->name('hijri.date.event');
    Route::post('/hijri-date-event/store', [HijriDateEventController::class, 'store'])->name('hijri.date.event.store');
    Route::get('/hijri-date-event/edit/{hijriEvent}', [HijriDateEventController::class, 'edit'])->name('hijri.date.event.edit');
    Route::post('/hijri-date-event/update/{hijriEvent}', [HijriDateEventController::class, 'update'])->name('hijri.date.event.update');
    Route::delete('/hijri-date-event/delete/{hijriEvent}', [HijriDateEventController::class, 'destroy'])->name('hijri.date.event.delete');

    Route::get('/showEventHijri', [HijriDateEventController::class, 'showEventHijri'])->name('showEventHijri');

    // eventpop
    Route::get('/eventpopup', [EventPopupController::class, 'index'])->name('eventpopup');
    Route::post('/eventpopup/store', [EventPopupController::class, 'store'])->name('eventpopup.store');
    Route::delete('/eventpopup/{eventPopup}', [EventPopupController::class, 'destroy'])->name('eventpopup.destroy');

    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    Route::get('/tafsir', [TafsirDataController::class, 'index'])->name('tafsir.index');
    Route::get('/tafsir/create', [TafsirDataController::class, 'create'])->name('tafsir.create');
    Route::post('/tafsir/store', [TafsirDataController::class, 'store'])->name('tafsir.store');
    Route::get('/tafsir/edit/{id}', [TafsirDataController::class, 'edit'])->name('tafsir.edit');
    Route::post('/tafsir/update/{id}', [TafsirDataController::class, 'update'])->name('tafsir.update');
    Route::delete('/tafsir/delete/{id}', [TafsirDataController::class, 'destroy'])->name('tafsir.destroy');


    Route::get('/marquee', [MarqueeTextController::class, 'index'])->name('marquee.index');
    Route::post('/marquee/store', [MarqueeTextController::class, 'store'])->name('marquee.store');
    Route::get('/marquee/edit/{marqueeText}', [MarqueeTextController::class, 'edit'])->name('marquee.edit');
    Route::post('/marquee/update/{marqueeText}', [MarqueeTextController::class, 'update'])->name('marquee.update');
    Route::delete('/marquee/delete/{marqueeText}', [MarqueeTextController::class, 'destroy'])->name('marquee.delete');

    Route::get('/upload-audio', [DashboardController::class, 'uploadAudiopage'])->name('uploadAudioPage');
    Route::post('/uploadAudio', [DashboardController::class, 'uploadAudio'])->name('uploadAudio');
    Route::delete('/delete-audio', [DashboardController::class, 'deleteAudio'])->name('deleteAudio');


    Route::get('/post-search', [PostSearchController::class, 'index'])->name('post.search');
    Route::post('/post-search', [PostSearchController::class, 'search'])->name('post.search.submit');


    Route::get('/post/search-replace', [PostSearchController::class, 'showSearchReplace'])
        ->name('post.search.replace.page');

    Route::post('/post/search-replace', [PostSearchController::class, 'searchReplace'])
        ->name('post.search.replace');

    Route::post('/post/perform-replace', [PostSearchController::class, 'performReplace'])
        ->name('post.perform.replace');

    Route::get('/notifications/schedule', [NotificationController::class, 'index'])
        ->name('notifications.schedule.index');
    Route::get('/notifications/schedule/create', [NotificationController::class, 'createSchedule'])
        ->name('notifications.schedule.create');
    Route::post('/notifications/schedule', [NotificationController::class, 'storeSchedule'])
        ->name('notifications.schedule.store');
    Route::get('/notifications/schedule/{schedule}/edit', [NotificationController::class, 'editSchedule'])
        ->name('notifications.schedule.edit');
    Route::put('/notifications/schedule/{schedule}', [NotificationController::class, 'updateSchedule'])
        ->name('notifications.schedule.update');
    Route::delete('/notifications/schedule/{schedule}', [NotificationController::class, 'destroy'])
        ->name('notifications.schedule.destroy');
    Route::post('/notifications/instant-send', [NotificationController::class, 'sendInstant'])
        ->name('notifications.instant.send');




    // English
    Route::prefix('english/categories')->name('english.category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');

        Route::get('/create-deeplink', [CategoryController::class, 'createDeeplink'])->name('createDeeplink');
        Route::post('/deeplink-store', [CategoryController::class, 'deeplinkStore'])->name('deeplink.store');
        Route::get('/deeplink/{category}/edit', [CategoryController::class, 'deeplinkEdit'])
            ->name('deeplink.edit');
        Route::put('/deeplink/{category}', [CategoryController::class, 'deeplinkUpdate'])
            ->name('deeplink.update');

        Route::get('/parents', [CategoryController::class, 'getParentCategories'])->name('parents');
    });

    Route::prefix('english/post')->name('english.post.')->group(function () {
        Route::get('/', [EnglishPostController::class, 'index'])->name('index');
        Route::get('/create', [EnglishPostController::class, 'create'])->name('create');
        Route::post('/store', [EnglishPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [EnglishPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [EnglishPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [EnglishPostController::class, 'destroy'])->name('destroy');
    });

    // Gujarati
    Route::prefix('gujarati/post')->name('gujarati.post.')->group(function () {
        Route::get('/', [GujaratiPostController::class, 'index'])->name('index');
        Route::get('/create', [GujaratiPostController::class, 'create'])->name('create');
        Route::post('/store', [GujaratiPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [GujaratiPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [GujaratiPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [GujaratiPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gujarati/categories')->name('gujarati.category.')->group(function () {
        Route::get('/', [GujaratiCategoryController::class, 'index'])->name('index');
        Route::get('/create', [GujaratiCategoryController::class, 'create'])->name('create');
        Route::post('/', [GujaratiCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [GujaratiCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [GujaratiCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [GujaratiCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [GujaratiCategoryController::class, 'getParentCategories'])->name('parents');
    });

    // Hindi
    Route::prefix('hindi/post')->name('hindi.post.')->group(function () {
        Route::get('/', [HindiPostController::class, 'index'])->name('index');
        Route::get('/create', [HindiPostController::class, 'create'])->name('create');
        Route::post('/store', [HindiPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [HindiPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [HindiPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [HindiPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('hindi/categories')->name('hindi.category.')->group(function () {
        Route::get('/', [HindiCategoryController::class, 'index'])->name('index');
        Route::get('/create', [HindiCategoryController::class, 'create'])->name('create');
        Route::post('/', [HindiCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [HindiCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [HindiCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [HindiCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [HindiCategoryController::class, 'getParentCategories'])->name('parents');
    });

    //Urdu
    Route::prefix('urdu/post')->name('urdu.post.')->group(function () {
        Route::get('/', [UrduPostController::class, 'index'])->name('index');
        Route::get('/create', [UrduPostController::class, 'create'])->name('create');
        Route::post('/store', [UrduPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [UrduPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [UrduPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [UrduPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('urdu/categories')->name('urdu.category.')->group(function () {
        Route::get('/', [UrduCategoryController::class, 'index'])->name('index');
        Route::get('/create', [UrduCategoryController::class, 'create'])->name('create');
        Route::post('/', [UrduCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [UrduCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [UrduCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [UrduCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [UrduCategoryController::class, 'getParentCategories'])->name('parents');
    });

    //Roman Urdu
    Route::prefix('roman-urdu/post')->name('roman-urdu.post.')->group(function () {
        Route::get('/', [RomanUrduPostController::class, 'index'])->name('index');
        Route::get('/create', [RomanUrduPostController::class, 'create'])->name('create');
        Route::post('/store', [RomanUrduPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [RomanUrduPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [RomanUrduPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [RomanUrduPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('roman-urdu/categories')->name('roman-urdu.category.')->group(function () {
        Route::get('/', [RomanUrduCategoryController::class, 'index'])->name('index');
        Route::get('/create', [RomanUrduCategoryController::class, 'create'])->name('create');
        Route::post('/', [RomanUrduCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [RomanUrduCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [RomanUrduCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [RomanUrduCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [RomanUrduCategoryController::class, 'getParentCategories'])->name('parents');
    });

    //French
    Route::prefix('french/post')->name('french.post.')->group(function () {
        Route::get('/', [FrenchPostController::class, 'index'])->name('index');
        Route::get('/create', [FrenchPostController::class, 'create'])->name('create');
        Route::post('/store', [FrenchPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [FrenchPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [FrenchPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [FrenchPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('french/categories')->name('french.category.')->group(function () {
        Route::get('/', [FrenchCategoryController::class, 'index'])->name('index');
        Route::get('/create', [FrenchCategoryController::class, 'create'])->name('create');
        Route::post('/', [FrenchCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [FrenchCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [FrenchCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [FrenchCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [FrenchCategoryController::class, 'getParentCategories'])->name('parents');
    });

    //Swahili
    Route::prefix('swahili/post')->name('swahili.post.')->group(function () {
        Route::get('/', [SwahiliPostController::class, 'index'])->name('index');
        Route::get('/create', [SwahiliPostController::class, 'create'])->name('create');
        Route::post('/store', [SwahiliPostController::class, 'store'])->name('store');
        Route::get('/{postId}/edit', [SwahiliPostController::class, 'edit'])->name('edit');
        Route::put('/{postId}', [SwahiliPostController::class, 'update'])->name('update');
        Route::delete('/{postId}', [SwahiliPostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('swahili/categories')->name('swahili.category.')->group(function () {
        Route::get('/', [SwahiliCategoryController::class, 'index'])->name('index');
        Route::get('/create', [SwahiliCategoryController::class, 'create'])->name('create');
        Route::post('/', [SwahiliCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [SwahiliCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [SwahiliCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [SwahiliCategoryController::class, 'destroy'])->name('destroy');

        Route::get('/parents', [SwahiliCategoryController::class, 'getParentCategories'])->name('parents');
    });
});
