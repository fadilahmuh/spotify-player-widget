<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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

Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/login', [MainController::class, 'login'])->name('login');
Route::get('/login2', [MainController::class, 'login2'])->name('login2');
Route::get('/v1/{id}', [MainController::class, 'player'])->name('player');
Route::get('/callback', [MainController::class, 'callback']);
Route::get('/call-test', [MainController::class, 'callback2']);
Route::put('/redirect', [MainController::class, 'redirect']);
Route::put('/redirect2', [MainController::class, 'redirect2']);
Route::get('/test2', [MainController::class, 'test']);
Route::get('/http-test', [MainController::class, 'http_test']);
Route::get('/test', function() {
    return view('test_js');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');