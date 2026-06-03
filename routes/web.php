<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PemeriksaanJentikController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pemeriksaan-jentik/create', [PemeriksaanJentikController::class, 'create'])->name('pemeriksaan-jentik.create');
    Route::post('/pemeriksaan-jentik/preview', [PemeriksaanJentikController::class, 'preview'])->name('pemeriksaan-jentik.preview');
    Route::post('/admin/pemeriksaan/store', [PemeriksaanJentikController::class, 'store'])->name('pemeriksaan.store');
    
    Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::patch('/users/{user}/archive', [UserController::class, 'archive'])->name('users.archive');
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});


