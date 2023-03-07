<?php

use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\OrderController;
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

// Route::get('/', [LandingpageController::class, 'index']);

Route::get('/form-detail', [LandingpageController::class, 'formDetail']);
Route::any('/registrasi', [LandingpageController::class, 'registrasi']);
// Route::get('/{kategori?}', [LandingpageController::class, 'index']);

Route::get('/', [OrderController::class, 'show']);
Route::get('/order', [OrderController::class, 'show']);
Route::get('/order/{slug?}', [OrderController::class, 'show']);

// Route::get('/', function () {
//     return view('welcome');
// });
