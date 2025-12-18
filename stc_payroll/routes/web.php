<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\GangController;

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

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// Master Routes
Route::prefix('master')->group(function () {
    // Quick create endpoints for use in employee form dropdowns (must be before other routes to avoid conflicts)
    Route::post('/sites/store', [EmployeeController::class, 'createSite'])->name('master.sites.store');
    Route::post('/departments/store', [EmployeeController::class, 'createDepartment'])->name('master.departments.store');
    Route::post('/designations/store', [EmployeeController::class, 'createDesignation'])->name('master.designations.store');
    Route::post('/gangs/store', [EmployeeController::class, 'createGang'])->name('master.gangs.store');
    
    // Sites routes
    Route::get('/sites', [SiteController::class, 'index'])->name('master.sites');
    Route::match(['get', 'post'], '/sites/list', [SiteController::class, 'list'])->name('master.sites.list');
    Route::post('/sites', [SiteController::class, 'store'])->name('master.sites.store.main');
    Route::get('/sites/show/{id}', [SiteController::class, 'show'])->name('master.sites.show');
    Route::put('/sites/{id}', [SiteController::class, 'update'])->name('master.sites.update');
    Route::delete('/sites/{id}', [SiteController::class, 'destroy'])->name('master.sites.destroy');
    
    // Departments routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('master.departments');
    Route::match(['get', 'post'], '/departments/list', [DepartmentController::class, 'list'])->name('master.departments.list');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('master.departments.store.main');
    Route::get('/departments/show/{id}', [DepartmentController::class, 'show'])->name('master.departments.show');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('master.departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('master.departments.destroy');
    
    // Designations routes
    Route::get('/designations', [DesignationController::class, 'index'])->name('master.designations');
    Route::match(['get', 'post'], '/designations/list', [DesignationController::class, 'list'])->name('master.designations.list');
    Route::post('/designations', [DesignationController::class, 'store'])->name('master.designations.store.main');
    Route::get('/designations/show/{id}', [DesignationController::class, 'show'])->name('master.designations.show');
    Route::put('/designations/{id}', [DesignationController::class, 'update'])->name('master.designations.update');
    Route::delete('/designations/{id}', [DesignationController::class, 'destroy'])->name('master.designations.destroy');
    
    // Gangs routes
    Route::get('/gangs', [GangController::class, 'index'])->name('master.gangs');
    Route::match(['get', 'post'], '/gangs/list', [GangController::class, 'list'])->name('master.gangs.list');
    Route::post('/gangs', [GangController::class, 'store'])->name('master.gangs.store.main');
    Route::get('/gangs/show/{id}', [GangController::class, 'show'])->name('master.gangs.show');
    Route::put('/gangs/{id}', [GangController::class, 'update'])->name('master.gangs.update');
    Route::delete('/gangs/{id}', [GangController::class, 'destroy'])->name('master.gangs.destroy');
    
    // Employees routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('master.employees');
    Route::match(['get', 'post'], '/employees/list', [EmployeeController::class, 'list'])->name('master.employees.list');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('master.employees.store');
    Route::get('/employees/show/{id}', [EmployeeController::class, 'show'])->name('master.employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('master.employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('master.employees.destroy');
});

// Transaction Routes
Route::prefix('transaction')->group(function () {
    Route::get('/payroll', function () {
        return view('pages.transaction.payroll', ['page_title' => 'Payroll']);
    })->name('transaction.payroll');
});

// Reports Routes
Route::prefix('reports')->group(function () {
    Route::get('/employee', function () {
        return view('pages.reports.employee', ['page_title' => 'Employee Reports']);
    })->name('reports.employee');
});

// Settings Routes
Route::get('/settings', function () {
    return view('pages.settings', ['page_title' => 'Settings']);
})->name('settings');

// Profile Routes
Route::get('/profile/edit', function () {
    return view('pages.profile.edit', ['page_title' => 'Edit Profile']);
})->name('profile.edit');

// Logout Route
Route::get('/logout', function () {
    return redirect('/');
})->name('logout');
