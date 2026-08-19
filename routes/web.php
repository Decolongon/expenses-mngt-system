<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified',Inertia\Middleware\EncryptHistory::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('expenses', ExpensesController::class);
    Route::resource('budgets', BudgetController::class)->except('show');
});

require __DIR__.'/settings.php';
