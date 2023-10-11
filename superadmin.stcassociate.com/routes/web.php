<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
        
    Route::get('/dashboard', function() {
        return view('pages.dashboard');
    });
});


Route::get('/', function() {
    return view('pages.welcome');
});

Route::get('/image', function() {
    return view('image');
});
