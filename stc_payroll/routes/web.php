<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\GangController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySelectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollParameterController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\PayrollController;

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

// Authentication Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Require Authentication)
Route::middleware(['auth.user'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard/attendance-data', [DashboardController::class, 'getAttendanceData'])->name('dashboard.attendance-data');

    // Company Selection Routes
    Route::prefix('company')->group(function () {
        Route::post('/set-company', [CompanySelectionController::class, 'setCompany'])->name('company.set');
        Route::get('/get-selected-company', [CompanySelectionController::class, 'getSelectedCompany'])->name('company.get-selected');
        Route::post('/clear-company', [CompanySelectionController::class, 'clearCompany'])->name('company.clear');
        Route::get('/get-user-companies', [CompanyController::class, 'getUserCompanies'])->name('company.get-user-companies');
    });

    // Master Routes
    Route::prefix('master')->group(function () {
        // Companies routes
        Route::get('/companies', [CompanyController::class, 'index'])->name('master.companies');
        Route::match(['get', 'post'], '/companies/list', [CompanyController::class, 'list'])->name('master.companies.list');
        Route::post('/companies', [CompanyController::class, 'store'])->name('master.companies.store');
        Route::get('/companies/show/{id}', [CompanyController::class, 'show'])->name('master.companies.show');
        Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('master.companies.update');
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('master.companies.destroy');
        Route::get('/companies/all', [CompanyController::class, 'getAll'])->name('master.companies.all');
        
        // Quick create endpoints for use in employee form dropdowns (must be before other routes to avoid conflicts)
        Route::post('/sites/store', [SiteController::class, 'store'])->name('master.sites.store');
        Route::post('/departments/store', [DepartmentController::class, 'store'])->name('master.departments.store');
        Route::post('/designations/store', [DesignationController::class, 'store'])->name('master.designations.store');
        Route::post('/gangs/store', [GangController::class, 'store'])->name('master.gangs.store');
        
        // Sites routes
        Route::get('/sites', [SiteController::class, 'index'])->name('master.sites');
        Route::match(['get', 'post'], '/sites/list', [SiteController::class, 'list'])->name('master.sites.list');
        Route::post('/sites', [SiteController::class, 'store'])->name('master.sites.store.main');
        Route::get('/sites/show/{id}', [SiteController::class, 'show'])->name('master.sites.show');
        Route::put('/sites/{id}', [SiteController::class, 'update'])->name('master.sites.update');
        Route::delete('/sites/{id}', [SiteController::class, 'destroy'])->name('master.sites.destroy');
        Route::get('/sites/under-contracts', [SiteController::class, 'getUnderContracts'])->name('master.sites.under-contracts');
        Route::get('/sites/nature-of-work', [SiteController::class, 'getNatureOfWork'])->name('master.sites.nature-of-work');
        Route::get('/sites/work-order-no', [SiteController::class, 'getWorkOrderNo'])->name('master.sites.work-order-no');
        Route::post('/sites/update-contractor-details', [SiteController::class, 'updateContractorDetails'])->name('master.sites.update-contractor-details');
        
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
        Route::post('/employees/export', [EmployeeController::class, 'export'])->name('master.employees.export');
        Route::get('/employees/export-sample', [EmployeeController::class, 'exportSample'])->name('master.employees.export-sample');
        Route::post('/employees/import-preview', [EmployeeController::class, 'importPreview'])->name('master.employees.import-preview');
        Route::post('/employees/import', [EmployeeController::class, 'import'])->name('master.employees.import');
        Route::post('/employees/import-rate-preview', [EmployeeController::class, 'importRatePreview'])->name('master.employees.import-rate-preview');
        Route::post('/employees/import-rate', [EmployeeController::class, 'importRate'])->name('master.employees.import-rate');
        Route::post('/employees/reset-leave-balance', [EmployeeController::class, 'resetLeaveBalance'])->name('master.employees.reset-leave-balance');
        Route::get('/employees/show/{id}', [EmployeeController::class, 'show'])->name('master.employees.show');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('master.employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('master.employees.destroy');
    });

    // Transaction Routes
    Route::prefix('transaction')->group(function () {
        
        // Attendance routes
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('transaction.attendance');
        Route::match(['get', 'post'], '/attendance/list', [AttendanceController::class, 'list'])->name('transaction.attendance.list');
        Route::get('/attendance/view-details', [AttendanceController::class, 'viewDetails'])->name('transaction.attendance.view-details');
        Route::get('/attendance/export-sample', [AttendanceController::class, 'exportSample'])->name('transaction.attendance.export-sample');
        Route::post('/attendance/import-preview', [AttendanceController::class, 'importPreview'])->name('transaction.attendance.import-preview');
        Route::post('/attendance/import', [AttendanceController::class, 'import'])->name('transaction.attendance.import');
            Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('transaction.attendance.destroy');
            Route::post('/attendance/process', [AttendanceController::class, 'processAttendance'])->name('transaction.attendance.process');
    });

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/employee', function () {
            $user = auth()->user();
            
            // Check if user has view permission (root user always has access)
            if (!$user || (!$user->hasPermission('reports.employee.view') && $user->email !== 'root@stcassociate.com')) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to access this page'
                    ], 403);
                }
                return redirect(route('home'))->with('error', 'You do not have permission to access this page');
            }
            
            return view('pages.reports.employee', ['page_title' => 'Employee Reports']);
        })->name('reports.employee');
        
        // Payroll Routes
        Route::get('/payroll', [PayrollController::class, 'index'])->name('reports.payroll');
        Route::post('/payroll/list', [PayrollController::class, 'list'])->name('reports.payroll.list');
        Route::post('/payroll/summary', [PayrollController::class, 'summary'])->name('reports.payroll.summary');
        Route::post('/payroll/slip', [PayrollController::class, 'slip'])->name('reports.payroll.slip');
        Route::post('/payroll/bank', [PayrollController::class, 'bank'])->name('reports.payroll.bank');
        Route::post('/payroll/bank-other', [PayrollController::class, 'bankOther'])->name('reports.payroll.bank-other');
        Route::post('/payroll/pf', [PayrollController::class, 'pf'])->name('reports.payroll.pf');
        Route::post('/payroll/esic', [PayrollController::class, 'esic'])->name('reports.payroll.esic');
        Route::get('/payroll/wage-summary-preview', [PayrollController::class, 'wageSummaryPreview'])->name('reports.payroll.wage-summary-preview');
        Route::get('/payroll/attendance-preview', [PayrollController::class, 'attendancePreview'])->name('reports.payroll.attendance-preview');
        Route::get('/payroll/attendance-odissa-preview', [PayrollController::class, 'attendanceOdissaPreview'])->name('reports.payroll.attendance-odissa-preview');
        Route::get('/payroll/wage-summary-pdf', [PayrollController::class, 'wageSummaryPdf'])->name('reports.payroll.wage-summary-pdf');
        Route::get('/payroll/attendance-pdf', [PayrollController::class, 'attendancePdf'])->name('reports.payroll.attendance-pdf');
        Route::get('/payroll/attendance-odissa-pdf', [PayrollController::class, 'attendanceOdissaPdf'])->name('reports.payroll.attendance-odissa-pdf');
        Route::get('/payroll/wage-slip-preview', [PayrollController::class, 'wageSlipPreview'])->name('reports.payroll.wage-slip-preview');
        Route::get('/payroll/all-wage-slips-preview', [PayrollController::class, 'allWageSlipsPreview'])->name('reports.payroll.all-wage-slips-preview');
    });

    // Calendar Routes
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar');
        Route::get('/leaves', [CalendarController::class, 'getLeaves'])->name('calendar.leaves');
        Route::get('/leaves/{date}', [CalendarController::class, 'getDateLeaves'])->name('calendar.date-leaves');
        Route::post('/leaves', [CalendarController::class, 'store'])->name('calendar.store');
        Route::delete('/leaves/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    // Settings Routes
    Route::get('/settings', function () {
        return view('pages.settings', ['page_title' => 'Settings']);
    })->name('settings');
    
    Route::prefix('settings')->group(function () {
        Route::get('/payroll-parameter', [PayrollParameterController::class, 'index'])->name('settings.payroll-parameter');
        Route::post('/payroll-parameter', [PayrollParameterController::class, 'store'])->name('settings.payroll-parameter.store');
        
        Route::get('/rate', [RateController::class, 'index'])->name('settings.rate');
        Route::post('/rate/list', [RateController::class, 'list'])->name('settings.rate.list');
        Route::post('/rate', [RateController::class, 'store'])->name('settings.rate.store');
        Route::get('/rate/{id}', [RateController::class, 'show'])->name('settings.rate.show');
        Route::put('/rate/{id}', [RateController::class, 'update'])->name('settings.rate.update');
        Route::delete('/rate/{id}', [RateController::class, 'destroy'])->name('settings.rate.destroy');
        Route::post('/rate/{id}/process', [RateController::class, 'process'])->name('settings.rate.process');
    });

    // Profile Routes
    Route::get('/profile/edit', function () {
        return view('pages.profile.edit', ['page_title' => 'Edit Profile']);
    })->name('profile.edit');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::match(['get', 'post'], '/users/list', [AdminController::class, 'list'])->name('admin.users.list');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/users/show/{id}', [AdminController::class, 'show'])->name('admin.users.show');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::get('/users/{id}/permissions', [AdminController::class, 'getUserPermissions'])->name('admin.users.permissions');
        Route::post('/users/{id}/permissions', [AdminController::class, 'savePermissions'])->name('admin.users.save-permissions');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });
});
