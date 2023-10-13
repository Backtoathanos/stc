<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\TradingUsersController;

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

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    
    Route::get('/users/admin', [AdminUsersController::class, 'show']);

    Route::get('/users/admin/create', [AdminUsersController::class, 'create']);

    Route::post('/users/admin/create', [AdminUsersController::class, 'insert']);

    Route::get('/users/admin/edit/{id}', [AdminUsersController::class, 'edit']);

    Route::post('/users/admin/edit/{id}', [AdminUsersController::class, 'update']);
    
    Route::get('/users/admin/delete/{id}', [AdminUsersController::class, 'delete']);

    
    Route::post('/users/tradingusers/create', [AdminUsersController::class, 'insert']);

    Route::get('/users/tradingusers', [TradingUsersController::class, 'show']);

    Route::get('/users/tradingusers/create', [TradingUsersController::class, 'create']);
    
    Route::post('/users/tradingusers/create', [TradingUsersController::class, 'insert']);

    // Route::get('/users/tradingusers', function() {
    //     $data['page_title']="Trading Users";
    //     return view('pages.tradinguser', $data);
    // });
    
    Route::get('/users/electronicsusers', function() {
        $data['page_title']="Electronics Users";
        return view('pages.electronicsuser', $data);
    });
});


Route::get('/', function() {
    return view('pages.welcome');
});
