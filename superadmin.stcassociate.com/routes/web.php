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
use App\Http\Controllers\UsersHubController;
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
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\STDController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\POAdhocController;
use App\Http\Controllers\PPETrackerController;
use App\Http\Controllers\ToolTrackerController;
use App\Http\Controllers\GldChallanController;
use App\Http\Controllers\DbSyncController;

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

    // DB sync (server -> local)
    Route::get('/db-sync', [DbSyncController::class, 'index']);
    Route::post('/db-sync/sync', [DbSyncController::class, 'sync']);
    Route::post('/db-sync/run-server-query', [DbSyncController::class, 'runServerQuery']);
    
    // user admin route
    Route::get('/users/admin', [SuperAdminUsersController::class, 'show']);
    Route::get('/users/admin/create', [SuperAdminUsersController::class, 'create']);
    Route::post('/users/admin/create', [SuperAdminUsersController::class, 'insert']);
    Route::get('/users/admin/edit/{id}', [SuperAdminUsersController::class, 'edit']);
    Route::post('/users/admin/edit/{id}', [SuperAdminUsersController::class, 'update']);    
    Route::get('/users/admin/delete/{id}', [SuperAdminUsersController::class, 'delete']);

    Route::get('/users', [UsersHubController::class, 'index']);
    Route::get('/users/hub/record/{type}/{id}', [UsersHubController::class, 'recordJson']);

    Route::post('/users/tradingusers/create', [TradingUsersController::class, 'insert']);
    Route::post('/users/tradingusers/edit/{id}', [TradingUsersController::class, 'update']);
    Route::post('/users/tradingusers/delete/{id}', [TradingUsersController::class, 'delete']);

    Route::post('/users/electronicsusers/create', [ElectronicsUsersController::class, 'insert']);
    Route::post('/users/electronicsusers/edit/{id}', [ElectronicsUsersController::class, 'update']);
    Route::post('/users/electronicsusers/delete/{id}', [ElectronicsUsersController::class, 'delete']);

    Route::post('/users/schoolusers/create', [SchoolUsersController::class, 'insert']);
    Route::post('/users/schoolusers/edit/{id}', [SchoolUsersController::class, 'update']);
    Route::post('/users/schoolusers/delete/{id}', [SchoolUsersController::class, 'delete']);

    Route::post('/users/groceriesusers/create', [GroceriesUsersController::class, 'insert']);
    Route::post('/users/groceriesusers/edit/{id}', [GroceriesUsersController::class, 'update']);
    Route::post('/users/groceriesusers/delete/{id}', [GroceriesUsersController::class, 'delete']);

    Route::post('/users/uadminusers/create', [AdminUsersController::class, 'insert']);
    Route::post('/users/uadminusers/edit/{id}', [AdminUsersController::class, 'update']);
    Route::post('/users/uadminusers/delete/{id}', [AdminUsersController::class, 'delete']);

    Route::post('/users/managerusers/create', [ManagerUsersController::class, 'insert']);
    Route::post('/users/managerusers/edit/{id}', [ManagerUsersController::class, 'update']);
    Route::post('/users/managerusers/delete/{id}', [ManagerUsersController::class, 'delete']);

    Route::post('/users/siteusers/create', [SiteUsersController::class, 'insert']);
    Route::post('/users/siteusers/edit/{id}', [SiteUsersController::class, 'update']);
    Route::post('/users/siteusers/delete/{id}', [SiteUsersController::class, 'delete']);
    
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

    // for electronics branch
    Route::get('/branch/electronicsbranch', [branchElectronicsController::class, 'show']);  
    Route::get('/branch/electronicsbranch/list', [branchElectronicsController::class, 'list']);  
    Route::post('/branch/electronicsbranch/getSP', [branchElectronicsController::class, 'getSP']);
    Route::post('/branch/electronicsbranch/edit', [branchElectronicsController::class, 'update']);    
    Route::get('/branch/electronicsbranch/delete', [branchElectronicsController::class, 'delete']);

    // for site branch
    Route::get('/branch/sitebranch', [branchSiteController::class, 'show']);  
    Route::get('/branch/sitebranch/list', [branchSiteController::class, 'list']);  
    Route::post('/branch/sitebranch/getSP', [branchSiteController::class, 'getSP']);
    Route::post('/branch/sitebranch/edit', [branchSiteController::class, 'update']);    
    Route::get('/branch/sitebranch/delete', [branchSiteController::class, 'delete']);  
    Route::get('/branch/sitebranch/getusers', [branchSiteController::class, 'getusers']);

    // for school monthly closing
    Route::get('/branch/school/monthlyclosing', [SchoolController::class, 'show']);    
    Route::get('/branch/school/monthlyclosing/list', [SchoolController::class, 'list']);
    Route::post('/branch/school/monthlyclosing/edit', [SchoolController::class, 'update']);   
    Route::get('/branch/school/monthlyclosing/delete', [SchoolController::class, 'delete']);
    
    // for school fee
    Route::get('/branch/school/fee', [SchoolController::class, 'feeshow']);    
    Route::get('/branch/school/fee/list', [SchoolController::class, 'feelist']); 
    Route::get('/branch/school/fee/delete', [SchoolController::class, 'feedelete']);
    
    // for school canteen
    Route::get('/branch/school/canteen', [SchoolController::class, 'canteenshow']);    
    Route::get('/branch/school/canteen/list', [SchoolController::class, 'canteenlist']);
    Route::get('/branch/school/canteen/delete', [SchoolController::class, 'canteendelete']);
    
    // for stc requisition
    Route::get('/branch/stc/projects', [ProjectController::class, 'show']);    
    Route::get('/branch/stc/projects/list', [ProjectController::class, 'list']);
    Route::get('/branch/stc/projects/get', [ProjectController::class, 'getProject']);
    Route::post('/branch/stc/projects/save', [ProjectController::class, 'saveProject']);
    Route::get('/branch/stc/projects/delete', [ProjectController::class, 'delete']);
    Route::get('/branch/stc/projects/collaborations/list', [ProjectController::class, 'collaborationsList']);
    Route::get('/branch/stc/projects/collaborations/remove', [ProjectController::class, 'removeCollaborator']);
    Route::post('/branch/stc/projects/collaborations/bulk-remove', [ProjectController::class, 'bulkRemoveCollaborators']);
    // for departments
    Route::get('/branch/stc/departments/list', [ProjectController::class, 'departmentsList']);
    Route::get('/branch/stc/departments/get', [ProjectController::class, 'getDepartment']);
    Route::post('/branch/stc/departments/save', [ProjectController::class, 'saveDepartment']);
    Route::get('/branch/stc/departments/delete', [ProjectController::class, 'deleteDepartment']);
    Route::post('/branch/stc/departments/bulk-status', [ProjectController::class, 'bulkStatusDepartments']);
    Route::post('/branch/stc/departments/bulk-delete', [ProjectController::class, 'bulkDeleteDepartments']);
    
    // for stc requisition
    Route::get('/branch/stc/requisitions', [RequisitionController::class, 'show']);    
    Route::get('/branch/stc/requisitions/list', [RequisitionController::class, 'list']);
    Route::get('/branch/stc/requisitions/delete', [RequisitionController::class, 'delete']);
    Route::get('/branch/stc/requisitions/itemlist', [RequisitionController::class, 'itemlist']);
    Route::get('/branch/stc/requisitions/itemdelete', [RequisitionController::class, 'itemdelete']);
    Route::get('/branch/stc/requisitions/itemdislist', [RequisitionController::class, 'itemdislist']);
    Route::get('/branch/stc/requisitions/itemdisdelete', [RequisitionController::class, 'itemdisdelete']);
    
    // for stc std
    Route::get('/branch/stc/std', [STDController::class, 'show']);    
    Route::post('/branch/stc/std/list', [STDController::class, 'list']);
    Route::get('/branch/stc/std/delete', [STDController::class, 'delete']);
    
    // for stc equipment
    Route::get('/branch/stc/equipment', [EquipmentController::class, 'show']);    
    Route::post('/branch/stc/equipment/list', [EquipmentController::class, 'list']);
    Route::get('/branch/stc/equipment/delete', [EquipmentController::class, 'delete']);
    Route::post('/branch/stc/equipment/logs', [EquipmentController::class, 'logs']);
    
    // for stc po adhoc
    Route::get('/branch/stc/poadhoc', [POAdhocController::class, 'show']);    
    Route::post('/branch/stc/poadhoc/list', [POAdhocController::class, 'list']);
    Route::get('/branch/stc/poadhoc/get', [POAdhocController::class, 'get']);
    Route::get('/branch/stc/poadhoc/shops', [POAdhocController::class, 'shopsByAdhoc']);
    Route::post('/branch/stc/poadhoc/shop', [POAdhocController::class, 'shopStore']);
    Route::post('/branch/stc/poadhoc/shop/update', [POAdhocController::class, 'shopUpdate']);
    Route::post('/branch/stc/poadhoc/shop/delete', [POAdhocController::class, 'shopDestroy']);
    Route::post('/branch/stc/poadhoc/edit', [POAdhocController::class, 'update']);
    Route::get('/branch/stc/poadhoc/delete', [POAdhocController::class, 'delete']);

    // for stc ppe tracker
    Route::get('/branch/stc/ppetracker', [PPETrackerController::class, 'show']);    
    Route::post('/branch/stc/ppetracker/list', [PPETrackerController::class, 'list']);
    Route::get('/branch/stc/ppetracker/get', [PPETrackerController::class, 'get']);
    Route::post('/branch/stc/ppetracker/edit', [PPETrackerController::class, 'update']);
    Route::get('/branch/stc/ppetracker/delete', [PPETrackerController::class, 'delete']);

    // for stc tool tracker
    Route::get('/branch/stc/tooltracker', [ToolTrackerController::class, 'show']);    
    Route::post('/branch/stc/tooltracker/list', [ToolTrackerController::class, 'list']);
    Route::get('/branch/stc/tooltracker/get', [ToolTrackerController::class, 'get']);
    Route::post('/branch/stc/tooltracker/edit', [ToolTrackerController::class, 'update']);
    Route::get('/branch/stc/tooltracker/delete', [ToolTrackerController::class, 'delete']);
    Route::get('/branch/stc/tooltracker/list-track', [ToolTrackerController::class, 'listTrack']);
    Route::get('/branch/stc/tooltracker/list-track-no-project', [ToolTrackerController::class, 'listTrackNoProject']);
    Route::get('/branch/stc/tooltracker/get-track', [ToolTrackerController::class, 'getTrack']);
    Route::get('/branch/stc/tooltracker/projects-by-user', [ToolTrackerController::class, 'getProjectsByUser']);
    Route::post('/branch/stc/tooltracker/edit-track', [ToolTrackerController::class, 'updateTrack']);
    Route::get('/branch/stc/tooltracker/delete-track', [ToolTrackerController::class, 'deleteTrack']);
    Route::post('/branch/stc/tooltracker/delete-track-bulk', [ToolTrackerController::class, 'deleteTrackBulk']);

    // GLD challan
    Route::get('/branch/stc/gld', [GldChallanController::class, 'show']);
    Route::get('/branch/stc/gld/filter-racks', [GldChallanController::class, 'filterRacks']);
    Route::post('/branch/stc/gld/list', [GldChallanController::class, 'list']);
    Route::get('/branch/stc/gld/get', [GldChallanController::class, 'get']);
    Route::post('/branch/stc/gld/store', [GldChallanController::class, 'store']);
    Route::post('/branch/stc/gld/edit', [GldChallanController::class, 'update']);
    Route::get('/branch/stc/gld/delete', [GldChallanController::class, 'delete']);
});
