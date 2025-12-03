<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route untuk halaman publik (Pastikan ini di atas route Auth)
Route::controller(ArticleController::class)->group(function () {
    Route::get('/', 'index')->name('public.index');
    Route::get('/article/{article:slug}', 'show')->name('public.show');
    Route::get('/writer/{user}', 'showWriterProfile')->name('writer.profile');
});

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
            Route::delete('/{article}', 'articleDelete')->name('delete.force');
        });

        Route::prefix('masters/categories')->name('categories.')->group(function () {
            Route::get('/', 'categoryIndex')->name('index');
            Route::post('/', 'categoryStore')->name('store');
            Route::put('/{category}', 'categoryUpdate')->name('update');
            Route::delete('/{category}', 'categoryDelete')->name('delete');
        });

        Route::prefix('masters/tags')->name('tags.')->group(function () {
            Route::get('/', 'tagIndex')->name('index');
            Route::post('/', 'tagStore')->name('store');
            Route::put('/{tag}', 'tagUpdate')->name('update');
            Route::delete('/{tag}', 'tagDelete')->name('delete');
        });

        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', 'logs')->name('index');
            Route::delete('/{log}', [LogController::class,'deleteLog'])->name('delete');
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
            Route::get('/{article}/edit', 'articleEdit')->name('edit');
            Route::put('/{article}', 'articleUpdate')->name('update');
        });

        Route::prefix('masters/categories')->name('categories.')->group(function () {
            Route::get('/', 'categoryIndex')->name('index');
            Route::post('/', 'categoryStore')->name('store');
            Route::put('/{category}', 'categoryUpdate')->name('update');
            Route::delete('/{category}', 'categoryDelete')->name('delete');
        });

        Route::prefix('masters/tags')->name('tags.')->group(function () {
            Route::get('/', 'tagIndex')->name('index');
            Route::post('/', 'tagStore')->name('store');
            Route::put('/{tag}', 'tagUpdate')->name('update');
            Route::delete('/{tag}', 'tagDelete')->name('delete');
        });

        Route::get('/user/{user}', 'readProfile')->name('user.profile');
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
