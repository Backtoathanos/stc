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
use App\Http\Controllers\ManagerUsersController;
use App\Http\Controllers\SiteUsersController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\branchElectronicsController;
use App\Http\Controllers\branchSiteController;
use App\Http\Controllers\SchoolController;

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
    
    // user manager route
    Route::get('/users/managerusers', [ManagerUsersController::class, 'show']);

    Route::get('/users/managerusers/create', [ManagerUsersController::class, 'create']);
    
    Route::post('/users/managerusers/create', [ManagerUsersController::class, 'insert']);

    Route::get('/users/managerusers/edit/{id}', [ManagerUsersController::class, 'edit']);

    Route::post('/users/managerusers/edit/{id}', [ManagerUsersController::class, 'update']);
    
    Route::get('/users/managerusers/delete/{id}', [ManagerUsersController::class, 'delete']);
    
    // user site user route
    Route::get('/users/siteusers', [SiteUsersController::class, 'show']);

    Route::get('/users/siteusers/create', [SiteUsersController::class, 'create']);
    
    Route::post('/users/siteusers/create', [SiteUsersController::class, 'insert']);

    Route::get('/users/siteusers/edit/{id}', [SiteUsersController::class, 'edit']);

    Route::post('/users/siteusers/edit/{id}', [SiteUsersController::class, 'update']);
    
    Route::get('/users/siteusers/delete/{id}', [SiteUsersController::class, 'delete']);
    
    //  city through ajax
    Route::get('/master/city', [CityController::class, 'show']);    
    Route::get('/master/city/list', [CityController::class, 'list']);
    Route::post('/master/city/create', [CityController::class, 'create']); 
    Route::post('/master/city/edit', [CityController::class, 'update']);    
    Route::get('/master/city/delete', [CityController::class, 'delete']);

    //  state through ajax
    Route::get('/master/state', [StateController::class, 'show']);    
    Route::get('/master/state/list', [StateController::class, 'list']);
    Route::post('/master/state/create', [StateController::class, 'create']); 
    Route::post('/master/state/edit', [StateController::class, 'update']);    
    Route::get('/master/state/delete', [StateController::class, 'delete']);

    //  category through ajax
    Route::get('/master/category', [CategoryController::class, 'show']);    
    Route::get('/master/category/list', [CategoryController::class, 'list']);
    Route::post('/master/category/create', [CategoryController::class, 'create']); 
    Route::post('/master/category/edit', [CategoryController::class, 'update']);    
    Route::get('/master/category/delete', [CategoryController::class, 'delete']);

    //  subcategory through ajax
    Route::get('/master/subcategory', [SubCategoryController::class, 'show']);    
    Route::get('/master/subcategory/list', [SubCategoryController::class, 'list']);
    Route::post('/master/subcategory/create', [SubCategoryController::class, 'create']); 
    Route::post('/master/subcategory/edit', [SubCategoryController::class, 'update']);    
    Route::get('/master/subcategory/delete', [SubCategoryController::class, 'delete']);

    //  brand through ajax
    Route::get('/master/brand', [BrandController::class, 'show']);    
    Route::get('/master/brand/list', [BrandController::class, 'list']);
    Route::post('/master/brand/create', [BrandController::class, 'create']); 
    Route::post('/master/brand/edit', [BrandController::class, 'update']);    
    Route::get('/master/brand/delete', [BrandController::class, 'delete']);

    //  rack through ajax
    Route::get('/master/rack', [RackController::class, 'show']);    
    Route::get('/master/rack/list', [RackController::class, 'list']);
    Route::post('/master/rack/create', [RackController::class, 'create']); 
    Route::post('/master/rack/edit', [RackController::class, 'update']);    
    Route::get('/master/rack/delete', [RackController::class, 'delete']);

    //  inventory through ajax
    Route::get('/master/inventory', [InventoryController::class, 'show']);    
    Route::get('/master/inventory/list', [InventoryController::class, 'list']);
    Route::post('/master/inventory/create', [InventoryController::class, 'create']); 
    Route::post('/master/inventory/edit', [InventoryController::class, 'update']);    
    Route::get('/master/inventory/delete', [InventoryController::class, 'delete']);

    //  product through ajax
    Route::get('/master/product', [ProductController::class, 'show']);    
    Route::get('/master/product/list', [ProductController::class, 'list']);
    Route::post('/master/product/create', [ProductController::class, 'create']); 
    Route::post('/master/product/edit', [ProductController::class, 'update']);    
    Route::get('/master/product/delete', [ProductController::class, 'delete']);

    Route::get('/branch/electronicsbranch', [branchElectronicsController::class, 'show']);  
    Route::get('/branch/electronicsbranch/list', [branchElectronicsController::class, 'list']);  
    Route::post('/branch/electronicsbranch/getSP', [branchElectronicsController::class, 'getSP']);
    Route::post('/branch/electronicsbranch/edit', [branchElectronicsController::class, 'update']);    
    Route::get('/branch/electronicsbranch/delete', [branchElectronicsController::class, 'delete']);

    Route::get('/branch/sitebranch', [branchSiteController::class, 'show']);  
    Route::get('/branch/sitebranch/list', [branchSiteController::class, 'list']);  
    Route::post('/branch/sitebranch/getSP', [branchSiteController::class, 'getSP']);
    Route::post('/branch/sitebranch/edit', [branchSiteController::class, 'update']);    
    Route::get('/branch/sitebranch/delete', [branchSiteController::class, 'delete']);  
    Route::get('/branch/sitebranch/getusers', [branchSiteController::class, 'getusers']);

    // for school monthly fee
    Route::get('/branch/school/monthlyclosing', [SchoolController::class, 'show']);    
    Route::get('/branch/school/monthlyclosing/list', [SchoolController::class, 'list']);
    Route::post('/branch/school/monthlyclosing/edit', [SchoolController::class, 'update']);   
    Route::get('/branch/school/monthlyclosing/delete', [SchoolController::class, 'delete']);
    
    
    Route::get('/branch/school/fee', [SchoolController::class, 'feeshow']);    
    Route::get('/branch/school/fee/list', [SchoolController::class, 'feelist']); 
    Route::get('/branch/school/fee/delete', [SchoolController::class, 'feedelete']);
    
    
    Route::get('/branch/school/canteen', [SchoolController::class, 'canteenshow']);    
    Route::get('/branch/school/canteen/list', [SchoolController::class, 'canteenlist']);
    Route::get('/branch/school/canteen/delete', [SchoolController::class, 'canteendelete']);
});
