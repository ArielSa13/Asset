<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetImportController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReportController;

// ── Auth routes (guest only) ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password',         [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password',        [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}',  [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',         [NewPasswordController::class, 'store'])->name('password.store');
});

// ── Logout ────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Protected routes ──────────────────────────────────────────────────
// ── Public routes (tanpa login) ──
Route::get('/request', [LoanRequestController::class, 'publicForm'])->name('loan-requests.public.form');
Route::post('/request', [LoanRequestController::class, 'publicStore'])->name('loan-requests.public.store');
Route::get('/request/sukses', [LoanRequestController::class, 'publicSuccess'])->name('loan-requests.public.success');

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Loan Requests (admin)
    Route::get('/loan-requests', [LoanRequestController::class, 'index'])->name('loan-requests.index');
    Route::post('/loan-requests/{loanRequest}/approve', [LoanRequestController::class, 'approve'])->name('loan-requests.approve');
    Route::post('/loan-requests/{loanRequest}/reject', [LoanRequestController::class, 'reject'])->name('loan-requests.reject');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Categories
    Route::resource('categories', AssetCategoryController::class)->except(['show']);
    Route::get('/categories/{category}/preview-code', [AssetCategoryController::class, 'previewCode'])->name('categories.preview-code');

    // Assets — urutan penting: generate-code & export/pdf harus SEBELUM resource
    Route::get('/assets/generate-code', [AssetController::class, 'generateCode'])->name('assets.generate-code');
    Route::get('/assets/import',          [AssetImportController::class, 'index'])->name('assets.import');
    Route::get('/assets/import/template', [AssetImportController::class, 'downloadTemplate'])->name('assets.import.template');
    Route::post('/assets/import/preview', [AssetImportController::class, 'preview'])->name('assets.import.preview');
    Route::post('/assets/import/process', [AssetImportController::class, 'import'])->name('assets.import.process');
    Route::get('/assets/export/pdf',    [AssetController::class, 'exportPdf'])->name('assets.pdf');
    Route::resource('assets', AssetController::class);

    // Loans — export/pdf sebelum resource
    Route::get('/loans/export/pdf',      [LoanController::class, 'exportAllPdf'])->name('loans.export-pdf');
    Route::resource('loans', LoanController::class);
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnAsset'])->name('loans.return');
    Route::get('/loans/{loan}/pdf',      [LoanController::class, 'exportPdf'])->name('loans.pdf');

    // Maintenance
    Route::get('/maintenance/export/pdf', [MaintenanceController::class, 'exportPdf'])->name('maintenance.pdf');
    Route::resource('maintenance', MaintenanceController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
