<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FORM 29 - {{ $monthDisplay }}</title>
    <style>
        @page { 
            margin: 5mm;
            size: A4 landscape;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 5px; 
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
            font-size: 10px;
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
            margin-top: 2px;
        }
        th, td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            font-size: 5px;
            line-height: 1.1;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 5px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 5px;
            font-size: 5px;
        }
        .footer-row {
            display: flex;
            margin-bottom: 2px;
        }
        .footer-label {
            font-weight: bold;
            min-width: 60px;
            margin-right: 10px;
        }
        .signature-line {
            margin-top: 10px;
            font-size: 5px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="form-header">
        <!-- Top Section: 3 columns - Contractor (left), FORM 29 (center), Establishment (right) -->
        <div class="header-section">
            <div class="header-col header-col-left">
                <div class="contractor-section">
                    <div class="contractor-label">Name and address of Contractor</div>
                    <div class="contractor-value">502/A Jawaharnagar Road-17 Azadnagar</div>
                    <div class="contractor-value">Mango Jsr-832110</div>
                </div>
            </div>
            <div class="header-col header-col-center">
                <div class="form-title">FORM 29</div>
                <div class="form-subtitle">Combined Muster Roll cum Register of Wages</div>
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
                        <span class="info-value">{{ $site ? $site->name : 'VOLTAS LIMITED' }}</span>
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
                        <span class="info-value">HVAC PROJECT</span><br>
                        <span class="info-label">Work Order No</span>
                        <span class="info-value"></span>
                    </div>
                </div>
            </div>
            <div class="header-col header-col-center">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Month :</span>
                        @php
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear ?? date('Y-m'));
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
                        <span class="info-value">JSR - TATA STEEL LTD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Sl</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Sex</th>
                <th rowspan="2">DOB</th>
                <th rowspan="2">Emp No</th>
                <th rowspan="2">Desig</th>
                <th rowspan="2">DOJ</th>
                <th rowspan="2">ESIC</th>
                <th rowspan="2">PF</th>
                <th rowspan="2">UAN</th>
                <th colspan="31">Attendance</th>
                <th rowspan="2">Pay Days</th>
                <th rowspan="2">Rate</th>
                <th rowspan="2">Basic</th>
                <th rowspan="2">DA</th>
                <th rowspan="2">HRA</th>
                <th rowspan="2">Con</th>
                <th rowspan="2">Med</th>
                <th rowspan="2">Wash</th>
                <th rowspan="2">Att Allow</th>
                <th rowspan="2">Sp Allow</th>
                <th rowspan="2">OT</th>
                <th rowspan="2">Misc</th>
                <th rowspan="2">Other</th>
                <th rowspan="2">Gross</th>
                <th rowspan="2">ESIC</th>
                <th rowspan="2">PF</th>
                <th rowspan="2">Soc Ins</th>
                <th rowspan="2">Adv</th>
                <th rowspan="2">PT</th>
                <th rowspan="2">TDS</th>
                <th rowspan="2">Fine</th>
                <th rowspan="2">Damage</th>
                <th rowspan="2">Other</th>
                <th rowspan="2">Total Ded</th>
                <th rowspan="2">Net Pay</th>
                <th rowspan="2">Pay Date</th>
                <th rowspan="2">Sign</th>
            </tr>
            <tr>
                @for($day = 1; $day <= 31; $day++)
                    <th>{{ $day }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1;
                $totalDays = 0;
                $totalBasic = 0;
                $totalDA = 0;
                $totalGross = 0;
                $totalPF = 0;
                $totalESIC = 0;
                $totalNet = 0;
            @endphp
            @foreach($attendances as $attendance)
                @php
                    $payroll = $payrolls->get($attendance->aadhar);
                    $overtime = $overtimes->get($attendance->aadhar);
                    
                    // Calculate present days
                    $present = 0;
                    $payable = 0;
                    $ot = 0;
                    $nh = 0;
                    $l = 0;
                    
                    if ($attendance) {
                        for ($day = 1; $day <= 31; $day++) {
                            $dayValue = $attendance->{'day_' . $day} ?? '';
                            if ($dayValue === 'P') {
                                $present++;
                                $payable++;
                            } elseif ($dayValue === 'O') {
                                $l++;
                            }
                        }
                    }
                    
                    // Count NH days
                    if (!empty($nhDays) && $monthYear) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                        $daysInMonth = $date->daysInMonth;
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $checkDate = $monthYear . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                            if (in_array($checkDate, $nhDays)) {
                                $nh++;
                            }
                        }
                    }
                    
                    $totalWorked = $present + $nh + $l;
                    
                    // Wage calculations
                    $basic = $payroll ? ($payroll->basic_amount ?? 0) : 0;
                    $da = $payroll ? ($payroll->da_amount ?? 0) : 0;
                    $hra = $attendance->hra ?? 0;
                    $con = $attendance->CA ?? 0;
                    $med = $attendance->Madical ?? 0;
                    $wash = $attendance->WashingAllowance ?? 0;
                    $attAllow = $attendance->AttendAllow ? ($totalWorked * 0.224) : 0; // Approximate calculation
                    $spAllow = $attendance->SpecialAllowance ?? 0;
                    $otAmount = $payroll ? ($payroll->ot_amount ?? 0) : 0;
                    $misc = $attendance->Misc ?? 0;
                    $other = 0;
                    $gross = round($basic + $da + $hra + $con + $med + $wash + $attAllow + $spAllow + $otAmount + $misc + $other);
                    
                    // Deductions
                    $esicDed = $payroll ? ($payroll->esic_employee ?? 0) : 0;
                    $pfDed = $payroll ? ($payroll->pf_employee ?? 0) : 0;
                    $socIns = 0;
                    $adv = 0;
                    $pt = 0;
                    $tds = 0;
                    $fine = 0;
                    $damage = 0;
                    $otherDed = 0;
                    $totalDed = $esicDed + $pfDed + $socIns + $adv + $pt + $tds + $fine + $damage + $otherDed;
                    $netPay = round($gross - $totalDed);
                    
                    // Rate calculation
                    $rate = $payroll ? (($payroll->basic_rate ?? 0) + ($payroll->da_rate ?? 0)) : 0;
                    
                    // Format dates
                    $dob = $attendance->Dob ? \Carbon\Carbon::parse($attendance->Dob)->format('M d, Y') : '';
                    $doj = $attendance->Doj ? \Carbon\Carbon::parse($attendance->Doj)->format('M d, Y') : '';
                    
                    // Totals
                    $totalDays += $totalWorked;
                    $totalBasic += $basic;
                    $totalDA += $da;
                    $totalGross += $gross;
                    $totalPF += $pfDed;
                    $totalESIC += $esicDed;
                    $totalNet += $netPay;
                @endphp
                <tr>
                    <td>{{ $sl }}</td>
                    <td class="text-left">{{ $attendance->employee_name ?? '' }}</td>
                    <td>{{ strtoupper(substr($attendance->Gender ?? 'MALE', 0, 1)) }}</td>
                    <td class="text-left">{{ $dob }}</td>
                    <td>{{ $attendance->EmpId ?? '' }}</td>
                    <td class="text-left">{{ strtoupper($attendance->designation ?? '') }}</td>
                    <td class="text-left">{{ $doj }}</td>
                    <td>{{ $attendance->Esic ?? '0' }}</td>
                    <td>{{ $attendance->PfApplicable ? ($attendance->Uan ?? '0') : '0' }}</td>
                    <td>{{ $attendance->Uan ?? '' }}</td>
                    @for($day = 1; $day <= 31; $day++)
                        @php
                            $dayValue = $attendance->{'day_' . $day} ?? '';
                            $otValue = $overtime ? ($overtime->{'day_' . $day} ?? 0) : 0;
                            $displayValue = $dayValue;
                            if ($otValue > 0 && $dayValue) {
                                $displayValue .= '+' . $otValue;
                            }
                        @endphp
                        <td>{{ $displayValue }}</td>
                    @endfor
                    <td>{{ $totalWorked }}</td>
                    <td>{{ number_format($rate, 2) }}</td>
                    <td>{{ number_format($basic, 2) }}</td>
                    <td>{{ number_format($da, 2) }}</td>
                    <td>{{ number_format($hra, 2) }}</td>
                    <td>{{ number_format($con, 2) }}</td>
                    <td>{{ number_format($med, 2) }}</td>
                    <td>{{ number_format($wash, 2) }}</td>
                    <td>{{ number_format($attAllow, 1) }}</td>
                    <td>{{ number_format($spAllow, 2) }}</td>
                    <td>{{ number_format($otAmount, 2) }}</td>
                    <td>{{ number_format($misc, 2) }}</td>
                    <td>{{ number_format($other, 2) }}</td>
                    <td>{{ number_format($gross, 2) }}</td>
                    <td>{{ number_format($esicDed, 2) }}</td>
                    <td>{{ number_format($pfDed, 2) }}</td>
                    <td>{{ number_format($socIns, 2) }}</td>
                    <td>{{ number_format($adv, 2) }}</td>
                    <td>{{ number_format($pt, 2) }}</td>
                    <td>{{ number_format($tds, 2) }}</td>
                    <td>{{ number_format($fine, 2) }}</td>
                    <td>{{ number_format($damage, 2) }}</td>
                    <td>{{ number_format($otherDed, 2) }}</td>
                    <td>{{ number_format($totalDed, 2) }}</td>
                    <td>{{ number_format($netPay, 2) }}</td>
                    <td>.</td>
                    <td>.</td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="footer" style="margin-top: 5px; font-size: 12px;">
        <div class="footer-row" style="display: flex; margin-bottom: 2px;">
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-right: 10px;">Total Basic:</span>
            <span class="footer-value">{{ number_format($totalBasic, 2) }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total DA:</span>
            <span class="footer-value">{{ number_format($totalDA, 2) }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total Days:</span>
            <span class="footer-value">{{ $totalDays }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total Gross:</span>
            <span class="footer-value">{{ number_format($totalGross, 2) }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total PF:</span>
            <span class="footer-value">{{ number_format($totalPF, 2) }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total ESIC:</span>
            <span class="footer-value">{{ number_format($totalESIC, 2) }}</span>
            <span class="footer-label" style="font-weight: bold; min-width: 60px; margin-left: 20px; margin-right: 10px;">Total Net:</span>
            <span class="footer-value">{{ number_format($totalNet, 2) }}</span>
        </div>
    </div>

    <div class="signature-line" style="font-size: 12px;">
        Signature of the contractor or his representative
    </div>
</body>
</html>
