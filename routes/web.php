<?php

use App\Http\Controllers\Admin\AgencyController as AdminAgencyController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\VipPropertyController as AdminVipPropertyController;
use App\Http\Controllers\Agency\AuthController as AgencyAuthController;
use App\Http\Controllers\Agency\DashboardController as AgencyDashboardController;
use App\Http\Controllers\AgencyPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::redirect('/login', '/agency/login')->name('login');
Route::get('/', [HomeController::class, 'landing'])->name('home');
Route::get('/properties', [HomeController::class, 'index'])->name('properties.index');
Route::get('/api/properties/map', [HomeController::class, 'mapData'])->name('api.properties.map');
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Public Hotels Directory & Profile
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotels.show');

// Public Agencies Directory & Agency Profile
Route::get('/agencies', [AgencyPublicController::class, 'index'])->name('agencies.index');
Route::get('/agencies/{slug}', [AgencyPublicController::class, 'show'])->name('agencies.show');

// Agency Authentication
Route::prefix('agency')->name('agency.')->group(function () {
    Route::get('/login', [AgencyAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AgencyAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AgencyAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AgencyAuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AgencyAuthController::class, 'logout'])->name('logout');
    Route::get('/pending-approval', [AgencyAuthController::class, 'pendingApproval'])->name('pending');

    // Agency Protected Dashboard (Requires Verified Status)
    Route::middleware(['auth', 'agency.verified'])->group(function () {
        Route::get('/dashboard', [AgencyDashboardController::class, 'index'])->name('dashboard');
        Route::get('/properties/create', [AgencyDashboardController::class, 'create'])->name('properties.create');
        Route::post('/properties', [AgencyDashboardController::class, 'store'])->name('properties.store');
        Route::get('/properties/{property}/edit', [AgencyDashboardController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{property}', [AgencyDashboardController::class, 'update'])->name('properties.update');
        Route::patch('/properties/{property}/status', [AgencyDashboardController::class, 'updateStatus'])->name('properties.status');
        Route::delete('/properties/{property}', [AgencyDashboardController::class, 'destroy'])->name('properties.destroy');
        Route::get('/profile', [AgencyDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [AgencyDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/credits', [AgencyDashboardController::class, 'credits'])->name('credits');
    });
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Agencies Management
        Route::get('/agencies', [AdminAgencyController::class, 'index'])->name('agencies.index');
        Route::get('/agencies/create', [AdminAgencyController::class, 'create'])->name('agencies.create');
        Route::post('/agencies', [AdminAgencyController::class, 'store'])->name('agencies.store');
        Route::get('/agencies/{agency}', [AdminAgencyController::class, 'show'])->name('agencies.show');
        Route::post('/agencies/{agency}/toggle-verify', [AdminAgencyController::class, 'toggleVerify'])->name('agencies.toggle-verify');
        Route::post('/agencies/{agency}/credits', [AdminAgencyController::class, 'updateCredits'])->name('agencies.credits');
        Route::delete('/agencies/{agency}', [AdminAgencyController::class, 'destroy'])->name('agencies.destroy');

        // Properties Management
        Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::patch('/properties/{property}/status', [AdminPropertyController::class, 'updateStatus'])->name('properties.status');
        Route::post('/properties/{property}/toggle-featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
        Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

        // VIP Properties Showcase Management
        Route::get('/vip-properties', [AdminVipPropertyController::class, 'index'])->name('vip.index');
        Route::post('/vip-properties', [AdminVipPropertyController::class, 'store'])->name('vip.store');
        Route::delete('/vip-properties/{property}', [AdminVipPropertyController::class, 'destroy'])->name('vip.destroy');

        // Hotels Management
        Route::get('/hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::get('/hotels/create', [AdminHotelController::class, 'create'])->name('hotels.create');
        Route::post('/hotels', [AdminHotelController::class, 'store'])->name('hotels.store');
        Route::get('/hotels/{hotel}/edit', [AdminHotelController::class, 'edit'])->name('hotels.edit');
        Route::put('/hotels/{hotel}', [AdminHotelController::class, 'update'])->name('hotels.update');
        Route::post('/hotels/{hotel}/toggle-status', [AdminHotelController::class, 'toggleStatus'])->name('hotels.toggle-status');
        Route::delete('/hotels/{hotel}', [AdminHotelController::class, 'destroy'])->name('hotels.destroy');

        // Analytics & Visitors
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::post('/analytics/reset', [AdminAnalyticsController::class, 'reset'])->name('analytics.reset');

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/password', [AdminSettingController::class, 'updatePassword'])->name('settings.password');
        Route::put('/settings/system', [AdminSettingController::class, 'updateCreditsSettings'])->name('settings.system');
    });
});
