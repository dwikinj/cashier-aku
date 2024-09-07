<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\ManagerController;
use App\Http\Controllers\Backend\ProfileController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    //register
    Route::get('/register', [AuthController::class, 'registerIndex'])->name('register');
    //register end

    //login
    Route::get('/login', [AuthController::class, 'loginIndex'])->name('login');
    //login end
});

Route::middleware('auth')->group(function () {
   Route::get('/dashboard',[ProfileController::class,'index'])->name('dashboard');
});

Route::middleware(['auth','role:admin'])->group(function () {
});

Route::middleware(['auth','role:manager'])->group(function () {

});
Route::middleware(['auth','role:cashier'])->group(function () {

});