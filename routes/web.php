<?php

use App\Http\Controllers\Admin\ClearCacheController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/home.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/botman.php';

// Convenience redirects for common mistaken admin URLs (use named routes so /Nexora is preserved)
Route::get('/admin', fn () => redirect()->route('adminloginform'));
Route::get('/admin/login', fn () => redirect()->route('adminloginform'));
Route::get('/adminlogin', fn () => redirect()->route('adminloginform'));

// CSRF Token Refresh Route (prevents 419 on idle pages)
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.refresh');

// PWA Manifest Route
Route::get('/manifest.json', [App\Http\Controllers\Admin\Settings\AppSettingsController::class, 'manifest'])->name('manifest');

// Public media (logos + profile photos). Bypasses Nginx /storage deny rules.
// Does NOT serve KYC uploads/ — only branding|photos.
Route::get('/media/{folder}/{file}', [MediaController::class, 'show'])
    ->where('folder', 'branding|photos')
    ->where('file', '.*')
    ->name('media.show');
