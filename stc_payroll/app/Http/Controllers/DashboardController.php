<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Site;
use App\Department;
use App\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Traits\HasCompanyFilter;

class DashboardController extends Controller
{
    use HasCompanyFilter;
    public function index(Request $request)
    {
        // Filter by selected company
        $companyId = $this->getSelectedCompanyId();
        
        $employeesQuery = Employee::query();
        $sitesQuery = Site::query();
        $departmentsQuery = Department::query();
        
        if ($companyId) {
            $employeesQuery->where('company_id', $companyId);
            $sitesQuery->where('company_id', $companyId);
            $departmentsQuery->where('company_id', $companyId);
        }
        
        $total_employees = $employeesQuery->count();
        $active_employees = $employeesQuery->where('Status', 'ACTIVE')->count();
        $total_sites = $sitesQuery->count();
        $total_departments = $departmentsQuery->count();
        
        // Get selected month and site from request
        $selectedMonth = $request->input('month', date('Y-m'));
        $selectedSite = $request->input('site_id', 'all');
        
        // Get all sites for dropdown (filtered by company)
        $sites = $sitesQuery->orderBy('name')->get();
        
        // Get attendance data grouped by month for the last 12 months from selected month
        $attendanceData = $this->getMonthlyAttendanceData($selectedMonth, $selectedSite);
        
        return view('pages.dashboard', [
            'page_title' => 'Dashboard',
            'total_employees' => $total_employees,
            'active_employees' => $active_employees,
            'total_sites' => $total_sites,
            'total_departments' => $total_departments,
            'attendance_data' => $attendanceData,
            'selected_month' => $selectedMonth,
            'selected_site' => $selectedSite,
            'sites' => $sites
        ]);
    }
    
    public function getAttendanceData(Request $request)
    {
        $selectedMonth = $request->input('month', date('Y-m'));
        $selectedSite = $request->input('site_id', 'all');
        $attendanceData = $this->getMonthlyAttendanceData($selectedMonth, $selectedSite);
        
        return response()->json([
            'success' => true,
            'data' => $attendanceData
        ]);
    }
    
    private function getMonthlyAttendanceData($selectedMonth = null, $selectedSite = 'all')
    {
        // If selectedMonth is provided, use it as the end month, otherwise use current month
        if ($selectedMonth) {
            $endDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        } else {
            $endDate = Carbon::now();
        }
        
        // Get last 12 months from selected month
        $months = [];
        $attendanceByMonth = [];
        $workingDaysByMonth = [];
        $attendancePercentByMonth = [];
        $workingDaysInMonthByMonth = [];
        $employeeCountByMonth = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = $endDate->copy()->subMonths($i);
            $monthYear = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            $months[] = $monthName;
            
            // Filter by selected company
            $companyId = $this->getSelectedCompanyId();
            
            // Get attendance records for this month, optionally filtered by site
            if ($selectedSite && $selectedSite !== 'all') {
                // Filter by site - join with employees table
                $employeeQuery = Employee::where('site_id', $selectedSite);
                if ($companyId) {
                    $employeeQuery->where('company_id', $companyId);
                }
                $aadhars = $employeeQuery->pluck('Aadhar')->toArray();
                $attendances = Attendance::where('month_year', $monthYear)
                    ->whereIn('aadhar', $aadhars)
                    ->get();
            } else {
                // Get all attendance records for selected company
                if ($companyId) {
                    $aadhars = Employee::where('company_id', $companyId)->pluck('Aadhar')->toArray();
                    $attendances = Attendance::where('month_year', $monthYear)
                        ->whereIn('aadhar', $aadhars)
                        ->get();
                } else {
                    $attendances = Attendance::where('month_year', $monthYear)->get();
                }
            }
            
            $totalPresent = 0;
            $daysInMonth = $date->daysInMonth;
            
            // Count Sundays in the month
            $sundays = 0;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                if ($checkDate->dayOfWeek === Carbon::SUNDAY) {
                    $sundays++;
                }
            }
            
            // Working days = total days - Sundays
            $workingDaysInMonth = $daysInMonth - $sundays;
            
            // Get unique employees (by aadhar) to avoid counting duplicates
            $uniqueEmployees = $attendances->pluck('aadhar')->unique()->count();
            
            // Count present days (P) for all employees
            foreach ($attendances as $attendance) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dayField = 'day_' . $day;
                    if ($attendance->$dayField === 'P') {
                        $totalPresent++;
                    }
                }
            }
            
            // Total working days = working days in month × number of unique employees
            // Example: 27 working days × 4 employees = 108 total working days
            $totalWorkingDays = $workingDaysInMonth * $uniqueEmployees;
            
            $attendanceByMonth[] = $totalPresent;
            $workingDaysByMonth[] = $totalWorkingDays;
            
            // Store working days in month and employee count for labels
            $workingDaysInMonthByMonth[] = $workingDaysInMonth;
            $employeeCountByMonth[] = $uniqueEmployees;
            
            // Calculate percentage
            $percentage = $totalWorkingDays > 0 ? round(($totalPresent / $totalWorkingDays) * 100, 2) : 0;
            $attendancePercentByMonth[] = $percentage;
        }
        
        // Calculate overall totals for donut chart
        $totalAttendance = array_sum($attendanceByMonth);
        $totalWorkingDays = array_sum($workingDaysByMonth);
        $totalAbsent = $totalWorkingDays - $totalAttendance;
        
        return [
            'months' => $months,
            'attendance' => $attendanceByMonth,
            'working_days' => $workingDaysByMonth,
            'attendance_percent' => $attendancePercentByMonth,
            'working_days_in_month' => $workingDaysInMonthByMonth,
            'employee_count' => $employeeCountByMonth,
            'total_attendance' => $totalAttendance,
            'total_working_days' => $totalWorkingDays,
            'total_absent' => $totalAbsent
        ];
    }
}
