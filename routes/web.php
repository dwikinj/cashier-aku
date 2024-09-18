<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\ManagerController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    //register
    Route::get('/register', [AuthController::class, 'registerIndex'])->name('register');
    //register end

    //login
    Route::get('/', [AuthController::class, 'loginIndex'])->name('login');
    //login end
});

Route::middleware('auth')->group(function () {
   Route::get('/dashboard',[ProfileController::class,'index'])->name('dashboard');

   //categories product routes
   Route::resource('category-data',CategoryController::class);
   Route::get('category-product',[CategoryController::class,'categoryProduct'])->name('categoryProduct');

   //product routes
   Route::resource('product-data',ProductController::class);
   Route::get('display-product',[ProductController::class,'displayProduct'])->name('displayProduct');
   Route::delete('product-data-delete-all',[ProductController::class,'destroyAll'])->name('product-data.destroyall');
   Route::get('print-product-barcode',[ProductController::class,'printProductsBarcode'])->name('product-data.printbarcode');

   //member routes
   Route::resource('member-data',MemberController::class);
   Route::get('display-member',[MemberController::class,'displayMember'])->name('displayMember');
   Route::get('print-member-barcode',[MemberController::class,'printProductsBarcode'])->name('member-data.printbarcode');
   
   //settings routes
   Route::resource('settings',SettingController::class);
   Route::get('display-settings',[SettingController::class,'displaySetting'])->name('displaySetting');

   //supplier routes
   Route::resource('supplier-data',SupplierController::class);
   Route::get('display-supplier',[SupplierController::class,'displaySupplier'])->name('displaySupplier');

});

Route::middleware(['auth','role:admin'])->group(function () {
});

Route::middleware(['auth','role:manager'])->group(function () {

});
Route::middleware(['auth','role:cashier'])->group(function () {

});