<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DbSyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// DB sync API (used by localhost to pull data from server).
Route::get('/db-sync/ping', [DbSyncController::class, 'apiPing']);
Route::get('/db-sync/tables', [DbSyncController::class, 'apiTables']);
Route::get('/db-sync/columns', [DbSyncController::class, 'apiColumns']);
Route::post('/db-sync/chunk', [DbSyncController::class, 'apiChunk']);
Route::post('/db-sync/run-sql', [DbSyncController::class, 'apiRunSql']);
