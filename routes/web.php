<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BerandaController;

// 🔐 ROUTE LOGIN
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/proses_login', [LoginController::class, 'proses_login'])->name('proses_login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// 🏠 ROUTE BERANDA
Route::get('/', [BerandaController::class, 'index'])->name('beranda');




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