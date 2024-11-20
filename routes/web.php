<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseInfoController;
use App\Http\Controllers\SaleInfoController;
use App\Http\Controllers\IncomeExpenseReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return redirect('login');

})->name('login');


Route::get('/dashboard', function () {
    //return view('dashboard');

    return view('admin.dashboard');

})->middleware(['auth'])->name('dashboard');

// Supplier

Route::get('dashboard/supplier', [SupplierController::class, 'index'])->name('all_supplier');
Route::get('dashboard/supplier/add/', [SupplierController::class, 'add'])->name('add_supplier');
Route::get('dashboard/supplier/edit/{slug}', [SupplierController::class, 'edit'])->name('edit_supplier');
Route::post('dashboard/supplier/submit', [SupplierController::class, 'submit'])->name('submit_supplier');
Route::post('dashboard/supplier/update', [SupplierController::class, 'update'])->name('update_supplier');
Route::post('dashboard/supplier/delete', [SupplierController::class, 'delete'])->name('delete_supplier');
Route::get('dashboard/supplier/ban/{slug}', [SupplierController::class, 'ban'])->name('ban_supplier');
Route::get('dashboard/supplier/unban/{slug}', [SupplierController::class, 'unban'])->name('unban_supplier');
Route::get('dashboard/supplier/send/email/{slug}', [SupplierController::class, 'send_email'])->name('send_email_supplier');
Route::post('dashboard/supplier/send/email', [SupplierController::class, 'submit_send_email'])->name('submit_send_email');

// Products

Route::get('dashboard/product',[ProductsController::class,'index'])->name('product.index');
Route::get('dashboard/product-create',[ProductsController::class,'create'])->name('product.create');
Route::post('dashboard/product-create',[ProductsController::class,'store'])->name('product.store');
Route::get('dashboard/product/edit/{slug}',[ProductsController::class,'edit'])->name('product_edit');
Route::post('dashboard/product/update', [ProductsController::class, 'update'])->name('product.update');
Route::delete('dashboard/product/delete/{id}', [ProductsController::class, 'destroy'])->name('product_delete');

// Purchase Info

Route::get('dashboard/purchase-info',[PurchaseInfoController::class,'index'])->name('purchase-info.index');
Route::get('dashboard/purchase-info-create',[PurchaseInfoController::class,'create'])->name('purchase-info.create');
Route::post('dashboard/purchase-info-create',[PurchaseInfoController::class,'store'])->name('purchase-info.store');
Route::get('dashboard/purchase-info/edit/{slug}',[PurchaseInfoController::class,'edit'])->name('purchase-info-edit');
Route::post('dashboard/purchase-info/update', [PurchaseInfoController::class, 'update'])->name('purchase-info.update');
Route::delete('dashboard/purchase-info/delete/{id}', [PurchaseInfoController::class, 'destroy'])->name('purchase-info-delete');

// Sale Info

Route::get('dashboard/sale-info',[SaleInfoController::class,'index'])->name('sale-info.index');
Route::get('dashboard/sale-info-create',[SaleInfoController::class,'create'])->name('sale-info.create');
Route::post('dashboard/sale-info-create',[SaleInfoController::class,'store'])->name('sale-info.store');
Route::get('dashboard/sale-info/edit/{slug}',[SaleInfoController::class,'edit'])->name('sale-info-edit');
Route::post('dashboard/sale-info/update', [SaleInfoController::class, 'update'])->name('sale-info.update');
Route::delete('dashboard/sale-info/delete/{id}', [SaleInfoController::class, 'destroy'])->name('sale-info-delete');

// Invoice

Route::get('dashboard/sale-info/view/{slug}', [SaleInfoController::class, 'view'])->name('sale-info-view');

// Stock

Route::get('dashboard/stock', [PurchaseInfoController::class, 'stock'])->name('stock');

// Income and expense report

Route::get('admin/report', [IncomeExpenseReportController::class, 'index']);
Route::get('admin/custom/report', [IncomeExpenseReportController::class, 'custom_report']);
Route::get('admin/report/search/{from}/{to}', [IncomeExpenseReportController::class, 'search']);

//user profile

Route::get('/dashboard/profile',[UserController::class ,'userprofile']);
Route::get('/dashboard/edit-profile/{slug}',[UserController::class ,'editprofile']);
Route::get('/dashboard/edit-profile-photo/{slug}',[UserController::class ,'editprofilePhoto']);

Route::post('/dashboard/update_user_info',[UserController::class ,'updateUserInfo'])->name('update_user_info');
Route::post('/dashboard/update_user_photo',[UserController::class ,'updateUserPhoto'])->name('update_user_photo');
Route::get('/dashboard/editpassword/{slug}',[UserController::class ,'ChangePasswordform']);
Route::post('change-password/{id}', [UserController::class,'changePassword'])->name('change.password');


require __DIR__.'/auth.php';
