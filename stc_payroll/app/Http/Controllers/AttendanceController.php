<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Overtime;
use App\Employee;
use App\Site;
use App\Payroll;
use App\PayrollParameter;
use App\Rate;
use App\CalendarLeaveType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Traits\HasCompanyFilter;

class AttendanceController extends Controller
{
    use HasCompanyFilter;
    /**
     * Check if required PHP extensions are available
     * 
     * @return array|null Returns null if all extensions are available, or an error response array
     */
    private function checkRequiredExtensions()
    {
        if (!class_exists('ZipArchive')) {
            return [
                'success' => false,
                'message' => 'Server configuration error: PHP Zip extension is not installed or enabled. Please contact your server administrator to enable the PHP zip extension. This is required to read Excel (.xlsx) files.',
                'error_code' => 'MISSING_ZIP_EXTENSION'
            ];
        }
        
        if (!extension_loaded('xml')) {
            return [
                'success' => false,
                'message' => 'Server configuration error: PHP XML extension is not installed or enabled. Please contact your server administrator to enable the PHP xml extension.',
                'error_code' => 'MISSING_XML_EXTENSION'
            ];
        }
        
        return null;
    }
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('transaction.attendance.view') && $user->email !== 'root@stcassociate.com')) {
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
        
        return view('pages.transaction.attendance', [
            'page_title' => 'Attendance Management',
            'sites' => $sitesQuery->get()
        ]);
    }

    public function list(Request $request)
    {
        // Require month_year filter
        $monthYear = $request->has('month_year') && $request->month_year ? $request->month_year : null;
        $siteId = $request->has('site_id') && $request->site_id ? $request->site_id : null;
        $includeDays = $request->has('include_days') && $request->include_days == 'true';
        
        // If month_year is not provided, return empty result
        if (!$monthYear) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'statistics' => [
                    'working_days' => 0,
                    'total_present' => 0,
                    'total_absent' => 0,
                    'total_ot' => 0,
                    'total_sundays' => 0
                ]
            ]);
        }
        
        $query = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id');

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('attendances.aadhar', 'like', "%{$search}%")
                  ->orWhere('attendances.employee_name', 'like', "%{$search}%")
                  ->orWhere('attendances.month_year', 'like', "%{$search}%")
                  ->orWhere('sites.name', 'like', "%{$search}%");
            });
        }

        // Filter by month_year
        $query->where('attendances.month_year', $monthYear);
        
        // Get company ID for filtering
        $companyId = $this->getSelectedCompanyId();

        // Filter by selected company
        if ($companyId) {
            $query->where('employees.company_id', $companyId);
        }
        
        // Filter by site_id (handle 'all')
        if ($siteId && $siteId !== 'all') {
            $query->where('employees.site_id', $siteId);
        }

        // Select attendance, site, and employee columns
        $query->select('attendances.*', 'sites.name as site_name', 'departments.name as department', 'designations.name as designation', 'employees.EmpId as empid');

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['id', 'site_name', 'aadhar', 'employee_name', 'month_year', 'working_days', 'sundays', 'present', 'absent', 'ot', 'created_at', 'updated_at'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
            if ($orderBy === 'id') {
                $query->orderBy('attendances.id', $orderDir);
            } else {
                $query->orderBy('attendances.' . $orderBy, $orderDir);
            }
        } else {
            $query->orderBy('attendances.month_year', 'desc')->orderBy('attendances.employee_name', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $attendances = $query->skip($start)->take($length)->get();

        // Calculate statistics for filtered data
        $statsQuery = Attendance::leftJoin('employees', 'attendances.aadhar', '=', 'employees.Aadhar');
        $statsQuery->where('attendances.month_year', $monthYear);
        
        // Filter by selected company
        if ($companyId) {
            $statsQuery->where('employees.company_id', $companyId);
        }
        
        if ($siteId && $siteId !== 'all') {
            $statsQuery->where('employees.site_id', $siteId);
        }
        
        $allAttendances = $statsQuery->select('attendances.*')->get();
        
        $totalWorkingDays = 0;
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalOT = 0;
        $totalSundays = 0;
        
        // Calculate number of Sundays in the month
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        
        // Count Sundays in the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
            if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                $totalSundays++;
            }
        }
        
        $workingDaysInMonth = $daysInMonth - $totalSundays;
        $totalWorkingDays = $workingDaysInMonth * $allAttendances->count();
        
        foreach ($allAttendances as $attendance) {
            // Count attendance codes - only for actual days in the month
            // IMPORTANT: Exclude Sundays - they are not working days, so attendance doesn't count
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                // Skip Sundays - they are not working days, so attendance doesn't count
                if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                    continue;
                }
                
                $dayValue = $attendance->{'day_' . $day};
                if ($dayValue === 'P') {
                    $totalPresent++;
                } elseif ($dayValue === 'A') {
                    $totalAbsent++;
                } elseif ($dayValue === 'O') {
                    $totalOT++;
                }
            }
        }

        $data = [];
        foreach ($attendances as $attendance) {
            // Calculate statistics for each row
            $present = 0;
            $absent = 0;
            $offHoliday = 0;
            $otHours = 0;
            
            $attMonthYear = $attendance->month_year;
            $date = \Carbon\Carbon::createFromFormat('Y-m', $attMonthYear);
            $daysInMonth = $date->daysInMonth;
            
            // Count Sundays for this month
            $sundays = 0;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $attMonthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                    $sundays++;
                }
            }
            
            $workingDays = $daysInMonth - $sundays;
            
            // Count attendance codes - only for actual days in the month
            // IMPORTANT: Exclude Sundays - they are not working days, so attendance doesn't count
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $attMonthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                // Skip Sundays - they are not working days, so attendance doesn't count
                if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                    continue;
                }
                
                $dayValue = $attendance->{'day_' . $day};
                if ($dayValue === 'P') {
                    $present++;
                } elseif ($dayValue === 'A') {
                    $absent++;
                } elseif ($dayValue === 'O') {
                    $offHoliday++;
                }
            }
            
            // Get OT hours from overtime table - only for actual days in the month
            $overtime = Overtime::where('aadhar', $attendance->aadhar)
                ->where('month_year', $attMonthYear)
                ->first();
            
            if ($overtime) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $otValue = $overtime->{'day_' . $day};
                    if ($otValue !== null && $otValue > 0) {
                        $otHours += $otValue;
                    }
                }
            }
            
            $rowData = [
                'id' => $attendance->id,
                'site_name' => $attendance->site_name ?? 'N/A',
                'aadhar' => $attendance->aadhar,
                'employee_name' => $attendance->employee_name,
                'empid' => $attendance->empid ?? '',
                'department' => $attendance->department ?? '',
                'designation' => $attendance->designation ?? '',
                'month_year' => $attendance->month_year,
                'working_days' => $workingDays,
                'sundays' => $sundays,
                'present' => $present,
                'absent' => $absent,
                'ot' => $otHours, // Show OT hours, not Off/Holiday count
                'created_at' => $attendance->created_at ? $attendance->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $attendance->updated_at ? $attendance->updated_at->format('Y-m-d H:i:s') : 'N/A',
            ];
            
            // Include day-by-day data if requested - only for actual days in the month
            if ($includeDays) {
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $rowData['day_' . $day] = $attendance->{'day_' . $day} ?? '';
                    if ($overtime) {
                        $rowData['ot_day_' . $day] = $overtime->{'day_' . $day} ?? 0;
                    } else {
                        $rowData['ot_day_' . $day] = 0;
                    }
                }
            }
            
            $data[] = $rowData;
        }
        
        // Get total records count with company filter (via employees)
        $totalCountQuery = Attendance::join('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
            ->where('attendances.month_year', $monthYear);
        
        if ($siteId && $siteId !== 'all') {
            $totalCountQuery->where('employees.site_id', $siteId);
        }
        
        // Filter by selected company
        if ($companyId) {
            $totalCountQuery->where('employees.company_id', $companyId);
        }
        
        $totalCount = $totalCountQuery->count();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'statistics' => [
                'working_days' => $totalWorkingDays,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'total_ot' => $totalOT,
                'total_sundays' => $totalSundays
            ]
        ]);
    }

    public function exportSample(Request $request)
    {
        $type = $request->input('type', 'attendance'); // 'attendance' or 'ot'
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers: Aadhar, Employee Name, then days 1-30
        $headers = ['Aadhar', 'Employee Name'];
        for ($i = 1; $i <= 30; $i++) {
            $headers[] = (string)$i;
        }

        // Set headers
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $column++;
        }

        if ($type === 'ot') {
            $sheet->setTitle('OT Import Sample');
            
            // OT sample rows - numbers 0-8 representing hours worked
            $sampleRows = [
                ['271091072582', 'SANA YASIN', 8, 8, 0, 8, 8, 8, 8, 8, 0, 8, 0, 8, 8, 8, 0, 8, 8, 8, 8, 8, 8, 8, 0, 8, 8, 8, 8, 8, 8, 8],
                ['527924839724', 'MANAS BEHERA', 0, 8, 0, 8, 8, 0, 8, 8, 8, 0, 8, 8, 8, 8, 8, 0, 0, 8, 8, 8, 8, 8, 8, 0, 8, 8, 8, 8, 8, 8],
                ['979010456131', 'HASIBUL MONDAL', 8, 8, 0, 8, 8, 8, 8, 8, 0, 8, 8, 8, 8, 0, 0, 0, 8, 8, 8, 8, 8, 8, 0, 8, 8, 8, 8, 8, 8, 8]
            ];
            
            $filename = 'ot_import_sample.xlsx';
        } else {
            $sheet->setTitle('Attendance Import Sample');
            
            // Attendance sample rows - P, O, A
            $sampleRows = [
                ['271091072582', 'SANA YASIN', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'O', 'P', 'A', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
                ['527924839724', 'MANAS BEHERA', 'A', 'P', 'O', 'P', 'P', 'A', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'O', 'A', 'P', 'P', 'P', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'P'],
                ['979010456131', 'HASIBUL MONDAL', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'A', 'O', 'A', 'P', 'P', 'P', 'P', 'P', 'P', 'O', 'P', 'P', 'P', 'P', 'P', 'P', 'P']
            ];
            
            $filename = 'attendance_import_sample.xlsx';
        }

        $rowNum = 2;
        foreach ($sampleRows as $sampleRow) {
            $column = 'A';
            foreach ($sampleRow as $value) {
                $sheet->setCellValue($column . $rowNum, $value);
                $column++;
            }
            $rowNum++;
        }

        // Auto-size columns
        foreach (range('A', 'AF') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function importPreview(Request $request)
    {
        try {
            // Validate request with better error handling
            try {
                $request->validate([
                    'file' => 'required|mimes:xlsx,xls|max:10240'
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
                    'errors' => $e->validator->errors()
                ], 422);
            }
            
            $file = $request->file('file');
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded'
                ], 400);
            }
            
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file uploaded: ' . $file->getError()
                ], 400);
            }
            
            $filePath = $file->getRealPath();
            if (!$filePath || !file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File path is invalid or file does not exist'
                ], 400);
            }
            
            // Check file size
            $fileSize = filesize($filePath);
            if ($fileSize === false || $fileSize > 10240 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'File size exceeds maximum allowed size (10MB)'
                ], 400);
            }
            
            // Check if required PHP extensions are available
            $extensionCheck = $this->checkRequiredExtensions();
            if ($extensionCheck !== null) {
                Log::error('Required PHP extension missing', $extensionCheck);
                return response()->json($extensionCheck, 500);
            }
            
            // Increase memory limit for large files
            $originalMemoryLimit = ini_get('memory_limit');
            ini_set('memory_limit', '256M');
            
            try {
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
            } catch (\Exception $e) {
                ini_set('memory_limit', $originalMemoryLimit);
                // Check if it's a PhpSpreadsheet related error
                $exceptionClass = get_class($e);
                if (strpos($exceptionClass, 'PhpOffice') !== false || strpos($exceptionClass, 'PhpSpreadsheet') !== false) {
                    Log::error('PhpSpreadsheet Error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to read Excel file. Please ensure the file is a valid Excel file (.xlsx or .xls format).'
                    ], 400);
                }
                throw $e;
            }
            
            // Restore memory limit
            ini_set('memory_limit', $originalMemoryLimit);
            
            if (empty($rows) || count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            // Get headers from first row
            $headers = array_filter($rows[0]);
            if (empty($headers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File has no headers in the first row'
                ], 400);
            }
            
            $headersLower = array_map('strtolower', $headers);
            
            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            $parsedData = [];
            
            foreach ($dataRows as $rowIndex => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }
                
                $rowData = [];
                foreach ($headersLower as $index => $header) {
                    $value = isset($row[$index]) ? $row[$index] : '';
                    // Preserve numeric values for OT, convert to string for attendance
                    if (is_numeric($value)) {
                        $rowData[$header] = $value;
                    } else {
                        $rowData[$header] = trim((string)$value);
                    }
                }
                $parsedData[] = $rowData;
            }
            
            return response()->json([
                'success' => true,
                'headers' => array_values($headers),
                'data' => $parsedData
            ]);
            
        } catch (ValidationException $e) {
            Log::error('Import Preview Validation Error', [
                'errors' => $e->validator->errors()->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => $e->validator->errors()
            ], 422);
            
        } catch (\Exception $e) {
            // Check for ZipArchive missing error
            if (strpos($e->getMessage(), 'ZipArchive') !== false || strpos($e->getMessage(), 'Class "ZipArchive" not found') !== false) {
                Log::error('ZipArchive extension not available: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Server configuration error: PHP Zip extension is not installed or enabled. Please contact your server administrator to enable the PHP zip extension. This is required to read Excel (.xlsx) files.',
                    'error_code' => 'MISSING_ZIP_EXTENSION'
                ], 500);
            }
            
            // Check if it's a PhpSpreadsheet related error
            $exceptionClass = get_class($e);
            if (strpos($exceptionClass, 'PhpOffice') !== false || strpos($exceptionClass, 'PhpSpreadsheet') !== false) {
                Log::error('Import Preview PhpSpreadsheet Error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to parse Excel file: ' . $e->getMessage(),
                    'error_details' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ] : null
                ], 500);
            }
            Log::error('Import Preview Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($e)
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process file: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $type = $request->input('type', 'attendance'); // 'attendance' or 'ot'
        
        if ($request->has('data') && is_array($request->data)) {
            // Import from preview data
            $dataRows = $request->data;
        } else {
            // Legacy: import from file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:10240'
            ]);
            
            // Check if required PHP extensions are available
            $extensionCheck = $this->checkRequiredExtensions();
            if ($extensionCheck !== null) {
                Log::error('Required PHP extension missing during import', $extensionCheck);
                return response()->json($extensionCheck, 500);
            }
            
            $file = $request->file('file');
            
            try {
                $spreadsheet = IOFactory::load($file->getRealPath());
            } catch (\Exception $e) {
                // Check for ZipArchive missing error
                if (strpos($e->getMessage(), 'ZipArchive') !== false || strpos($e->getMessage(), 'Class "ZipArchive" not found') !== false) {
                    Log::error('ZipArchive extension not available during import: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Server configuration error: PHP Zip extension is not installed or enabled. Please contact your server administrator to enable the PHP zip extension. This is required to read Excel (.xlsx) files.',
                        'error_code' => 'MISSING_ZIP_EXTENSION'
                    ], 500);
                }
                throw $e;
            }
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            $headers = array_map('strtolower', array_filter($rows[0]));
            $dataRows = array_slice($rows, 1);
            
            // Convert to same format as preview
            $parsedData = [];
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) continue;
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $value = isset($row[$index]) ? $row[$index] : '';
                    // Preserve numeric values for OT, convert to string for attendance
                    if (is_numeric($value)) {
                        $rowData[$header] = $value;
                    } else {
                        $rowData[$header] = trim((string)$value);
                    }
                }
                $parsedData[] = $rowData;
            }
            $dataRows = $parsedData;
        }

        // Get month_year from request or use current month
        $monthYear = $request->input('month_year', date('Y-m'));
        
        // Use appropriate model based on type
        if ($type === 'ot') {
            return $this->importOT($dataRows, $monthYear);
        } else {
            return $this->importAttendance($dataRows, $monthYear);
        }
    }
    
    private function importAttendance($dataRows, $monthYear)
    {
        try {
            // Calculate actual days in the month
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($dataRows as $index => $rowData) {
                try {
                    // Extract aadhar and employee name
                    $aadhar = '';
                    $employeeName = '';
                    
                    // Try different possible column names
                    if (isset($rowData['aadhar'])) {
                        $aadhar = trim((string)$rowData['aadhar']);
                    } elseif (isset($rowData['aadhaar'])) {
                        $aadhar = trim((string)$rowData['aadhaar']);
                    }
                    
                    if (isset($rowData['employee name'])) {
                        $employeeName = trim((string)$rowData['employee name']);
                    } elseif (isset($rowData['employee_name'])) {
                        $employeeName = trim((string)$rowData['employee_name']);
                    } elseif (isset($rowData['name'])) {
                        $employeeName = trim((string)$rowData['name']);
                    }
                    
                    if (empty($aadhar) || empty($employeeName)) {
                        $errors[] = 'Row ' . ($index + 1) . ': Missing Aadhar or Employee Name';
                        continue;
                    }
                    
                    // Build attendance data
                    $attendanceData = [
                        'aadhar' => $aadhar,
                        'employee_name' => $employeeName,
                        'month_year' => $monthYear
                    ];
                    
                    // Extract day values - only for actual days in the month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dayKey = (string)$day;
                        $dayValue = '';
                        
                        // Try different possible column names
                        if (isset($rowData[$dayKey])) {
                            $dayValue = strtoupper(trim((string)$rowData[$dayKey]));
                        }
                        
                        // Validate day value (P, O, A, or empty)
                        if (!empty($dayValue) && !in_array($dayValue, ['P', 'O', 'A'])) {
                            $dayValue = ''; // Invalid value, set to null
                        }
                        
                        $attendanceData['day_' . $day] = !empty($dayValue) ? $dayValue : null;
                    }
                    
                    // Set days beyond the month to null (e.g., day_31 for months with 30 days)
                    for ($day = $daysInMonth + 1; $day <= 31; $day++) {
                        $attendanceData['day_' . $day] = null;
                    }
                    
                    // Check if attendance already exists for this aadhar and month_year
                    $existing = Attendance::where('aadhar', $aadhar)
                        ->where('month_year', $monthYear)
                        ->first();
                    
                    if ($existing) {
                        $existing->update($attendanceData);
                        $updated++;
                    } else {
                        Attendance::create($attendanceData);
                        $imported++;
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }
            
            DB::commit();
            
            $message = "Successfully imported {$imported} attendance record(s)";
            if ($updated > 0) {
                $message .= " and updated {$updated} existing record(s)";
            }
            if (count($errors) > 0) {
                $message .= '. Some errors occurred: ' . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' and ' . (count($errors) - 5) . ' more errors';
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function importOT($dataRows, $monthYear)
    {
        try {
            // Calculate actual days in the month
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($dataRows as $index => $rowData) {
                try {
                    // Extract aadhar and employee name
                    $aadhar = '';
                    $employeeName = '';
                    
                    // Try different possible column names
                    if (isset($rowData['aadhar'])) {
                        $aadhar = trim((string)$rowData['aadhar']);
                    } elseif (isset($rowData['aadhaar'])) {
                        $aadhar = trim((string)$rowData['aadhaar']);
                    }
                    
                    if (isset($rowData['employee name'])) {
                        $employeeName = trim((string)$rowData['employee name']);
                    } elseif (isset($rowData['employee_name'])) {
                        $employeeName = trim((string)$rowData['employee_name']);
                    } elseif (isset($rowData['name'])) {
                        $employeeName = trim((string)$rowData['name']);
                    }
                    
                    if (empty($aadhar) || empty($employeeName)) {
                        $errors[] = 'Row ' . ($index + 1) . ': Missing Aadhar or Employee Name';
                        continue;
                    }
                    
                    // Build OT data
                    $otData = [
                        'aadhar' => $aadhar,
                        'employee_name' => $employeeName,
                        'month_year' => $monthYear
                    ];
                    
                    // Extract day values - only for actual days in the month (should be numbers 0-8)
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dayKey = (string)$day;
                        $dayValue = null;
                        
                        // Try different possible column names
                        if (isset($rowData[$dayKey])) {
                            $rawValue = $rowData[$dayKey];
                            
                            // Handle both string and numeric values from Excel
                            if (is_numeric($rawValue)) {
                                $dayValue = (int)$rawValue;
                            } else {
                                $dayValue = trim((string)$rawValue);
                                // Try to convert string to number
                                if (is_numeric($dayValue)) {
                                    $dayValue = (int)$dayValue;
                                } else {
                                    $dayValue = null;
                                }
                            }
                            
                            // Validate range 0-8
                            if ($dayValue !== null && ($dayValue < 0 || $dayValue > 8)) {
                                $dayValue = null; // Invalid value, set to null
                            }
                        }
                        
                        $otData['day_' . $day] = $dayValue;
                    }
                    
                    // Set days beyond the month to null (e.g., day_31 for months with 30 days)
                    for ($day = $daysInMonth + 1; $day <= 31; $day++) {
                        $otData['day_' . $day] = null;
                    }
                    
                    // Check if OT already exists for this aadhar and month_year
                    $existing = Overtime::where('aadhar', $aadhar)
                        ->where('month_year', $monthYear)
                        ->first();
                    
                    if ($existing) {
                        $existing->update($otData);
                        $updated++;
                    } else {
                        Overtime::create($otData);
                        $imported++;
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }
            
            DB::commit();
            
            $message = "Successfully imported {$imported} OT record(s)";
            if ($updated > 0) {
                $message .= " and updated {$updated} existing record(s)";
            }
            if (count($errors) > 0) {
                $message .= '. Some errors occurred: ' . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' and ' . (count($errors) - 5) . ' more errors';
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewDetails(Request $request)
    {
        $aadhar = $request->input('aadhar');
        $monthYear = $request->input('month_year');
        
        if (!$aadhar || !$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Aadhar and Month/Year are required'
            ], 400);
        }
        
        // Get attendance record
        $attendance = Attendance::where('aadhar', $aadhar)
            ->where('month_year', $monthYear)
            ->first();
        
        // Get OT record
        $overtime = Overtime::where('aadhar', $aadhar)
            ->where('month_year', $monthYear)
            ->first();
        
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance record not found'
            ], 404);
        }
        
        // Build day-by-day data
        $daysData = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalOTHours = 0;
        
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $daysInMonth = $date->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $attValue = $attendance->{'day_' . $day};
            $otValue = $overtime ? $overtime->{'day_' . $day} : null;
            
            $displayValue = '';
            if ($attValue === 'P') {
                $displayValue = 'P';
                $totalPresent++;
            } elseif ($attValue === 'A') {
                $displayValue = 'A';
                $totalAbsent++;
            } elseif ($attValue === 'O') {
                $displayValue = 'O';
            }
            
            // Add OT hours if available
            if ($otValue !== null && $otValue > 0) {
                $displayValue .= '+' . $otValue;
                $totalOTHours += $otValue;
            }
            
            $daysData[$day] = $displayValue;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'aadhar' => $attendance->aadhar,
                'employee_name' => $attendance->employee_name,
                'month_year' => $attendance->month_year,
                'days' => $daysData,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'total_ot_hours' => $totalOTHours
            ]
        ]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance record not found'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $attendance->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance record deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting attendance record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processAttendance(Request $request)
    {
        $monthYear = $request->input('month_year');
        
        if (!$monthYear) {
            return response()->json([
                'success' => false,
                'message' => 'Month/Year is required'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Get payroll parameters for selected company
            $companyId = $this->getSelectedCompanyId();
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a company first'
                ], 400);
            }
            
            $params = PayrollParameter::where('company_id', $companyId)->first();
            if (!$params) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payroll parameters not configured for this company. Please configure payroll parameters first.'
                ], 400);
            }
            
            // Get all attendance records for the month (all sites)
            // Use inner join for employees to ensure we only process records with valid employees
            $attendancesQuery = Attendance::join('employees', 'attendances.aadhar', '=', 'employees.Aadhar')
                ->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
                ->where('attendances.month_year', $monthYear);
            
            // Filter by selected company
            $companyId = $this->getSelectedCompanyId();
            if ($companyId) {
                $attendancesQuery->where('employees.company_id', $companyId);
            }
            
            $attendances = $attendancesQuery->select('attendances.*', 'employees.id as employee_id', 'employees.Skill', 'employees.site_id', 'employees.PfApplicable', 'employees.EsicApplicable', 'sites.name as site_name')
                ->get();
            
            if ($attendances->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No attendance records found for the selected month'
                ], 404);
            }
            
            // Get all employee rates keyed by employee_id for quick lookup
            $employeeRates = \App\Rate::whereIn('employee_id', $attendances->pluck('employee_id')->filter()->unique())
                ->get()
                ->keyBy('employee_id');
            
            // Calculate days in month
            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $daysInMonth = $date->daysInMonth;
            
            // Count Sundays
            $sundays = 0;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                    $sundays++;
                }
            }
            
            $workingDays = $daysInMonth - $sundays;
            $processed = 0;
            $failed = [];
            
            foreach ($attendances as $attendance) {
                try {
                    // Validate employee exists
                    if (!$attendance->employee_id) {
                        $failed[] = [
                            'employee_name' => $attendance->employee_name,
                            'aadhar' => $attendance->aadhar,
                            'site_name' => $attendance->site_name ?? 'N/A',
                            'reason' => 'Employee not found in employees table'
                        ];
                        continue;
                    }
                    
                    // Validate site_id (but don't fail if it's null, we can still process)
                    $siteId = $attendance->site_id ?? null;
                    
                    // Calculate days in month (recalculate for each attendance)
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                    $daysInMonth = $date->daysInMonth;
                    
                    // SIMPLE: Count P and A directly from attendance table (excluding Sundays)
                    // This is the actual count from the attendance data - no conversions
                    $present = 0;
                    $absent = 0;
                    $leave = 0; // Leave days (marked as 'O' in attendance)
                    
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $checkDate = \Carbon\Carbon::createFromFormat('Y-m-d', $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));
                        // Skip Sundays - they are not working days
                        if ($checkDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
                            continue;
                        }
                        
                        $dayValue = $attendance->{'day_' . $day};
                        // Normalize the value - trim and convert to uppercase
                        $dayValue = $dayValue ? strtoupper(trim((string)$dayValue)) : '';
                        
                        // Simple count: P = present, A = absent, O = leave
                        if ($dayValue === 'P') {
                            $present++;
                        } elseif ($dayValue === 'A') {
                            $absent++;
                        } elseif ($dayValue === 'O') {
                            $leave++;
                        }
                    }
                    
                    // Store ORIGINAL absent count from attendance table (before any conversions)
                    $originalAbsent = $absent;
                    
                    // Count NH (National Holiday) and FL (Festival Leave) days from calendar for this month
                    $nh = 0;
                    $fl = 0;
                    $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
                    $endDate = \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->endOfMonth();
                    $holidayRecords = \App\CalendarLeaveType::whereBetween('date', [$startDate, $endDate])
                        ->whereIn('leave_type', ['NH', 'FL'])
                        ->get();
                    
                    // Count NH and FL days that fall within the month AND on working days (not Sundays)
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
                    
                    // Calculate total worked days = Present + NH + FL + Leave
                    // Note: NH and FL are holidays, so they count as worked days
                    // But we need to validate that total doesn't exceed working days
                    $totalWorkedDays = $present + $nh + $fl + $leave;
                    
                    // Calculate missing days (working days - total worked days)
                    // This represents days that should have been worked but weren't
                    $missingDays = $workingDays - $totalWorkedDays;
                    
                    // Get current leave balance for employee
                    $employee = \App\Employee::find($attendance->employee_id);
                    if (!$employee) {
                        $failed[] = [
                            'employee_name' => $attendance->employee_name,
                            'aadhar' => $attendance->aadhar,
                            'site_name' => $attendance->site_name ?? 'N/A',
                            'reason' => 'Employee record not found'
                        ];
                        continue;
                    }
                    
                    // Safely get leave_balance - check if column exists
                    $currentLeaveBalance = 0;
                    try {
                        $currentLeaveBalance = (float)($employee->leave_balance ?? 0);
                    } catch (\Exception $e) {
                        // Column might not exist on server, default to 0
                        $currentLeaveBalance = 0;
                    }
                    
                    // IMPORTANT: Validate leave days
                    // If totalWorkedDays > workingDays, it means we have invalid leave days
                    // The excess leave days should be converted back to absent
                    if ($totalWorkedDays > $workingDays) {
                        $excessLeave = $totalWorkedDays - $workingDays;
                        // Convert excess leave back to absent
                        $leave = max(0, $leave - $excessLeave);
                        $absent += $excessLeave;
                        // Recalculate
                        $totalWorkedDays = $present + $nh + $fl + $leave;
                        $missingDays = $workingDays - $totalWorkedDays;
                    }
                    
                    // Process missing days and absent days:
                    // Logic: If employee has absent days and missing days, try to convert absent to leave
                    // Example: November has 25 working days, employee present=24, absent=1
                    // missingDays = 25 - 24 = 1, absent = 1
                    // If employee has leave balance >= 1, convert 1 absent to 1 leave
                    // Final: present=24, leave=1, absent=0, totalWorkedDays=25
                    $leaveUsed = 0;
                    $newLeaveBalance = $currentLeaveBalance;
                    
                    // Check if we need to convert absent days to leave
                    if ($missingDays > 0 && $absent > 0 && $currentLeaveBalance > 0) {
                        // Calculate how many absent days we can convert to leave
                        // We can convert up to: min(absent days, missing days, available leave balance)
                        $daysToConvert = min($absent, $missingDays, $currentLeaveBalance);
                        
                        if ($daysToConvert > 0) {
                            // Convert absent days to leave days
                            $leaveUsed = $daysToConvert;
                            $newLeaveBalance = $currentLeaveBalance - $daysToConvert;
                            $absent -= $daysToConvert;
                            $leave += $daysToConvert;
                            // Recalculate total worked days
                            $totalWorkedDays = $present + $nh + $fl + $leave;
                            // Recalculate missing days (should be 0 or less now)
                            $missingDays = $workingDays - $totalWorkedDays;
                        }
                    } elseif ($missingDays < 0) {
                        // Employee worked more than working days (extra days worked)
                        // This shouldn't happen after validation, but handle it
                        // Add excess to leave balance as earned leave
                        $earnedLeave = abs($missingDays);
                        $newLeaveBalance = $currentLeaveBalance + $earnedLeave;
                    }
                    // If missingDays == 0 or no absent days or no leave balance, no change to balance
                    
                    // Update employee's leave balance
                    $employee->leave_balance = $newLeaveBalance;
                    $employee->save();
                    
                    // IMPORTANT: Store ORIGINAL absent count from attendance table
                    // This is the actual 'A' count from attendance, not the converted value
                    // The absent_days should show how many 'A' were in the attendance table
                    $absentToStore = $originalAbsent;
                    
                    // Get OT hours - only for actual days in the month
                    $otHours = 0;
                    $overtime = Overtime::where('aadhar', $attendance->aadhar)
                        ->where('month_year', $monthYear)
                        ->first();
                    
                    if ($overtime) {
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $otValue = $overtime->{'day_' . $day};
                            if ($otValue !== null && $otValue > 0) {
                                $otHours += $otValue;
                            }
                        }
                    }
                    
                    // Get employee rate from rates table
                    $employeeRate = $employeeRates->get($attendance->employee_id);
                    if (!$employeeRate) {
                        $failed[] = [
                            'employee_name' => $attendance->employee_name,
                            'aadhar' => $attendance->aadhar,
                            'site_name' => $attendance->site_name ?? 'N/A',
                            'reason' => 'Employee rate not found in rates table'
                        ];
                        continue;
                    }
                    
                    // Validate working days
                    if ($workingDays <= 0) {
                        $failed[] = [
                            'employee_name' => $attendance->employee_name,
                            'aadhar' => $attendance->aadhar,
                            'site_name' => $attendance->site_name ?? 'N/A',
                            'reason' => 'Invalid working days calculation'
                        ];
                        continue;
                    }
                    
                    // Get basic and DA from employee's rate
                    // Note: basic and da in rates table are already daily rates
                    $basicRate = $employeeRate->basic ?? 0;
                    $daRate = $employeeRate->da ?? 0;
                    
                    // Get employee category/skill for reference
                    $category = $attendance->Skill ?? 'UN-SKILLED';
                    if (!in_array($category, ['UN-SKILLED', 'SEMI-SKILLED', 'SKILLED', 'HIGH-SKILLED'])) {
                        $category = 'UN-SKILLED';
                    }
                    
                    // Calculate amounts based on TOTAL WORKED DAYS (P + NH + L)
                    // Basic and DA rates are already daily rates, so multiply directly by total worked days
                    $basicAmount = $basicRate * $totalWorkedDays;
                    $daAmount = $daRate * $totalWorkedDays;
                    
                    // Calculate OT amount
                    // OT rate per hour = daily basic rate / 8 hours per day
                    $otRatePerHour = $basicRate / 8;
                    $otAmount = $otRatePerHour * $otHours;
                    
                    // Gross salary
                    $grossSalary = $basicAmount + $daAmount + $otAmount;
                    
                    // Calculate deductions (PF and ESIC are optional - if not applicable, deductions are 0)
                    $pfEmployee = 0;
                    $esicEmployee = 0;
                    
                    // PF deduction only if employee is PF applicable (check for true/1, not just truthy)
                    // PF is calculated on basic amount (min of basic and pf_limit) × pf_percentage
                    $isPfApplicable = !empty($attendance->PfApplicable) && ($attendance->PfApplicable === true || $attendance->PfApplicable === 1 || $attendance->PfApplicable === '1');
                    if ($isPfApplicable && $basicAmount > 0) {
                        $pfBase = min($basicAmount, $params->pf_limit);
                        $pfEmployee = ($pfBase * $params->pf_percentage) / 100;
                    }
                    
                    // ESIC deduction only if employee is ESIC applicable (check for true/1, not just truthy)
                    // ESIC is calculated on gross salary × esic_employer_percentage (if within limit)
                    $isEsicApplicable = !empty($attendance->EsicApplicable) && ($attendance->EsicApplicable === true || $attendance->EsicApplicable === 1 || $attendance->EsicApplicable === '1');
                    if ($isEsicApplicable && $grossSalary <= $params->esic_limit) {
                        $esicEmployee = ($grossSalary * $params->esic_employer_percentage) / 100;
                    }
                    
                    $totalDeductions = $pfEmployee + $esicEmployee;
                    $netSalary = $grossSalary - $totalDeductions;
                    
                    // Create or update payroll record
                    // Use site_id if available, otherwise use 0 (site_id is not required for processing)
                    $payrollSiteId = $siteId ?? 0;
                    
                    Payroll::updateOrCreate(
                        [
                            'aadhar' => $attendance->aadhar,
                            'month_year' => $monthYear,
                            'site_id' => $payrollSiteId
                        ],
                        [
                            'employee_name' => $attendance->employee_name,
                            'working_days' => $workingDays,
                            'present_days' => $present,
                            'absent_days' => $absentToStore, // Store original 'A' count from attendance table
                            'nh_days' => $nh,
                            'fl_days' => $fl,
                            'leave_days' => $leave,
                            'total_worked_days' => $totalWorkedDays,
                            'leave_balance_before' => $currentLeaveBalance,
                            'leave_used' => $leaveUsed,
                            'leave_balance_after' => $newLeaveBalance,
                            'ot_hours' => $otHours,
                            'category' => $category,
                            'basic_rate' => $basicRate,
                            'da_rate' => $daRate,
                            'basic_amount' => round($basicAmount, 2),
                            'da_amount' => round($daAmount, 2),
                            'ot_amount' => round($otAmount, 2),
                            'gross_salary' => round($grossSalary, 2),
                            'pf_employee' => round($pfEmployee, 2),
                            'esic_employee' => round($esicEmployee, 2),
                            'total_deductions' => round($totalDeductions, 2),
                            'net_salary' => round($netSalary, 2)
                        ]
                    );
                    
                    $processed++;
                    
                } catch (\Exception $e) {
                    $failed[] = [
                        'employee_name' => $attendance->employee_name ?? 'N/A',
                        'aadhar' => $attendance->aadhar ?? 'N/A',
                        'site_name' => $attendance->site_name ?? 'N/A',
                        'reason' => $e->getMessage()
                    ];
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$processed} employee(s)" . (count($failed) > 0 ? ". " . count($failed) . " failed." : ""),
                'processed' => $processed,
                'failed' => $failed
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing attendance: ' . $e->getMessage()
            ], 500);
        }
    }
}

