<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Overtime;
use App\Employee;
use App\Site;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AttendanceController extends Controller
{
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
            return redirect('/stc/stc_payroll/')->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.transaction.attendance', [
            'page_title' => 'Attendance Management',
            'sites' => Site::all()
        ]);
    }

    public function list(Request $request)
    {
        // Require both month_year and site_id filters
        $monthYear = $request->has('month_year') && $request->month_year ? $request->month_year : null;
        $siteId = $request->has('site_id') && $request->site_id ? $request->site_id : null;
        
        // If both filters are not provided, return empty result
        if (!$monthYear || !$siteId) {
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
            ->leftJoin('sites', 'employees.site_id', '=', 'sites.id');

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

        // Filter by site_id
        $query->where('employees.site_id', $siteId);

        // Select attendance and site columns
        $query->select('attendances.*', 'sites.name as site_name');

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
        $statsQuery->where('employees.site_id', $siteId);
        
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
            // Count attendance codes
            for ($day = 1; $day <= 31; $day++) {
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
            
            // Count attendance codes
            for ($day = 1; $day <= 31; $day++) {
                $dayValue = $attendance->{'day_' . $day};
                if ($dayValue === 'P') {
                    $present++;
                } elseif ($dayValue === 'A') {
                    $absent++;
                } elseif ($dayValue === 'O') {
                    $offHoliday++;
                }
            }
            
            // Get OT hours from overtime table
            $overtime = Overtime::where('aadhar', $attendance->aadhar)
                ->where('month_year', $attMonthYear)
                ->first();
            
            if ($overtime) {
                for ($day = 1; $day <= 31; $day++) {
                    $otValue = $overtime->{'day_' . $day};
                    if ($otValue !== null && $otValue > 0) {
                        $otHours += $otValue;
                    }
                }
            }
            
            $data[] = [
                'id' => $attendance->id,
                'site_name' => $attendance->site_name ?? 'N/A',
                'aadhar' => $attendance->aadhar,
                'employee_name' => $attendance->employee_name,
                'month_year' => $attendance->month_year,
                'working_days' => $workingDays,
                'sundays' => $sundays,
                'present' => $present,
                'absent' => $absent,
                'ot' => $otHours, // Show OT hours, not Off/Holiday count
                'created_at' => $attendance->created_at ? $attendance->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $attendance->updated_at ? $attendance->updated_at->format('Y-m-d H:i:s') : 'N/A',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Attendance::count(),
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);
        
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            // Get headers from first row
            $headers = array_filter($rows[0]);
            $headersLower = array_map('strtolower', $headers);
            
            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            $parsedData = [];
            
            foreach ($dataRows as $row) {
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
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse file: ' . $e->getMessage()
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
            
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
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
                    
                    // Extract day values (1-31)
                    for ($day = 1; $day <= 31; $day++) {
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
                    
                    // Extract day values (1-31) - should be numbers 0-8
                    for ($day = 1; $day <= 31; $day++) {
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
            } else {
                $displayValue .= '+0';
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
}

