<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DistributorController; // Corrected the controller name
use App\Http\Controllers\OrderController;

Route::get('/', [CommissionController::class, 'report'])->name('commission.report');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
// Route for top distributors report
Route::get('/top-distributors', [DistributorController::class, 'topDistributors'])->name('distributors.top');



