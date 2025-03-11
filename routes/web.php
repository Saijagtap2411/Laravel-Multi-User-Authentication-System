<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/account/authenticate',[LoginController::class,'authenticate'])->name('account.authenticate');


Route::group(['prefix'=>'account'],function(){

    Route::group(['middleware'=>'guest'],function(){
        Route::get('login',[LoginController::class,'index'])->name('account.login');
Route::get('register',[LoginController::class,'register'])->name('account.register');
Route::post('process-register',[LoginController::class,'processRegister'])->name('account.processRegister');



    });

    Route::group(['middleware'=>'auth'],function(){
        Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');

        Route::get('logout',[LoginController::class,'logout'])->name('account.logout');

    });
});

// Route::get('admin/login',[AdminLoginController::class,'index'])->name('admin.login');
// Route::get('admin/dashboard',[AdminDashboardController::class,'index'])->name('admin.dashboard');
// Route::post('/admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

// Route::get('admin/logout',[AdminLoginController::class,'logout'])->name('admin.logout');

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});