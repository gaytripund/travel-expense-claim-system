<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/claims');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/claims', [ExpenseClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/create', [ExpenseClaimController::class, 'create'])->name('claims.create');
    Route::post('/claims', [ExpenseClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}', [ExpenseClaimController::class, 'show'])->name('claims.show');
    Route::get('/claims/{claim}/edit', [ExpenseClaimController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ExpenseClaimController::class, 'update'])->name('claims.update');

    Route::middleware('role:manager')->group(function () {
        Route::put('/claims/{claim}/approve', [ExpenseClaimController::class, 'approve'])->name('claims.approve');
        Route::put('/claims/{claim}/reject', [ExpenseClaimController::class, 'reject'])->name('claims.reject');
        Route::put('/claims/{claim}/return', [ExpenseClaimController::class, 'returnForCorrection'])->name('claims.return');
    });
});
