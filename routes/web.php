<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CategoryDiscountController;
use App\Http\Controllers\Backend\CategoryCustomerDiscountController;
use App\Http\Controllers\Backend\CustomerDiscountController;
use App\Http\Controllers\Backend\DiscountController;
use App\Http\Controllers\Backend\KitchenController;
use App\Http\Controllers\Backend\RecipeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/',[App\Http\Controllers\Backend\DashboardController::class,'index'])->name('dashboard');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/category',CategoryController::class)->except(['show','create']);

Route::resource('/kitchen',KitchenController::class)->except(['show','create']);

Route::resource('/recipe',RecipeController::class)->except('show');

Route::resource('/discount',DiscountController::class)->except(['show','create']);

Route::resource('/customer',CustomerDiscountController::class)->except(['show','create']);

Route::resource('/category_discount',CategoryDiscountController::class)->parameter('category_discount','discount')->except(['show','create']);

Route::resource('/category_customer_discount',CategoryCustomerDiscountController::class)->parameter('category_customer_discount','customerDiscount')->except(['show','create']);