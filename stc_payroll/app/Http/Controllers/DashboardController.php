<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Site;
use App\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $total_employees = Employee::count();
        $active_employees = Employee::where('Status', 'ACTIVE')->count();
        $total_sites = Site::count();
        $total_departments = Department::count();
        
        return view('pages.dashboard', [
            'page_title' => 'Dashboard',
            'total_employees' => $total_employees,
            'active_employees' => $active_employees,
            'total_sites' => $total_sites,
            'total_departments' => $total_departments
        ]);
    }
}
