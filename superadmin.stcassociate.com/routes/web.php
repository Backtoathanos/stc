<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\TradingUsersController;
use App\Http\Controllers\ElectronicsUsersController;
use App\Http\Controllers\SchoolUsersController;
use App\Http\Controllers\GroceriesUsersController;
use App\Http\Controllers\SuperAdminUsersController;

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

Route::get('/', [AuthController::class, 'login_admin']);
Route::post('/', [AuthController::class, 'auth_login_admin']);
Route::get('/logout', [AuthController::class, 'logout_admin']);


Route::group(['middleware' => 'superadmin'], function(){

    Route::get('/dashboard', [DashboardController::class, 'show']);
    
    // user admin route
    Route::get('/users/admin', [SuperAdminUsersController::class, 'show']);

    Route::get('/users/admin/create', [SuperAdminUsersController::class, 'create']);

    Route::post('/users/admin/create', [SuperAdminUsersController::class, 'insert']);

    Route::get('/users/admin/edit/{id}', [SuperAdminUsersController::class, 'edit']);

    Route::post('/users/admin/edit/{id}', [SuperAdminUsersController::class, 'update']);
    
    Route::get('/users/admin/delete/{id}', [SuperAdminUsersController::class, 'delete']);

    // user trading route
    Route::get('/users/tradingusers', [TradingUsersController::class, 'show']);

    Route::get('/users/tradingusers/create', [TradingUsersController::class, 'create']);
    
    Route::post('/users/tradingusers/create', [TradingUsersController::class, 'insert']);

    Route::get('/users/tradingusers/edit/{id}', [TradingUsersController::class, 'edit']);

    Route::post('/users/tradingusers/edit/{id}', [TradingUsersController::class, 'update']);
    
    Route::get('/users/tradingusers/delete/{id}', [TradingUsersController::class, 'delete']);

    // user electronics route
    Route::get('/users/electronicsusers', [ElectronicsUsersController::class, 'show']);

    Route::get('/users/electronicsusers/create', [ElectronicsUsersController::class, 'create']);
    
    Route::post('/users/electronicsusers/create', [ElectronicsUsersController::class, 'insert']);

    Route::get('/users/electronicsusers/edit/{id}', [ElectronicsUsersController::class, 'edit']);

    Route::post('/users/electronicsusers/edit/{id}', [ElectronicsUsersController::class, 'update']);
    
    Route::get('/users/electronicsusers/delete/{id}', [ElectronicsUsersController::class, 'delete']);

    // user electronics route
    Route::get('/users/schoolusers', [SchoolUsersController::class, 'show']);

    Route::get('/users/schoolusers/create', [SchoolUsersController::class, 'create']);
    
    Route::post('/users/schoolusers/create', [SchoolUsersController::class, 'insert']);

    Route::get('/users/schoolusers/edit/{id}', [SchoolUsersController::class, 'edit']);

    Route::post('/users/schoolusers/edit/{id}', [SchoolUsersController::class, 'update']);
    
    Route::get('/users/schoolusers/delete/{id}', [SchoolUsersController::class, 'delete']);
    
    // user groceries route
    Route::get('/users/groceriesusers', [GroceriesUsersController::class, 'show']);

    Route::get('/users/groceriesusers/create', [GroceriesUsersController::class, 'create']);
    
    Route::post('/users/groceriesusers/create', [GroceriesUsersController::class, 'insert']);

    Route::get('/users/groceriesusers/edit/{id}', [GroceriesUsersController::class, 'edit']);

    Route::post('/users/groceriesusers/edit/{id}', [GroceriesUsersController::class, 'update']);
    
    Route::get('/users/groceriesusers/delete/{id}', [GroceriesUsersController::class, 'delete']);
    
    // user admin route
    Route::get('/users/uadminusers', [AdminUsersController::class, 'show']);

    Route::get('/users/uadminusers/create', [AdminUsersController::class, 'create']);
    
    Route::post('/users/uadminusers/create', [AdminUsersController::class, 'insert']);

    Route::get('/users/uadminusers/edit/{id}', [AdminUsersController::class, 'edit']);

    Route::post('/users/uadminusers/edit/{id}', [AdminUsersController::class, 'update']);
    
    Route::get('/users/uadminusers/delete/{id}', [AdminUsersController::class, 'delete']);
});
