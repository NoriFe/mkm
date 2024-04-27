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
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/export', [ProductController::class, 'export']);
Route::post('/products/import', [ProductController::class, 'import']);

Route::get('/', function () {
    return view('welcome');
});
