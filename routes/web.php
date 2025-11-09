<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\SubmissionForm;
use App\Http\Livewire\AdminDashboard;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/form', SubmissionForm::class)->name('form');

// Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/qr-codes', \App\Http\Livewire\QRManagement::class)->name('admin.qr-codes');
    Route::get('/submissions', \App\Http\Livewire\SubmissionsList::class)->name('admin.submissions');
    Route::get('/download-qr', [\App\Http\Controllers\QRDownloadController::class, 'download'])->name('admin.download-qr');
});
