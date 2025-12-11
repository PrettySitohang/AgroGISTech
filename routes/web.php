<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route untuk halaman publik (Pastikan ini di atas route Auth)
Route::controller(ArticleController::class)->group(function () {
    Route::get('/', 'index')->name('public.index');
    Route::get('/article/{article:slug}', 'show')->name('public.show');
    Route::get('/writer/{user}', 'showWriterProfile')->name('writer.profile');
});

// Static / section pages
Route::get('/teknologi', [PageController::class, 'teknologi'])->name('pages.teknologi');
Route::get('/riset', [PageController::class, 'riset'])->name('pages.riset');
Route::get('/berita', [PageController::class, 'berita'])->name('pages.berita');
Route::get('/tentang', [PageController::class, 'tentang'])->name('pages.tentang');

// Route untuk otentikasi
Route::controller(AuthController::class)->group(function () {
    // Ubah nama method controller untuk menghindari konflik dengan nama route/http verb
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'storeUser')->name('register.post');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(GoogleController::class)->prefix('auth/google')->group(function () {
    Route::get('/', 'redirectToGoogle')->name('google.login');
    Route::get('/callback', 'handleGoogleCallback')->name('google.callback');
});


Route::middleware('auth')->get('/dashboard', function() {
    $user = Auth::user();

    return match($user->role) {
        'super_admin' => redirect()->route('admin.dashboard'),
        'editor' => redirect()->route('editor.dashboard'),
        default => redirect()->route('penulis.dashboard'),
    };
})->name('dashboard');

Route::middleware(['auth','role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(AdminController::class)
    ->group(function(){
        Route::get('/', 'dashboard')->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', 'userIndex')->name('index');
            Route::get('/create', 'userCreate')->name('create');
            Route::post('/', 'userStore')->name('store');
            Route::get('/{user}/edit', 'userEdit')->name('edit');
            Route::put('/{user}', 'userUpdate')->name('update');
            Route::delete('/{user}', 'userDelete')->name('delete');
        });

        Route::prefix('articles')->name('articles.')->group(function () {
            Route::get('/', 'articleIndex')->name('index');
            Route::get('/{article}/edit', 'articleEdit')->name('edit');
            Route::put('/{article}', 'articleUpdate')->name('update');
            Route::delete('/{article}', 'articleDelete')->name('delete.force');
        });

        // Site settings (logo, site name)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'settingsIndex')->name('index');
            Route::post('/', 'settingsUpdate')->name('update');
        });

        Route::prefix('masters/categories')->name('categories.')->group(function () {

        Route::get('/', 'categoryIndex')->name('index');
            Route::get('/create', 'categoryCreate')->name('create');
            Route::post('/', 'categoryStore')->name('store');
            Route::get('/{category}', 'categoryShow')->name('show');
            Route::get('/{category}/edit', 'categoryEdit')->name('edit');
            Route::put('/{category}', 'categoryUpdate')->name('update');
            Route::delete('/{category}', 'categoryDelete')->name('delete');
        });


        Route::prefix('masters/tags')->name('tags.')->group(function () {
            Route::get('/', 'tagIndex')->name('index');
            Route::get('/create', 'tagCreate')->name('create');
            Route::post('/', 'tagStore')->name('store');
            Route::get('/{tag}/edit', 'tagEdit')->name('edit');
            Route::put('/{tag}', 'tagUpdate')->name('update');
            Route::delete('/{tag}', 'tagDelete')->name('delete');
        });


        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', 'logs')->name('index');
            Route::delete('/{log}', [LogController::class,'deleteLog'])->name('delete');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', 'profileEdit')->name('edit');
            Route::put('/', 'profileUpdate')->name('update');
        });
    });

Route::middleware(['auth','role:editor'])
    ->prefix('editor')
    ->name('editor.')
    ->controller(EditorController::class)
    ->group(function(){
        Route::get('/', 'dashboard')->name('dashboard');

        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', 'reviewIndex')->name('index');
            Route::post('/{article}/claim', 'claimArticle')->name('claim');
        });

        Route::prefix('articles')->name('articles.')->group(function () {
            Route::delete('/{article}', 'articleDestroy')->name('destroy');
            Route::get('/', 'articleIndex')->name('index');
            Route::get('/{article}/edit', 'articleEdit')->name('edit');
            Route::put('/{article}', 'articleUpdate')->name('update');
            Route::put('/{article}/publish', 'articlePublish')->name('publish');


        });

        Route::prefix('masters/categories')->name('categories.')->group(function () {
            Route::get('/', 'categoryIndex')->name('index');
            Route::post('/', 'categoryStore')->name('store');
            Route::get('/{category}/edit', 'categoryEdit')->name('edit');
            Route::put('/{category}', 'categoryUpdate')->name('update');
            Route::delete('/{category}', 'categoryDestroy')->name('destroy');


        });

        Route::prefix('masters/tags')->name('tags.')->group(function () {
            Route::get('/', 'tagIndex')->name('index');
            Route::post('/', 'tagStore')->name('store');
            Route::get('/{tag}/edit', 'tagEdit')->name('edit');
            Route::put('/{tag}', 'tagUpdate')->name('update');
            Route::delete('/{tag}', 'tagDestroy')->name('destroy');
        });

        Route::get('/user/{user}', 'readProfile')->name('user.profile');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', 'profileEdit')->name('edit');
            Route::put('/', 'profileUpdate')->name('update');
        });
    });


    Route::middleware(['auth','role:penulis'])
    ->prefix('penulis')
    ->name('penulis.')
    ->controller(PenulisController::class)
    ->group(function(){
        // ... kode penulis lainnya ...
        Route::get('/', 'dashboard')->name('dashboard');

        Route::prefix('articles')->name('articles.')->group(function(){
            Route::get('/', 'articleIndex')->name('index');
            Route::get('/create', 'articleCreate')->name('create');
            Route::post('/', 'articleStore')->name('store');
            Route::get('/{article}/edit', 'articleEdit')->name('edit');
            Route::put('/{article}', 'articleUpdate')->name('update');
            Route::delete('/{article}', 'articleDelete')->name('delete');
            Route::post('/{article}/submit', 'submitForReview')->name('submit');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', 'profileEdit')->name('edit');
            Route::put('/', 'profileUpdate')->name('update');
        });
    });
