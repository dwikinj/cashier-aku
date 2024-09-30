<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\ManagerController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\SaleController;
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
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');

    //categories product routes
    Route::resource('category-data', CategoryController::class);
    Route::get('category-product', [CategoryController::class, 'categoryProduct'])->name('categoryProduct');

    //product routes
    Route::resource('product-data', ProductController::class);
    Route::get('display-product', [ProductController::class, 'displayProduct'])->name('displayProduct');
    Route::delete('product-data-delete-all', [ProductController::class, 'destroyAll'])->name('product-data.destroyall');
    Route::get('print-product-barcode', [ProductController::class, 'printProductsBarcode'])->name('product-data.printbarcode');

    //member routes
    Route::resource('member-data', MemberController::class);
    Route::get('display-member', [MemberController::class, 'displayMember'])->name('displayMember');
    Route::get('print-member-barcode', [MemberController::class, 'printProductsBarcode'])->name('member-data.printbarcode');

    //settings routes
    Route::controller(SettingController::class)->group(function () {
        Route::get('admin/settings','showAdminSetting')->name('admin.settings.index');
        Route::patch('admin/settings', 'updateAdminSetting')->name('admin.settings.update');
    });

    //supplier routes
    Route::resource('supplier-data', SupplierController::class);
    Route::get('display-supplier', [SupplierController::class, 'displaySupplier'])->name('displaySupplier');

    //supplier routes
    Route::resource('expense-data', ExpenseController::class);
    Route::get('display-expense', [ExpenseController::class, 'displayExpense'])->name('displayExpense');

    //purchase routes
    Route::resource('purchase-data', PurchaseController::class);
    Route::get('display-purchase', [PurchaseController::class, 'displayPurchase'])->name('displayPurchase');
    Route::get('purchase-supplier', [PurchaseController::class, 'purchaseSupplier'])->name('purchaseSupplier');

    //purchase details routes
    Route::get('purchase-detail/{purchase}', [PurchaseDetailController::class, 'index'])->name('purchase-detail.index');
    Route::get('purchase-products', [PurchaseDetailController::class, 'purchaseProducts'])->name('purchase-detail.products');
    Route::post('purchase-detail', [PurchaseDetailController::class, 'store'])->name('purchase-detail.store');
    Route::get('purchase-detail/{purchase}/edit', [PurchaseDetailController::class, 'edit'])->name('purchase-detail.edit');
    Route::patch('purchase-detail/{purchase}', [PurchaseDetailController::class, 'update'])->name('purchase-detail.update');
    Route::get('purchase-detail/{purchase}/products', [PurchaseDetailController::class, 'showPurchasedProducts'])->name('purchased-products');

    //sale routes 
    Route::controller(SaleController::class)->group(function () {
        Route::get('admin/sales','showSalesPage')->name('admin.sales.index');
        Route::get('admin/sales/datatable','dataTable')->name('admin.sales.datatable');
    });

});

Route::middleware(['auth', 'role:admin'])->group(function () {});

Route::middleware(['auth', 'role:manager'])->group(function () {});
Route::middleware(['auth', 'role:cashier'])->group(function () {});
