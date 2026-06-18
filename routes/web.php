<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Editor;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC ROUTES ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/plants', [PlantController::class, 'index'])->name('plants.index');
Route::get('/plants/{plant:slug}', [PlantController::class, 'show'])->name('plants.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// ─── AUTH ROUTES ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/verify-email', [AuthController::class, 'verifyForm'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendOtp'])->name('verification.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── USER ROUTES (auth) ───────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{plant:id}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ─── EDITOR ROUTES ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active', 'role:editor,admin'])->prefix('editor')->name('editor.')->group(function () {
    Route::get('/plants', [Editor\PlantController::class, 'index'])->name('plants.index');
    Route::get('/plants/create', [Editor\PlantController::class, 'create'])->name('plants.create');
    Route::post('/plants', [Editor\PlantController::class, 'store'])->name('plants.store');
    Route::get('/plants/{plant}/edit', [Editor\PlantController::class, 'edit'])->name('plants.edit');
    Route::put('/plants/{plant}', [Editor\PlantController::class, 'update'])->name('plants.update');
});

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Plants (admin can delete)
    Route::delete('/plants/{plant}', [Admin\PlantController::class, 'destroy'])->name('plants.destroy');

    // Categories CRUD
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Users management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
});
