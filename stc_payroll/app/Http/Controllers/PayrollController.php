<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payroll;
use App\Employee;
use App\Site;
use App\Rate;
use App\CalendarLeaveType;
use App\Attendance;
use App\PayrollParameter;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use App\Http\Controllers\Traits\HasCompanyFilter;

class PayrollController extends Controller
{
    use HasCompanyFilter;
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || ((!$user->hasPermission('transaction.payroll.view') && !$user->hasPermission('reports.payroll.view')) && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect(route('home'))->with('error', 'You do not have permission to access this page');
        }
        
        // Filter sites by selected company
        $companyId = $this->getSelectedCompanyId();
        $sitesQuery = Site::query();
        if ($companyId) {
            $sitesQuery->where('company_id', $companyId);
        }
        
        return view('pages.transaction.payroll', [
            'page_title' => 'Payroll',
            'sites' => $sitesQuery->orderBy('name')->get()
        ]);
    }

    public function list(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id');
        
        if (!$monthYear) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
        
        // Get payroll parameters for selected company
        $companyId = $this->getSelectedCompanyId();
        $params = null;
        if ($companyId) {
            $params = PayrollParameter::where('company_id', $companyId)->first();
        }
        
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear);
        
        // Filter by selected company
        $companyId = $this->getSelectedCompanyId();
        if ($companyId) {
            $query->where('employees.company_id', $companyId);
        }
        
        // Filter by site if provided
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('employees.EmpId', 'like', "%{$search}%")
                  ->orWhere('payrolls.employee_name', 'like', "%{$search}%")
                  ->orWhere('sites.name', 'like', "%{$search}%");
            });
        }
        
        // Get total count before pagination (with company filter)
        $totalRecordsQuery = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->where('payrolls.month_year', $monthYear);
        
        if ($companyId) {
            $totalRecordsQuery->where('employees.company_id', $companyId);
        }
        
        if ($siteId && $siteId !== 'all') {
            $totalRecordsQuery->where('payrolls.site_id', $siteId);
        }
        
        $totalRecords = $totalRecordsQuery->count();
        
        // Ordering
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'asc';
        $columns = ['employees.EmpId', 'payrolls.employee_name', 'sites.name'];
        $orderBy = $columns[$orderColumn] ?? 'employees.EmpId';
        $query->orderBy($orderBy, $orderDir);
        
        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $filteredRecords = $query->count();
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'employees.leave_balance',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )
        ->skip($start)
        ->take($length)
        ->get();
        
        // Get NH and FL days for the month from calendar
        $holidayDays = [];
        $holidayRecords = collect([]);
        $daysInMonth = 0;
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
            $holidayDays = $holidayRecords->pluck('date')
                ->map(function($date) {
                    return $date->format('Y-m-d');
                })
                ->toArray();
            
            // Calculate days in month once
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
        }
        
        $data = [];
        $serial = $start + 1;
        $totals = [
            'basic'           => 0,
            'da'              => 0,
            'hra'             => 0,
            'other_cash'      => 0,
            'ot_amt'          => 0,
            'other_allowance' => 0,
            'gross'           => 0,
            'pf'              => 0,
            'esic'            => 0,
            'prf_tax'         => 0,
            'advance'         => 0,
            'deduction'       => 0,
            'net_amt'         => 0,
        ];
        
        foreach ($payrolls as $payroll) {
            // Get present days from attendance table (count 'P' days)
            $present = 0;
            
            // Get NH and FL days from calendar table (reuse already fetched holidayRecords)
            $nh = 0;
            $fl = 0;
            $cl = 0; // CL stored as 'C'
            $pl = 0; // PL/EL stored as 'E'
            $attendance = Attendance::where('aadhar', $payroll->aadhar)
                ->where('month_year', $monthYear)
                ->first();
            
            if ($attendance) {
                // Count present days (only for working days, exclude Sundays)
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                    // Skip Sundays - they are not working days
                    if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                        continue;
                    }
                    
                    $dayValue = $attendance->{'day_' . $day};
                    if ($dayValue === 'P') {
                        $present++;
                    }elseif ($dayValue === 'F') {
                        $fl++;
                    }elseif ($dayValue === 'N') {
                        $nh++;
                    }elseif ($dayValue === 'E') {
                        $pl++;
                    }elseif ($dayValue === 'C') {
                        $cl++;
                    }
                }
            }

            // Leave as per attendance sheet
            $leaveFromAttendance = $cl + $pl;
            
            // Count NH and FL days (only for working days, exclude Sundays)
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                // Skip Sundays - holidays on Sundays don't count as worked days
                if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                    continue;
                }
                
                $checkDateStr = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                foreach ($holidayRecords as $holidayRecord) {
                    if ($holidayRecord->date->format('Y-m-d') === $checkDateStr) {
                        if ($holidayRecord->leave_type === 'NH') {
                            $nh++;
                        } elseif ($holidayRecord->leave_type === 'FL') {
                            $fl++;
                        }
                        break;
                    }
                }
            }
            
            // Get leave days used to cover absent days from payroll table
            // This shows how many leave days were used from leave balance to cover absent days
            // Example: If employee has 5 leave balance, 1 absent day, then 1 leave is used to cover it
            // So l = 1 (leave used), not 5 (total leave balance)
            // $l = $payroll->leave_balance ?? 0;
            $leaveBalance = (int) ($payroll->leave_balance ?? 0);
            $absentDays   = (int) ($payroll->absent_days ?? 0);

            // Leave used to cover absences
            $leaveUsedToCoverAbsence = min($leaveBalance, $absentDays);

            // Total leave to show = leave marked in attendance + leave used to cover absences
            $l = $leaveFromAttendance + $leaveUsedToCoverAbsence;
            
            // Total worked days = Present + NH + FL + Leave (leave used to cover absent)
            $totalWorked = $present + $nh + $fl + $l;
            
            // Rate breakdown
            $basicRate = $payroll->basic_rate ?? 0;
            $daRate = $payroll->da_rate ?? 0;
            $totalRate = $basicRate + $daRate;
            
            // Other cash (sum of various allowances from rates table)
            // Based on the correct data, Other Cash = 4.2, which seems to be a small allowance
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            
            // Other allowance (Special Allowance from rates table - separate from Other Cash)
            $otherAllowance = ($payroll->SpecialAllowance / 26) * $totalWorked;
            
            // HRA
            $hra = $payroll->hra ?? 0;
            
            // OT
            $otHours = $payroll->ot_hours ?? 0;
            $otAmount = $payroll->ot_amount ?? 0;
            
            // Gross should include: basic + da + hra + other cash + ot
            // But during processing, gross was calculated as: basic + da + ot
            // So we need to recalculate gross to include HRA and other cash
            $gross = ($payroll->basic_amount ?? 0) + 
                    ($payroll->da_amount ?? 0) + 
                    $hra + 
                    $otherCash + 
                    $otAmount;
            
            // Deductions - use stored values from payroll table (already calculated correctly during processing)
            // PF is calculated on basic amount (min of basic and pf_limit) × pf_percentage
            $pf = $payroll->pf_employee ?? 0;
            
            // ESIC is calculated on gross salary during processing - use stored value
            // The stored ESIC is already correct, no need to recalculate
            $esic = $payroll->esic_employee ?? 0;
            
            // PRF Tax - calculate based on gross salary ranges
            $isPRFTaxApplicable = !empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1');
            $prfTax = $this->calculatePRFTax($gross + $otherAllowance, $isPRFTaxApplicable);
            
            // Advance and Deduction - these might be in rates table or need to be added
            // For now, set to 0 if not available
            $advance = 0; // Can be added to rates table later
            $deduction = $payroll->IncomeTax ?? 0; // Using IncomeTax as deduction for now
            
            // Total Deduction = PF + ESIC + PRF Tax + Advance + Other Deductions
            $totalDeduction = $pf + $esic + $prfTax + $advance + $deduction;
            
            $gross += $otherAllowance;
            // Net amount = Gross - Total Deduction
            $netAmt = $gross - $totalDeduction;
            $totals['basic']           += ($payroll->basic_amount ?? 0);
            $totals['da']              += ($payroll->da_amount ?? 0);
            $totals['hra']             += $hra;
            $totals['other_cash']      += $otherCash;
            $totals['ot_amt']          += $otAmount;
            $totals['other_allowance'] += $otherAllowance;
            $totals['gross']           += $gross;
            $totals['pf']              += $pf;
            $totals['esic']            += $esic;
            $totals['prf_tax']         += $prfTax;
            $totals['advance']         += $advance;
            $totals['deduction']       += $totalDeduction;
            $totals['net_amt']         += $netAmt;

            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'name' => $payroll->employee_name ?? 'N/A',
                'site' => $payroll->site_name ?? 'N/A',
                'worked' => [
                    'present' => $present,
                    'nh' => $nh,
                    'fl' => $fl,
                    'l' => $l,
                    'total' => $totalWorked
                ],
                'rate' => [
                    'basic' => number_format(round($basicRate, 0), 2),
                    'da' => number_format(round($daRate, 0), 2),
                    'total' => number_format(round($totalRate, 0, 2))
                ],
                'basic' => number_format(round($payroll->basic_amount ?? 0, 0), 2),
                'da' => number_format(round($payroll->da_amount ?? 0, 0), 2),
                'hra' => number_format(round($hra, 0), 2),
                'other_cash' => number_format(round($otherCash, 0), 2),
                'ot_hrs' => number_format(round($otHours, 0), 2),
                'ot_amt' => number_format(round($otAmount, 0), 2),
                'other_allowance' => number_format(round($otherAllowance, 0), 2),
                'gross' => number_format(round($gross, 0), 2),
                'pf' => number_format(round($pf, 0), 2),
                'esic' => number_format(round($esic, 0), 2),
                'prf_tax' => number_format(round($prfTax, 0), 2),
                'advance' => number_format(round($advance, 0), 2),
                'deduction' => number_format(round($totalDeduction, 0), 2),
                'net_amt' => number_format(round($netAmt, 0), 2)
            ];
        }
        
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
            'totals' => [
                'basic'           => round($totals['basic'], 2),
                'da'              => round($totals['da'], 2),
                'hra'             => round($totals['hra'], 2),
                'other_cash'      => round($totals['other_cash'], 2),
                'ot_amt'          => round($totals['ot_amt'], 2),
                'other_allowance' => round($totals['other_allowance'], 2),
                'gross'           => round($totals['gross'], 2),
                'pf'              => round($totals['pf'], 2),
                'esic'            => round($totals['esic'], 2),
                'prf_tax'         => round($totals['prf_tax'], 2),
                'advance'         => round($totals['advance'], 2),
                'deduction'       => round($totals['deduction'], 2),
                'net_amt'         => round($totals['net_amt'], 2),
            ]
        ]);
    }
    
    public function summary(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Build query similar to list method
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayDays = [];
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
            $holidayDays = $holidayRecords->pluck('date')
                ->map(function($date) {
                    return $date->format('Y-m-d');
                })
                ->toArray();
        }
        
        // Initialize totals
        $totals = [
            'total_employees' => 0,
            'total_present' => 0,
            'total_nh' => 0,
            'total_fl' => 0,
            'total_leave' => 0,
            'total_worked' => 0,
            'total_basic' => 0,
            'total_da' => 0,
            'total_hra' => 0,
            'total_other_cash' => 0,
            'total_ot_hours' => 0,
            'total_ot_amount' => 0,
            'total_other_allowance' => 0,
            'total_gross' => 0,
            'total_pf' => 0,
            'total_esic' => 0,
            'total_prf_tax' => 0,
            'total_advance' => 0,
            'total_deduction' => 0,
            'total_net' => 0
        ];
        
        foreach ($payrolls as $payroll) {
            $totals['total_employees']++;
            
            // Use stored values from payroll table (already processed and validated correctly)
            $present = $payroll->present_days ?? 0;
            $nh = $payroll->nh_days ?? 0;
            $fl = $payroll->fl_days ?? 0;
            $l = $payroll->leave_days ?? 0; // Use stored leave_days (already validated during processing)
            
            // Total worked days = Present + NH + FL + Leave (all from stored values)
            $totalWorked = $present + $nh + $fl + $l;
            
            // Calculate values same as list method
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            
            $otherAllowance = $payroll->SpecialAllowance ?? 0;
            $hra = $payroll->hra ?? 0;
            $otHours = $payroll->ot_hours ?? 0;
            $otAmount = $payroll->ot_amount ?? 0;
            
            $gross = ($payroll->basic_amount ?? 0) + 
                    ($payroll->da_amount ?? 0) + 
                    $hra + 
                    $otherCash + 
                    $otAmount;
            
            $pf = $payroll->pf_employee ?? 0;
            $esic = $payroll->esic_employee ?? 0;
            
            $isPRFTaxApplicable = !empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1');
            $prfTax = $this->calculatePRFTax($gross, $isPRFTaxApplicable);
            
            $advance = 0;
            $deduction = $payroll->IncomeTax ?? 0;
            $totalDeduction = $pf + $esic + $prfTax + $advance + $deduction;
            $netAmt = $gross - $totalDeduction;
            
            // Accumulate totals
            $totals['total_present'] += $present;
            $totals['total_nh'] += $nh;
            $totals['total_fl'] += $fl;
            $totals['total_leave'] += $l;
            $totals['total_worked'] += $totalWorked;
            $totals['total_basic'] += ($payroll->basic_amount ?? 0);
            $totals['total_da'] += ($payroll->da_amount ?? 0);
            $totals['total_hra'] += $hra;
            $totals['total_other_cash'] += $otherCash;
            $totals['total_ot_hours'] += $otHours;
            $totals['total_ot_amount'] += $otAmount;
            $totals['total_other_allowance'] += $otherAllowance;
            $totals['total_gross'] += $gross;
            $totals['total_pf'] += $pf;
            $totals['total_esic'] += $esic;
            $totals['total_prf_tax'] += $prfTax;
            $totals['total_advance'] += $advance;
            $totals['total_deduction'] += $deduction;
            $totals['total_net'] += $netAmt;
        }
        
        // Return data in the same format as list method but with simplified columns
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Use stored values from payroll table (already processed and validated correctly)
            $present = $payroll->present_days ?? 0;
            $nh = $payroll->nh_days ?? 0;
            $fl = $payroll->fl_days ?? 0;
            $l = $payroll->leave_days ?? 0; // Use stored leave_days (already validated during processing)
            
            // Total worked days = Present + NH + FL + Leave (all from stored values)
            $totalWorked = $present + $nh + $fl + $l;
            
            // Calculate values
            $basicRate = $payroll->basic_rate ?? 0;
            $daRate = $payroll->da_rate ?? 0;
            $totalRate = $basicRate + $daRate;
            
            // Actual Amt = Basic + DA (based on worked days)
            $actualAmt = ($payroll->basic_amount ?? 0) + ($payroll->da_amount ?? 0);
            
            $otHours = $payroll->ot_hours ?? 0;
            $otAmount = $payroll->ot_amount ?? 0;
            
            // Allowance = Other Allowance (Special Allowance)
            $allowance = $payroll->SpecialAllowance ?? 0;
            
            // Total = Actual Amt + OT Amount + Allowance
            $total = $actualAmt + $otAmount + $allowance;
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'name' => $payroll->employee_name ?? 'N/A',
                'worked' => $totalWorked,
                'rate' => number_format($totalRate, 2),
                'actual_amt' => number_format($actualAmt, 2),
                'ot_hrs' => $otHours > 0 ? number_format($otHours, 2) : '',
                'ot_amt' => $otAmount > 0 ? number_format($otAmount, 2) : '',
                'allowance' => $allowance > 0 ? number_format($allowance, 2) : '',
                'total' => number_format($total, 2)
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function slip(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Build query similar to list method
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return data in slip format
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Use stored values from payroll table (already processed and validated correctly)
            $present = $payroll->present_days ?? 0;
            $nh = $payroll->nh_days ?? 0;
            $fl = $payroll->fl_days ?? 0;
            $l = $payroll->leave_days ?? 0; // Use stored leave_days (already validated during processing)
            
            // Total worked days = Present + NH + FL + Leave (all from stored values)
            $totalWorked = $present + $nh + $fl + $l;
            
            // Calculate values
            $basic = $payroll->basic_amount ?? 0;
            $da = $payroll->da_amount ?? 0;
            
            // Gross = Basic + DA + HRA + Other Cash + OT
            $hra = $payroll->hra ?? 0;
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            $otAmount = $payroll->ot_amount ?? 0;
            
            $gross = $basic + $da + $hra + $otherCash + $otAmount;
            
            // Deductions
            $pf = $payroll->pf_employee ?? 0;
            $esic = $payroll->esic_employee ?? 0;
            $isPRFTaxApplicable = !empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1');
            $prfTax = $this->calculatePRFTax($gross, $isPRFTaxApplicable);
            $advance = 0;
            $deduction = $payroll->IncomeTax ?? 0;
            $totalDeduction = $pf + $esic + $prfTax + $advance + $deduction;
            
            // Net Amount
            $netAmt = $gross - $totalDeduction;
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'worked' => $totalWorked,
                'basic' => number_format($basic, 2),
                'da' => $da > 0 ? number_format($da, 2) : '',
                'gross' => number_format(round($gross), 0),
                'advance' => $advance > 0 ? number_format($advance, 2) : '',
                'deduction' => number_format(round($totalDeduction), 0),
                'net_amt' => number_format(round($netAmt), 0),
                'name' => $payroll->employee_name ?? 'N/A',
                'aadhar' => $payroll->aadhar
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function bank(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Build query similar to list method
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'employees.Bank',
            'employees.Ifsc',
            'employees.Ac',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return data in bank statement format
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Get attendance record
            $attendance = Attendance::where('aadhar', $payroll->aadhar)
                ->where('month_year', $monthYear)
                ->first();
            
            $present = $payroll->present_days ?? 0;
            $nh = 0;
            $fl = 0;
            $l = 0;
            
            // Calculate days in month
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
            
            if ($attendance) {
                // Count 'O' (Off/Holiday) days - ONLY for days within the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dayValue = $attendance->{'day_' . $day};
                    if ($dayValue === 'O') {
                        $l++;
                    }
                }
            }
            
            // Count NH and FL days
            if (!empty($holidayRecords) && $monthYear) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $checkDate = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    foreach ($holidayRecords as $holidayRecord) {
                        if ($holidayRecord->date->format('Y-m-d') === $checkDate) {
                            if ($holidayRecord->leave_type === 'NH') {
                                $nh++;
                            } elseif ($holidayRecord->leave_type === 'FL') {
                                $fl++;
                            }
                            break;
                        }
                    }
                }
            }
            
            // Calculate net amount for BANK
            // Bank: Gross WITHOUT Special Allowance, but WITH PF and ESIC deductions
            $basic = $payroll->basic_amount ?? 0;
            $da = $payroll->da_amount ?? 0;
            
            // Gross = Basic + DA + HRA + Other Cash + OT (NO Special Allowance)
            $hra = $payroll->hra ?? 0;
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            $otAmount = $payroll->ot_amount ?? 0;
            
            // Gross WITHOUT Special Allowance
            $gross = $basic + $da + $hra + $otherCash + $otAmount;
            
            // Deductions: PF and ESIC are included
            $pf = $payroll->pf_employee ?? 0;
            $esic = $payroll->esic_employee ?? 0;
            $isPRFTaxApplicable = !empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1');
            $prfTax = $this->calculatePRFTax($gross, $isPRFTaxApplicable);
            $advance = 0;
            $deduction = $payroll->IncomeTax ?? 0;
            $totalDeduction = $pf + $esic + $prfTax + $advance + $deduction;
            
            // Net Amount = Gross (without Special Allowance) - Deductions (with PF and ESIC)
            $netAmt = $gross - $totalDeduction;
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'name' => $payroll->employee_name ?? 'N/A',
                'bank' => $payroll->Bank ?? '',
                'ifsc' => $payroll->Ifsc ?? '',
                'ac_no' => $payroll->Ac ?? '',
                'net_amt' => number_format(round($netAmt), 0)
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function bankOther(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Build query similar to bank method but filter for employees without bank details or with different payment mode
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear)
            ->where(function($q) {
                // Employees without bank details or with payment mode other than bank
                $q->whereNull('employees.Bank')
                  ->orWhere('employees.Bank', '')
                  ->orWhere('employees.Paymentmode', '!=', 'BANK')
                  ->orWhereNull('employees.Paymentmode');
            });
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'employees.Bank',
            'employees.Ifsc',
            'employees.Ac',
            'employees.Paymentmode',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return data in bank other statement format
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Get attendance record
            $attendance = Attendance::where('aadhar', $payroll->aadhar)
                ->where('month_year', $monthYear)
                ->first();
            
            // Calculate net amount for BANK OTHER
            // Bank Other: Gross WITH Special Allowance, WITH PF and ESIC deductions
            $basic = $payroll->basic_amount ?? 0;
            $da = $payroll->da_amount ?? 0;
            
            // Gross = Basic + DA + HRA + Other Cash + OT + Special Allowance
            $hra = $payroll->hra ?? 0;
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            $otAmount = $payroll->ot_amount ?? 0;
            $specialAllowance = $payroll->SpecialAllowance ?? 0;
            
            // Gross WITH Special Allowance
            $gross = $basic + $da + $hra + $otherCash + $otAmount + $specialAllowance;
            
            // Deductions: PF and ESIC are included
            $pf = $payroll->pf_employee ?? 0;
            $esic = $payroll->esic_employee ?? 0;
            $isPRFTaxApplicable = !empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1');
            $prfTax = $this->calculatePRFTax($gross, $isPRFTaxApplicable);
            $advance = 0;
            $deduction = $payroll->IncomeTax ?? 0;
            $totalDeduction = $pf + $esic + $prfTax + $advance + $deduction;
            
            // Net Amount = Gross (with Special Allowance) - Deductions (with PF and ESIC)
            $netAmt = $gross - $totalDeduction;
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'name' => $payroll->employee_name ?? 'N/A',
                'bank' => $payroll->Bank ?? '',
                'ifsc' => $payroll->Ifsc ?? '',
                'ac_no' => $payroll->Ac ?? '',
                'net_amt' => number_format(round($netAmt), 0)
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function pf(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Get payroll parameters for selected company
        $companyId = $this->getSelectedCompanyId();
        $params = null;
        if ($companyId) {
            $params = PayrollParameter::where('company_id', $companyId)->first();
        }
        
        // Build query similar to list method
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear)
            ->where(function($q) {
                // Only employees who are PF applicable
                $q->where('employees.PfApplicable', true)
                  ->orWhere('employees.PfApplicable', 1)
                  ->orWhere('employees.PfApplicable', '1');
            });
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'employees.Uan',
            'employees.Bank',
            'employees.Ifsc',
            'employees.Ac',
            'employees.Paymentmode',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return data in PF report format
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Get attendance record
            $attendance = Attendance::where('aadhar', $payroll->aadhar)
                ->where('month_year', $monthYear)
                ->first();
            
            $present = $payroll->present_days ?? 0;
            $absent = $payroll->absent_days ?? 0;
            
            // Calculate basic amount
            $basic = $payroll->basic_amount ?? 0;
            
            // EPF WAGES = Basic amount (capped at pf_limit if applicable)
            $epfWages = $basic;
            if ($params && $params->pf_limit > 0) {
                $epfWages = min($basic, $params->pf_limit);
            }
            
            // EPS WAGES = EPF WAGES capped at 15000 (pension scheme limit)
            $epsWages = min($epfWages, 15000);
            
            // EDLI WAGES = EPF WAGES capped at 15000 (EDLI limit)
            $edliWages = min($epfWages, 15000);
            
            // PF calculations based on screenshot data:
            // EPF AMT = 8.33% of EPS WAGES (employee's contribution to EPF, calculated on EPS WAGES)
            // PPF AMT = 3.67% of EPF WAGES (employer's EPF) + 8.33% of EPS WAGES (EPS) + 0.5% of EDLI WAGES (EDLI)
            // Total PF = EPF AMT + PPF AMT
            
            if ($params && $epfWages > 0) {
                // EPF AMT = 8.33% of EPS WAGES (employee's share)
                $epfAmt = ($epsWages * 8.33) / 100;
                
                // PPF AMT = Employer's contribution:
                // - EPF Employer: 3.67% of EPF WAGES
                // - EPS: 8.33% of EPS WAGES
                // - EDLI: 0.5% of EDLI WAGES
                $epfEmployer = ($epfWages * 3.67) / 100;
                $epsAmt = ($epsWages * 8.33) / 100;
                $edliAmt = ($edliWages * 0.5) / 100;
                $ppfAmt = $epfEmployer + $epsAmt + $edliAmt;
                
                // Total PF = EPF AMT + PPF AMT
                $pf = $epfAmt + $ppfAmt;
            } else {
                $epfAmt = 0;
                $ppfAmt = 0;
                $pf = 0;
            }
            
            // NCP DAY = Non-Contributory Period Days (Absent days)
            $ncpDay = $absent;
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'uan' => $payroll->Uan ?? '',
                'name' => $payroll->employee_name ?? 'N/A',
                'epf_wages' => number_format(round($epfWages), 0),
                'eps_wages' => number_format(round($epsWages), 0),
                'edli_wages' => number_format(round($edliWages), 0),
                'pf' => number_format(round($pf), 0),
                'epf_amt' => number_format(round($epfAmt), 0),
                'ppf_amt' => number_format(round($ppfAmt), 0),
                'ncp_day' => $ncpDay
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function esic(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ], 400);
        }
        
        // Get payroll parameters for selected company
        $companyId = $this->getSelectedCompanyId();
        $params = null;
        if ($companyId) {
            $params = PayrollParameter::where('company_id', $companyId)->first();
        }
        
        // Build query similar to list method but filter for ESIC applicable employees
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear)
            ->where(function($q) {
                // Only employees who are ESIC applicable
                $q->where('employees.EsicApplicable', true)
                  ->orWhere('employees.EsicApplicable', 1)
                  ->orWhere('employees.EsicApplicable', '1');
            });
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'employees.Esic',
            'employees.Bank',
            'employees.Ifsc',
            'employees.Ac',
            'employees.Paymentmode',
            'sites.name as site_name',
            'rates.hra',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'rates.SpecialAllowance',
            'rates.IncomeTax'
        )->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return data in ESIC report format
        $data = [];
        $serial = 1;
        
        foreach ($payrolls as $payroll) {
            // Use stored values from payroll table (already processed and validated correctly)
            $present = $payroll->present_days ?? 0;
            $nh = $payroll->nh_days ?? 0;
            $fl = $payroll->fl_days ?? 0;
            $l = $payroll->leave_days ?? 0; // Use stored leave_days (already validated during processing)
            
            // Total worked days = Present + NH + FL + Leave (all from stored values)
            $totalWorked = $present + $nh + $fl + $l;
            
            // Calculate gross salary for ESIC
            $basic = $payroll->basic_amount ?? 0;
            $da = $payroll->da_amount ?? 0;
            
            // Gross = Basic + DA + HRA + Other Cash + OT
            $hra = $payroll->hra ?? 0;
            $otherCash = ($payroll->CA ?? 0) + 
                        ($payroll->Fooding ?? 0) + 
                        ($payroll->Misc ?? 0) + 
                        ($payroll->CEA ?? 0) + 
                        ($payroll->WashingAllowance ?? 0) + 
                        ($payroll->ProfessionalPursuits ?? 0);
            $otAmount = $payroll->ot_amount ?? 0;
            
            $gross = $basic + $da + $hra + $otherCash + $otAmount;
            
            // ESIC_CONT_AMT = Gross salary (capped at esic_limit if applicable, rounded)
            $esicContAmt = $gross;
            if ($params && $params->esic_limit > 0) {
                $esicContAmt = min($gross, $params->esic_limit);
            }
            $esicContAmt = round($esicContAmt);
            
            // ESIC AMT = Use stored ESIC employee amount from payroll table (already calculated correctly)
            // If not available, calculate: ESIC_CONT_AMT × esic_employee_percentage / 100
            $esicAmt = $payroll->esic_employee ?? 0;
            if ($esicAmt == 0 && $params && $esicContAmt > 0) {
                $esicPercentage = $params->esic_employee_percentage ?? 0.75;
                $esicAmt = ($esicContAmt * $esicPercentage) / 100;
            }
            $esicAmt = round($esicAmt);
            
            $data[] = [
                'sl' => $serial++,
                'empid' => $payroll->EmpId ?? 'N/A',
                'esic' => $payroll->Esic ?? '',
                'name' => $payroll->employee_name ?? 'N/A',
                'days' => $totalWorked,
                'esic_cont_amt' => number_format(round($esicContAmt), 0),
                'esic_amt' => number_format(round($esicAmt), 0)
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function wageSummaryPdf(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get payroll data (similar to summary method)
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.Name',
            'employees.PRFTax',
            'sites.name as site_name',
            'rates.SpecialAllowance',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'designations.name as designation'
        )->orderBy('employees.EmpId')->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        $siteName = 'All Sites';
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        }
        
        // Get site for contractor/establishment info
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
        } else {
            // Get first site from payrolls if available
            $firstPayroll = $payrolls->first();
            if ($firstPayroll && $firstPayroll->site_id) {
                $site = Site::find($firstPayroll->site_id);
            }
        }
        
        // Render view template
        $html = view('pages.transaction.pdfs.wage-summary', [
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'siteName' => $siteName,
            'holidayRecords' => $holidayRecords,
            'site' => $site
        ])->render();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Force download instead of inline display
        return $dompdf->stream('wage_summary_' . $monthYear . '.pdf', ['Attachment' => 1]);
    }
    
    public function wageSummaryPreview(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get payroll data (similar to summary method)
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.Name',
            'employees.PRFTax',
            'sites.name as site_name',
            'rates.SpecialAllowance',
            'rates.CA',
            'rates.Fooding',
            'rates.Misc',
            'rates.CEA',
            'rates.WashingAllowance',
            'rates.ProfessionalPursuits',
            'designations.name as designation'
        )->orderBy('employees.EmpId')->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        $siteName = 'All Sites';
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        } else {
            // Get first site from payrolls if available
            $firstPayroll = $payrolls->first();
            if ($firstPayroll && $firstPayroll->site_id) {
                $site = Site::find($firstPayroll->site_id);
            }
        }
        
        // Return HTML view for preview (not PDF)
        return view('pages.transaction.pdfs.wage-summary', [
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'siteName' => $siteName,
            'holidayRecords' => $holidayRecords,
            'site' => $site
        ]);
    }
    
    public function attendancePreview(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get attendance data with all employee details
        $query = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('attendances.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('employees.site_id', $siteId);
        }
        
        $attendances = $query->select(
                'attendances.*', 
                'employees.EmpId', 
                'employees.Name as employee_name',
                'employees.Gender',
                'employees.Dob',
                'employees.Doj',
                'employees.Esic',
                'employees.Uan',
                'employees.PfApplicable',
                'sites.name as site_name', 
                'departments.name as department', 
                'designations.name as designation',
                'rates.*'
            )
            ->orderBy('employees.EmpId')
            ->get();
        
        // Get payroll data for wage calculations
        $aadhars = $attendances->pluck('aadhar')->unique();
        $payrolls = Payroll::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Get overtime data
        $overtimes = \App\Overtime::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Calculate days in month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        $monthDisplay = $date->format('F Y');
        
        $siteName = 'All Sites';
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        }
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Return HTML view for preview (not PDF)
        return view('pages.transaction.pdfs.attendance', [
            'attendances' => $attendances,
            'overtimes' => $overtimes,
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay,
            'siteName' => $siteName,
            'site' => $site,
            'daysInMonth' => $daysInMonth,
            'holidayRecords' => $holidayRecords
        ]);
    }
    
    public function attendancePdf(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get attendance data with all employee details
        $query = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('attendances.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('employees.site_id', $siteId);
        }
        
        $attendances = $query->select(
                'attendances.*', 
                'employees.EmpId', 
                'employees.Name as employee_name',
                'employees.Gender',
                'employees.Dob',
                'employees.Doj',
                'employees.Esic',
                'employees.Uan',
                'employees.PfApplicable',
                'sites.name as site_name', 
                'departments.name as department', 
                'designations.name as designation',
                'rates.*'
            )
            ->orderBy('employees.EmpId')
            ->get();
        
        // Get payroll data for wage calculations
        $aadhars = $attendances->pluck('aadhar')->unique();
        $payrolls = Payroll::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Get overtime data
        $overtimes = \App\Overtime::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Calculate days in month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        $monthDisplay = $date->format('F Y');
        
        $siteName = 'All Sites';
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        }
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Render view template
        $html = view('pages.transaction.pdfs.attendance', [
            'attendances' => $attendances,
            'overtimes' => $overtimes,
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay,
            'siteName' => $siteName,
            'site' => $site,
            'daysInMonth' => $daysInMonth,
            'holidayRecords' => $holidayRecords
        ])->render();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Force download instead of inline display
        return $dompdf->stream('attendance_' . $monthYear . '.pdf', ['Attachment' => 1]);
    }
    
    public function attendanceOdissaPreview(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get attendance data with all employee details
        $query = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('attendances.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('employees.site_id', $siteId);
        }
        
        $attendances = $query->select(
                'attendances.*', 
                'employees.EmpId', 
                'employees.Name as employee_name',
                'employees.Gender',
                'employees.Dob',
                'employees.Doj',
                'employees.Esic',
                'employees.Uan',
                'employees.PfApplicable',
                'employees.Skill',
                'sites.name as site_name', 
                'departments.name as department', 
                'designations.name as designation',
                'rates.*'
            )
            ->orderBy('employees.EmpId')
            ->get();
        
        // Get payroll data for wage calculations
        $aadhars = $attendances->pluck('aadhar')->unique();
        $payrolls = Payroll::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Get overtime data
        $overtimes = \App\Overtime::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Calculate days in month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        $monthDisplay = $date->format('F Y');
        
        $siteName = 'All Sites';
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        }
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Get company data from site or cookie
        $company = null;
        if ($site && $site->company_id) {
            $company = \App\Company::find($site->company_id);
        }
        if (!$company) {
            $companyId = \Cookie::get('company_id');
            $company = $companyId ? \App\Company::find($companyId) : null;
        }
        if (!$company) {
            $company = \App\Company::first();
        }
        
        // Return HTML view for preview (not PDF)
        return view('pages.transaction.pdfs.attendance-odissa', [
            'attendances' => $attendances,
            'overtimes' => $overtimes,
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay,
            'siteName' => $siteName,
            'site' => $site,
            'daysInMonth' => $daysInMonth,
            'holidayRecords' => $holidayRecords,
            'company' => $company
        ]);
    }
    
    public function attendanceOdissaPdf(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get attendance data with all employee details
        $query = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('attendances.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('employees.site_id', $siteId);
        }
        
        $attendances = $query->select(
                'attendances.*', 
                'employees.EmpId', 
                'employees.Name as employee_name',
                'employees.Gender',
                'employees.Dob',
                'employees.Doj',
                'employees.Esic',
                'employees.Uan',
                'employees.PfApplicable',
                'sites.name as site_name', 
                'departments.name as department', 
                'designations.name as designation',
                'rates.*'
            )
            ->orderBy('employees.EmpId')
            ->get();
        
        // Get payroll data for wage calculations
        $aadhars = $attendances->pluck('aadhar')->unique();
        $payrolls = Payroll::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Get overtime data
        $overtimes = \App\Overtime::whereIn('aadhar', $aadhars)
            ->where('month_year', $monthYear)
            ->get()
            ->keyBy('aadhar');
        
        // Calculate days in month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        $monthDisplay = $date->format('F Y');
        
        $siteName = 'All Sites';
        $site = null;
        if ($siteId && $siteId !== 'all') {
            $site = Site::find($siteId);
            $siteName = $site ? $site->name : 'All Sites';
        }
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Get company data from site or cookie
        $company = null;
        if ($site && $site->company_id) {
            $company = \App\Company::find($site->company_id);
        }
        if (!$company) {
            $companyId = \Cookie::get('company_id');
            $company = $companyId ? \App\Company::find($companyId) : null;
        }
        if (!$company) {
            $company = \App\Company::first();
        }
        
        // Render view template
        $html = view('pages.transaction.pdfs.attendance-odissa', [
            'attendances' => $attendances,
            'overtimes' => $overtimes,
            'payrolls' => $payrolls,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay,
            'siteName' => $siteName,
            'site' => $site,
            'daysInMonth' => $daysInMonth,
            'holidayRecords' => $holidayRecords,
            'company' => $company
        ])->render();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Force download instead of inline display
        return $dompdf->stream('attendance_odissa_' . $monthYear . '.pdf', ['Attachment' => 1]);
    }
    
    public function wageSlipPreview(Request $request)
    {
        $aadhar = $request->input('aadhar');
        $monthYear = $request->input('month_year');
        
        if (!$aadhar || !$monthYear) {
            return response('Aadhar and Month are required', 400);
        }
        
        // Get payroll data
        $payroll = Payroll::where('aadhar', $aadhar)
            ->where('month_year', $monthYear)
            ->first();
        
        if (!$payroll) {
            return response('Payroll data not found', 404);
        }
        
        // Get employee data
        $employee = Employee::where('Aadhar', $aadhar)->first();
        if (!$employee) {
            return response('Employee not found', 404);
        }
        
        // Get site data
        $site = Site::find($payroll->site_id);
        
        // Get attendance record
        $attendance = Attendance::where('aadhar', $aadhar)
            ->where('month_year', $monthYear)
            ->first();
        
        // Get overtime data
        $overtime = \App\Overtime::where('aadhar', $aadhar)
            ->where('month_year', $monthYear)
            ->first();
        
        // Get rate data
        $rate = $employee->rate;
        
        // Calculate days worked
        $present = $payroll->present_days ?? 0;
        $nh = 0;
        $fl = 0;
        $l = 0;
        
        // Calculate days in month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        
        if ($attendance) {
            // Count 'O' (Off/Holiday) days - ONLY for days within the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dayValue = $attendance->{'day_' . $day};
                if ($dayValue === 'O') {
                    $l++;
                }
            }
        }
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
            
            // Count NH and FL days
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                foreach ($holidayRecords as $holidayRecord) {
                    if ($holidayRecord->date->format('Y-m-d') === $checkDate) {
                        if ($holidayRecord->leave_type === 'NH') {
                            $nh++;
                        } elseif ($holidayRecord->leave_type === 'FL') {
                            $fl++;
                        }
                        break;
                    }
                }
            }
        }
        
        $totalWorked = $present + $nh + $fl + $l;
        
        // Calculate wage details
        $basicRate = $payroll->basic_rate ?? 0;
        $daRate = $payroll->da_rate ?? 0;
        $totalRate = $basicRate + $daRate;
        
        // Daily rate calculation
        $dailyRate = $totalRate;
        
        // Amount of wages (Basic + DA based on worked days)
        $basicAmount = $payroll->basic_amount ?? 0;
        $daAmount = $payroll->da_amount ?? 0;
        $amountOfWages = $basicAmount + $daAmount;
        
        // Other Pay (Special Allowance)
        $otherPay = $rate ? ($rate->SpecialAllowance ?? 0) : 0;
        
        // Overtime
        $otHours = $payroll->ot_hours ?? 0;
        $otAmount = $payroll->ot_amount ?? 0;
        
        // Gross Wages Payable
        $grossWages = round($amountOfWages + $otherPay + $otAmount);
        
        // Deductions
        $pfDeduction = $payroll->pf_employee ?? 0;
        $esicDeduction = $payroll->esic_employee ?? 0;
        $isPRFTaxApplicable = !empty($employee->PRFTax) && ($employee->PRFTax === true || $employee->PRFTax === 1 || $employee->PRFTax === '1');
        $prfDeduction = $this->calculatePRFTax($grossWages, $isPRFTaxApplicable);
        $totalDeductions = $pfDeduction + $esicDeduction + $prfDeduction;
        
        // Net Amount Paid
        $netAmount = round($grossWages - $totalDeductions);
        
        // Format month for display
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $monthDisplay = $date->format('M - Y');
        
        // Return HTML view
        return view('pages.transaction.pdfs.wage-slip', [
            'employee' => $employee,
            'payroll' => $payroll,
            'site' => $site,
            'rate' => $rate,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay,
            'totalWorked' => $totalWorked,
            'dailyRate' => $dailyRate,
            'otherPay' => $otherPay,
            'otHours' => $otHours,
            'otAmount' => $otAmount,
            'amountOfWages' => $amountOfWages,
            'grossWages' => $grossWages,
            'pfDeduction' => $pfDeduction,
            'esicDeduction' => $esicDeduction,
            'prfDeduction' => $prfDeduction,
            'totalDeductions' => $totalDeductions,
            'netAmount' => $netAmount
        ]);
    }
    
    public function allWageSlipsPreview(Request $request)
    {
        $monthYear = $request->input('month_year');
        $siteId = $request->input('site_id', 'all');
        
        if (!$monthYear) {
            return response('Month is required', 400);
        }
        
        // Get all payroll data (similar to slip method)
        $query = Payroll::leftJoin('employees', 'payrolls.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'payrolls.site_id', '=', 'sites.id')
            ->leftJoin('rates', 'employees.id', '=', 'rates.employee_id')
            ->where('payrolls.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $query->where('payrolls.site_id', $siteId);
        }
        
        $payrolls = $query->select(
            'payrolls.*',
            'employees.EmpId',
            'employees.id as employee_id',
            'employees.PRFTax',
            'employees.EsicApplicable',
            'employees.PfApplicable',
            'sites.name as site_name',
            'rates.SpecialAllowance'
        )->orderBy('employees.EmpId')->get();
        
        // Get NH and FL days for the month
        $holidayRecords = [];
        if ($monthYear) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
            $holidayRecords = CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                ->whereIn('leave_type', ['NH', 'FL'])
                ->get();
        }
        
        // Format month for display
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $monthDisplay = $date->format('M - Y');
        
        // Get site info (use first payroll's site)
        $site = null;
        if ($payrolls->count() > 0) {
            $site = Site::find($payrolls->first()->site_id);
        }
        
        // Prepare slip data for each employee
        $slipsData = [];
        foreach ($payrolls as $payroll) {
            $employee = Employee::where('Aadhar', $payroll->aadhar)->first();
            if (!$employee) continue;
            
            $attendance = Attendance::where('aadhar', $payroll->aadhar)
                ->where('month_year', $monthYear)
                ->first();
            
            $present = $payroll->present_days ?? 0;
            $nh = 0;
            $fl = 0;
            $l = 0;
            
            // Calculate days in month
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
            
            if ($attendance) {
                // Count 'O' (Off/Holiday) days - ONLY for days within the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dayValue = $attendance->{'day_' . $day};
                    if ($dayValue === 'O') {
                        $l++;
                    }
                }
            }
            
            // Count NH and FL days
            if (!empty($holidayRecords) && $monthYear) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $checkDate = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    foreach ($holidayRecords as $holidayRecord) {
                        if ($holidayRecord->date->format('Y-m-d') === $checkDate) {
                            if ($holidayRecord->leave_type === 'NH') {
                                $nh++;
                            } elseif ($holidayRecord->leave_type === 'FL') {
                                $fl++;
                            }
                            break;
                        }
                    }
                }
            }
            
            $totalWorked = $present + $nh + $fl + $l;
            
            // Calculate wage details
            $basicRate = $payroll->basic_rate ?? 0;
            $daRate = $payroll->da_rate ?? 0;
            $totalRate = $basicRate + $daRate;
            $dailyRate = $totalRate;
            
            $basicAmount = $payroll->basic_amount ?? 0;
            $daAmount = $payroll->da_amount ?? 0;
            $amountOfWages = $basicAmount + $daAmount;
            
            $otherPay = $payroll->SpecialAllowance ?? 0;
            $otHours = $payroll->ot_hours ?? 0;
            $otAmount = $payroll->ot_amount ?? 0;
            $grossWages = round($amountOfWages + $otherPay + $otAmount);
            
            $pfDeduction = $payroll->pf_employee ?? 0;
            $esicDeduction = $payroll->esic_employee ?? 0;
            $isPRFTaxApplicable = !empty($employee->PRFTax) && ($employee->PRFTax === true || $employee->PRFTax === 1 || $employee->PRFTax === '1');
            $prfDeduction = $this->calculatePRFTax($grossWages, $isPRFTaxApplicable);
            $totalDeductions = $pfDeduction + $esicDeduction + $prfDeduction;
            $netAmount = round($grossWages - $totalDeductions);
            
            $slipsData[] = [
                'employee' => $employee,
                'payroll' => $payroll,
                'rate' => $employee->rate,
                'totalWorked' => $totalWorked,
                'dailyRate' => $dailyRate,
                'otherPay' => $otherPay,
                'otHours' => $otHours,
                'otAmount' => $otAmount,
                'amountOfWages' => $amountOfWages,
                'grossWages' => $grossWages,
                'pfDeduction' => $pfDeduction,
                'esicDeduction' => $esicDeduction,
                'prfDeduction' => $prfDeduction,
                'totalDeductions' => $totalDeductions,
                'netAmount' => $netAmount
            ];
        }
        
        return view('pages.transaction.pdfs.all-wage-slips', [
            'slipsData' => $slipsData,
            'site' => $site,
            'monthYear' => $monthYear,
            'monthDisplay' => $monthDisplay
        ]);
    }
    
    /**
     * Calculate PRF Tax based on gross salary
     * 
     * @param float $gross The gross salary amount
     * @param bool $isPRFTaxApplicable Whether PRF Tax is applicable for the employee
     * @return float The PRF Tax amount
     */
    private function calculatePRFTax($gross, $isPRFTaxApplicable)
    {
        if (!$isPRFTaxApplicable) {
            return 0;
        }
        
        // If gross is more than 13304 and less than 24999: PRF Tax = 125
        if ($gross > 13304 && $gross < 24999) {
            return 125;
        }
        
        // If gross is more than 25000 and less than 30000: PRF Tax = 200
        if ($gross >= 25000 && $gross < 30000) {
            return 200;
        }
        
        // Default: no PRF Tax for other ranges
        return 0;
    }
}
