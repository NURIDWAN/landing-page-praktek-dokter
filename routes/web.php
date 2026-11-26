<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/tentang', AboutController::class)->name('about');
Route::get('/lokasi-jadwal', LocationController::class)->name('location');
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('contact.store');

Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/syarat-ketentuan', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
