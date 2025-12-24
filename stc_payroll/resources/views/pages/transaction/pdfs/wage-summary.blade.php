<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FORM XVII - {{ $monthYear }}</title>
    <style>
        @page { 
            margin: 8mm;
            size: A4 landscape;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 6px; 
            margin: 0;
            padding: 0;
        }
        .form-header {
            margin-bottom: 5px;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            font-size: 6px;
            line-height: 1.3;
        }
        .header-col {
            flex: 1;
            padding: 0 5px;
        }
        .header-col-left {
            text-align: left;
        }
        .header-col-center {
            text-align: center;
        }
        .header-col-right {
            text-align: right;
        }
        .form-title {
            font-size: 15px;
            font-weight: bold;
            margin: 3px 0;
        }
        .form-subtitle {
            font-size: 12px;
            margin: 2px 0;
        }
        .contractor-section {
            margin-bottom: 3px;
            font-size: 10px;
            line-height: 1.3;
        }
        .contractor-label {
            font-weight: bold;
            margin-bottom: 1px;
        }
        .contractor-value {
            margin-left: 0;
        }
        .info-section {
            margin-bottom: 3px;
            font-size: 10px;
            line-height: 1.3;
        }
        .info-row {
            margin-bottom: 1px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
        }
        .info-value {
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 5px;
            margin-top: 3px;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px 1px;
            text-align: center;
            font-size: 5px;
            line-height: 1.1;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold !important;
            font-size: 10px !important;
        }
        th.col-slim,
        th.col-text,
        th.col-day,
        th.col-numeric {
            font-size: 10px !important;
            font-weight: bold !important;
        }
        th.col-compact {
            font-size: 8px !important;
            font-weight: bold !important;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .sub-header {
            font-size: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer-item {
            display: inline-block;
            margin-right: 15px;
        }
        .signature-col {
            width: 40px;
        }
        .col-slim {
            width: 25px;
            min-width: 25px;
            max-width: 25px;
            padding: 1px;
            font-size: 8px;
        }
        .col-text {
            font-size: 10px;
            padding: 2px 3px;
            /* min-width: 60px;
            max-width: 100px; */
            width: -1px;
        }
        .col-day {
            width: 18px;
            min-width: 18px;
            max-width: 18px;
            padding: 1px;
            font-size: 8px;
        }
        .col-numeric {
            width: 20px;
            min-width: 35px;
            max-width: 50px;
            padding: 1px 2px;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="form-header">
        <!-- Top Section: 3 columns - Contractor (left), FORM XVII (center), Establishment (right) -->
        <div class="header-section">
            <div class="header-col header-col-left">
                <div class="contractor-section">
                    <div class="contractor-label">Name and address of Contractor</div>
                    <div class="contractor-value">GLOBAL AC SYSTEM <br>502/A Jawaharnagar Road-17 Azadnagar</div>
                    <div class="contractor-value">Mango Jsr-832110</div>
                </div>
            </div>
            <div class="header-col header-col-center">
                <div class="form-title">FORM XVII</div>
                <div class="form-subtitle">[See Rule 78(2)(B)]</div>
                <div class="form-subtitle" style="font-weight: bold;">Register For Wages</div>
            </div>
            <div class="header-col header-col-right">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Name and Address of Establishment</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">In/Under which contract is carried on</span>
                    </div>
                    <div class="info-row">
                        <span class="info-value">{{ $site && $site->under_contract ? $site->under_contract : ($site ? $site->name : 'VOLTAS LIMITED') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Section: 3 columns - Nature of Work (left), Month/Year (center), Principle Employer (right) -->
        <div class="header-section">
            <div class="header-col header-col-left">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Nature of Work</span>
                        <span class="info-value">{{ $site && $site->natureofwork ? $site->natureofwork : 'AIR CONDITIONING (1000)' }}</span><br>
                        <span class="info-label">Work Order No</span>
                        <span class="info-value">{{ $site && $site->workorderno ? $site->workorderno : '' }}</span>
                    </div>
                </div>
            </div>
            <div class="header-col header-col-center">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Month :</span>
                        @php
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                            $monthName = strtoupper($date->format('F'));
                            $year = $date->format('Y');
                        @endphp
                        <span class="info-value">{{ $monthName }}</span>
                        <span class="info-label" style="margin-left: 10px;">Year:</span>
                        <span class="info-value">{{ $year }}</span>
                    </div>
                </div>
            </div>
            <div class="header-col header-col-right">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Name and address of principle Employer</span>
                    </div>
                    <div class="info-row">
                        <span class="info-value">{{ $site ? $site->name : 'JSR - TATA STEEL LTD' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-slim">Sl</th>
                <th rowspan="2" class="col-slim">EmpId</th>
                <th rowspan="2" class="col-text" style="width:30px !important">Employee</th>
                <th rowspan="2" class="col-text" style="width:30px !important">Designation</th>
                <th colspan="5" class="col-day">Day</th>
                <th rowspan="2" class="col-numeric">Rate</th>
                <th rowspan="2" class="col-numeric">Basic</th>
                <th rowspan="2" class="col-slim">Da</th>
                <th rowspan="2" class="col-slim col-compact">Other<br>cash</th>
                <th rowspan="2" class="col-slim col-compact">Ot_Hrs</th>
                <th rowspan="2" class="col-numeric col-compact">Ot_Am</th>
                <th rowspan="2" class="col-slim col-compact">Dt_Allow</th>
                <th rowspan="2" class="col-numeric col-compact">Gross</th>
                <th rowspan="2" class="col-numeric col-compact">PF</th>
                <th rowspan="2" class="col-slim col-compact">Esic</th>
                <th rowspan="2" class="col-slim col-compact">PRF</th>
                <th rowspan="2" class="col-slim col-compact">Advance<br>Deduction</th>
                <th rowspan="2" class="col-numeric col-compact">Net</th>
                <th rowspan="2" class="col-slim">Sig/<br>Thumb</th>
                <th rowspan="2" class="col-slim">Sig/<br>Contractor<br>rep</th>
            </tr>
            <tr>
                <th class="sub-header col-day">P</th>
                <th class="sub-header col-day">EL</th>
                <th class="sub-header col-day">CL</th>
                <th class="sub-header col-day">FL</th>
                <th class="sub-header col-day">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1;
                $totalBasic = 0;
                $totalDa = 0;
                $totalAttn = 0;
                $totalGross = 0;
                $totalPF = 0;
                $totalESIC = 0;
                $totalAllowance = 0;
                $totalNet = 0;
            @endphp
            @foreach($payrolls as $payroll)
                @php
                    $present = $payroll->present_days ?? 0;
                    $el = 0; // Earned Leave
                    $cl = 0; // Casual Leave
                    $fl = 0; // Festival Leave
                    $nh = 0;
                    
                    // Get attendance record to calculate leave types
                    $attendance = \App\Attendance::where('aadhar', $payroll->aadhar)
                        ->where('month_year', $monthYear)
                        ->first();
                    
                    if ($attendance) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                        $daysInMonth = $date->daysInMonth;
                        
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $dayValue = $attendance->{'day_' . $day} ?? '';
                            $checkDate = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                            
                            // Check if it's a national holiday
                            $isNH = !empty($nhDays) && in_array($checkDate, $nhDays);
                            
                            if ($dayValue === 'EL' || $dayValue === 'el') {
                                $el++;
                            } elseif ($dayValue === 'CL' || $dayValue === 'cl') {
                                $cl++;
                            } elseif ($dayValue === 'FL' || $dayValue === 'fl') {
                                $fl++;
                            } elseif ($isNH && ($dayValue === '' || $dayValue === null)) {
                                // NH days that are not marked as present
                                $nh++;
                            }
                        }
                    }
                    
                    $totalDays = $present + $el + $cl + $fl;
                    
                    $basicRate = $payroll->basic_rate ?? 0;
                    $daRate = $payroll->da_rate ?? 0;
                    $totalRate = $basicRate + $daRate;
                    
                    $basic = $payroll->basic_amount ?? 0;
                    $da = $payroll->da_amount ?? 0;
                    $otherCash = ($payroll->CA ?? 0) + ($payroll->Fooding ?? 0) + ($payroll->Misc ?? 0) + ($payroll->CEA ?? 0) + ($payroll->WashingAllowance ?? 0) + ($payroll->ProfessionalPursuits ?? 0);
                    $otHours = $payroll->ot_hours ?? 0;
                    $otAmount = $payroll->ot_amount ?? 0;
                    $dtAllow = $payroll->SpecialAllowance ?? 0;
                    $gross = round($basic + $da + $otherCash + $otAmount + $dtAllow);
                    
                    $pf = $payroll->pf_employee ?? 0;
                    $esic = $payroll->esic_employee ?? 0;
                    $prf = 0;
                    if (!empty($payroll->PRFTax) && ($payroll->PRFTax === true || $payroll->PRFTax === 1 || $payroll->PRFTax === '1')) {
                        $prf = 200;
                    }
                    $advanceDeduction = 0; // Assuming this field exists or is 0
                    $net = round($gross - $pf - $esic - $prf - $advanceDeduction);
                    
                    $totalBasic += $basic;
                    $totalDa += $da;
                    $totalAttn += $totalDays;
                    $totalGross += $gross;
                    $totalPF += $pf;
                    $totalESIC += $esic;
                    $totalAllowance += $dtAllow;
                    $totalNet += $net;
                @endphp
                <tr>
                    <td class="col-slim col-text">{{ $sl }}</td>
                    <td class="col-slim col-text">{{ $payroll->EmpId ?? '' }}</td>
                    <td class="text-left col-text">{{ $payroll->Name ?? '' }}</td>
                    <td class="text-left col-text">{{ $payroll->designation ?? '' }}</td>
                    <td class="col-day">{{ $present }}</td>
                    <td class="col-day">{{ $el }}</td>
                    <td class="col-day">{{ $cl }}</td>
                    <td class="col-day">{{ $fl }}</td>
                    <td class="col-day">{{ $totalDays }}</td>
                    <td class="text-right col-numeric">{{ number_format($totalRate, 2) }}</td>
                    <td class="text-right col-numeric">{{ number_format($basic, 2) }}</td>
                    <td class="text-right col-slim">{{ number_format($da, 2) }}</td>
                    <td class="text-right col-slim">{{ number_format($otherCash, 1) }}</td>
                    <td class="text-right col-slim">{{ $otHours > 0 ? number_format($otHours, 1) : '0.0' }}</td>
                    <td class="text-right col-numeric">{{ $otAmount > 0 ? number_format($otAmount, 1) : '0.0' }}</td>
                    <td class="text-right col-slim">{{ $dtAllow > 0 ? number_format($dtAllow, 1) : '0.0' }}</td>
                    <td class="text-right col-numeric">{{ number_format($gross, 1) }}</td>
                    <td class="text-right col-numeric">{{ number_format($pf, 1) }}</td>
                    <td class="text-right col-slim">{{ number_format($esic, 1) }}</td>
                    <td class="text-right col-slim">{{ number_format($prf, 1) }}</td>
                    <td class="text-right col-slim">{{ number_format($advanceDeduction, 1) }}</td>
                    <td class="text-right col-numeric">{{ number_format($net, 1) }}</td>
                    <td class="col-slim"></td>
                    <td class="col-slim"></td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <span class="footer-item">Basic : {{ number_format($totalBasic, 1) }}</span>
        <span class="footer-item">Da : {{ number_format($totalDa, 1) }}</span>
        <span class="footer-item">Total Attn : {{ $totalAttn }}</span>
        <span class="footer-item">Gross : {{ number_format($totalGross, 1) }}</span>
        <span class="footer-item">PF : {{ number_format($totalPF, 1) }}</span>
        <span class="footer-item">ESIC : {{ number_format($totalESIC, 1) }}</span>
        <span class="footer-item">Total Allowance : {{ number_format($totalAllowance, 1) }}</span>
        <span class="footer-item">Net Payment : {{ number_format($totalNet, 1) }}</span>
    </div>
</body>
</html>

