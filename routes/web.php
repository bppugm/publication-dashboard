<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardActivationController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->resource('data', DataController::class);
Route::middleware('auth')->resource('category', CategoryController::class);
Route::middleware('auth')->resource('user', UserController::class);

Route::get('/dashboard/preview/{dashboard}', [DashboardActivationController::class, 'show'])->name('dashboard.preview');
Route::middleware('auth')->post('/dashboard/activation', [DashboardActivationController::class, 'store'])->name('dashboard.activation');
// Route dashboard/activate for delete
Route::middleware('auth')->delete('/dashboard/deactivation/{dashboard}', [DashboardActivationController::class, 'destroy'])->name('dashboard.deactivation');
// Route dashboard/activate for update
Route::middleware('auth')->put('/dashboard/order', [DashboardActivationController::class, 'update'])->name('dashboard.order');
Route::middleware('auth')->resource('dashboard', DashboardController::class);
