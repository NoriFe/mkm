<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{sku}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::post('/products/export', [ProductController::class, 'export'])->name('products.export');
Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
Route::post('/products/csv', [ProductController::class, 'storeCsv'])->name('products.storeCsv');



