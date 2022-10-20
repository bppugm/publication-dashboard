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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::middleware('auth')->get('/home', function () {
    return redirect()->route('home');
});

Route::middleware('auth')->resource('data', DataController::class);
Route::middleware('auth')->resource('category', CategoryController::class);
Route::middleware('auth')->resource('user', UserController::class);

Route::get('/dashboard/preview/{dashboard}', [DashboardActivationController::class, 'show'])->name('dashboard.preview');
// Route dashboard/activate for activating a dashboard
Route::middleware('auth')->post('/dashboard/activation', [DashboardActivationController::class, 'store'])->name('dashboard.activation');
// Route dashboard/deactivate for deactivating a dashboard
Route::middleware('auth')->delete('/dashboard/deactivation/{dashboard}', [DashboardActivationController::class, 'destroy'])->name('dashboard.deactivation');
// Route dashboard/order for reordering a dashboard
Route::middleware('auth')->put('/dashboard/order', [DashboardActivationController::class, 'update'])->name('dashboard.order');
Route::middleware('auth')->resource('dashboard', DashboardController::class);
