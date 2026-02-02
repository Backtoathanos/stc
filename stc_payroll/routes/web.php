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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Miscellaneous Report
        Route::get('/misc', function () {
            $user = auth()->user();
            if (!$user || (!$user->hasPermission('reports.misc.view') && $user->email !== 'root@stcassociate.com')) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to access this page'
                    ], 403);
                }
                return redirect(route('home'))->with('error', 'You do not have permission to access this page');
            }

            $companyId = session('selected_company_id');
            $sitesQuery = DB::table('sites')->select('id', 'name')->orderBy('name');
            if ($companyId) {
                $sitesQuery->where('company_id', $companyId);
            } else {
                // No company selected; return empty list (company selection modal should enforce selection)
                $sitesQuery->whereRaw('1 = 0');
            }
            $sites = $sitesQuery->get();
            return view('pages.reports.misc', ['page_title' => 'Miscellaneous Reports', 'sites' => $sites]);
        })->name('reports.misc');

        // Fine Register preview (HTML) - used inside iframe like other pdf previews
        Route::get('/misc/fine-preview', function (Request $request) {
            $user = auth()->user();
            if (!$user || (!$user->hasPermission('reports.misc.view') && $user->email !== 'root@stcassociate.com')) {
                return response('Forbidden', 403);
            }

            $companyId = session('selected_company_id');
            $monthYear = $request->input('month_year'); // YYYY-MM
            $siteId = $request->input('site_id', 'all');

            if (!$companyId || !$monthYear) {
                return response('Month is required', 400);
            }

            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $monthDisplay = $date->format('F Y');
            $monthShort = strtoupper($date->format('M')) . ' ' . $date->format('Y');

            $site = null;
            if ($siteId && $siteId !== 'all') {
                $site = \App\Site::find($siteId);
            }

            $company = \App\Company::find($companyId);

            $rows = DB::table('payrolls as p')
                ->leftJoin('employees as e', 'p.aadhar', '=', 'e.Aadhar')
                ->where('p.month_year', $monthYear)
                ->where('e.company_id', $companyId)
                ->when($siteId && $siteId !== 'all', function ($q) use ($siteId) {
                    $q->where('p.site_id', $siteId);
                })
                ->select(
                    'e.EmpId as empid',
                    'p.employee_name as name',
                    'e.Father as father',
                    'e.Skill as designation',
                    'p.category as category'
                )
                ->distinct()
                ->orderBy('empid')
                ->get();

            return view('pages.transaction.pdfs.fine-register', [
                'rows' => $rows,
                'monthYear' => $monthYear,
                'monthDisplay' => $monthDisplay,
                'monthShort' => $monthShort,
                'companyName' => $company->name ?? '',
                'natureOfWork' => $site && $site->natureofwork ? $site->natureofwork : '',
                'establishment' => $site ? $site->name : '',
                'principalEmployer' => $site ? $site->name : '',
                'site' => $site,
                'company' => $company
            ]);
        })->name('reports.misc.fine-preview');

        // Overtime Register preview (HTML) - Odissa Form-XIX (landscape)
        Route::get('/misc/overtime-preview', function (Request $request) {
            $user = auth()->user();
            if (!$user || (!$user->hasPermission('reports.misc.view') && $user->email !== 'root@stcassociate.com')) {
                return response('Forbidden', 403);
            }

            $companyId = session('selected_company_id');
            $monthYear = $request->input('month_year'); // YYYY-MM
            $siteId = $request->input('site_id', 'all');

            if (!$companyId || !$monthYear) {
                return response('Month is required', 400);
            }

            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $monthDisplay = $date->format('F Y');
            $monthShort = strtoupper($date->format('M')) . ' ' . $date->format('Y');

            $site = null;
            if ($siteId && $siteId !== 'all') {
                $site = \App\Site::find($siteId);
            }
            $company = \App\Company::find($companyId);

            // Base employee list from payroll for the month (so counts match payroll)
            $rows = DB::table('payrolls as p')
                ->leftJoin('employees as e', 'p.aadhar', '=', 'e.Aadhar')
                ->where('p.month_year', $monthYear)
                ->where('e.company_id', $companyId)
                ->when($siteId && $siteId !== 'all', function ($q) use ($siteId) {
                    $q->where('p.site_id', $siteId);
                })
                ->select(
                    'p.aadhar as aadhar',
                    'e.EmpId as empid',
                    'p.employee_name as name',
                    'e.Gender as gender',
                    'e.Skill as designation',
                    'p.ot_amount as ot_amount'
                )
                ->distinct()
                ->orderBy('empid')
                ->get();

            $aadhars = $rows->pluck('aadhar')->filter()->unique()->values();
            $overtimesByAadhar = DB::table('overtimes')
                ->where('month_year', $monthYear)
                ->whereIn('aadhar', $aadhars)
                ->get()
                ->keyBy('aadhar');

            // Enrich rows with OT dates + total hours
            $rows = $rows->map(function ($r) use ($overtimesByAadhar) {
                $ot = $r->aadhar ? ($overtimesByAadhar->get($r->aadhar) ?? null) : null;
                $dates = [];
                $hoursTotal = 0;
                if ($ot) {
                    for ($d = 1; $d <= 31; $d++) {
                        $h = (int)($ot->{'day_' . $d} ?? 0);
                        if ($h > 0) {
                            $dates[] = str_pad((string)$d, 2, '0', STR_PAD_LEFT);
                            $hoursTotal += $h;
                        }
                    }
                }
                $r->ot_dates = $dates;      // array of "01", "02", ...
                $r->ot_hours_total = $hoursTotal; // integer
                return $r;
            });

            return view('pages.transaction.pdfs.overtime-register', [
                'rows' => $rows,
                'monthYear' => $monthYear,
                'monthDisplay' => $monthDisplay,
                'monthShort' => $monthShort,
                'site' => $site,
                'company' => $company
            ]);
        })->name('reports.misc.overtime-preview');

        // Employment Card preview (HTML) - FOR X (portrait, 2 cards per page)
        Route::get('/misc/employment-card-preview', function (Request $request) {
            $user = auth()->user();
            if (!$user || (!$user->hasPermission('reports.misc.view') && $user->email !== 'root@stcassociate.com')) {
                return response('Forbidden', 403);
            }

            $companyId = session('selected_company_id');
            $monthYear = $request->input('month_year'); // YYYY-MM
            $siteId = $request->input('site_id', 'all');

            if (!$companyId || !$monthYear) {
                return response('Month is required', 400);
            }

            $site = null;
            if ($siteId && $siteId !== 'all') {
                $site = \App\Site::find($siteId);
            }
            $company = \App\Company::find($companyId);

            $rows = DB::table('payrolls as p')
                ->leftJoin('employees as e', 'p.aadhar', '=', 'e.Aadhar')
                ->where('p.month_year', $monthYear)
                ->where('e.company_id', $companyId)
                ->when($siteId && $siteId !== 'all', function ($q) use ($siteId) {
                    $q->where('p.site_id', $siteId);
                })
                ->select(
                    'e.EmpId as empid',
                    'p.employee_name as name',
                    'e.Father as father',
                    'e.Doj as doj',
                    'e.Doe as doe',
                    'e.Skill as designation',
                    'p.category as category',
                    'p.basic_rate as basic_rate',
                    'p.da_rate as da_rate'
                )
                ->distinct()
                ->orderBy('empid')
                ->get();

            return view('pages.transaction.pdfs.employment-card', [
                'rows' => $rows,
                'monthYear' => $monthYear,
                'site' => $site,
                'company' => $company
            ]);
        })->name('reports.misc.employment-card-preview');

        Route::post('/misc/list', function (Request $request) {
            $site = $request->input('site_id');
            $companyId = session('selected_company_id');
            $monthYear = $request->input('date_range') ?: $request->input('month_year'); // YYYY-MM

            if (!$companyId || !$monthYear) {
                return response()->json(['data' => []]);
            }

            // Pull employees from payroll for the selected month (so counts match payroll)
            $query = DB::table('payrolls as p')
                ->leftJoin('employees as e', 'p.aadhar', '=', 'e.Aadhar')
                ->leftJoin('sites as s', 'p.site_id', '=', 's.id')
                ->where('p.month_year', $monthYear)
                ->where('e.company_id', $companyId)
                ->select(
                    'e.id',
                    'e.EmpId as empid',
                    'p.employee_name as name',
                    's.name as site'
                )
                ->distinct();

            if ($site && $site !== 'all') {
                $query->where('p.site_id', $site);
            }

            $data = $query->orderBy('empid')->get();
            return response()->json(['data' => $data]);
        })->name('reports.misc.list');
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
