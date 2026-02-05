<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Report (Odissa) - {{ $monthDisplay }}</title>
    <style>
        @page { 
            margin: 3mm;
            size: A4 landscape;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 4px; 
            margin: 0;
            padding: 0;
        }
        .form-header {
            font-size: 8px;
            line-height: 1.3;
        }

        .bold {
            font-weight: bold;
        }

        .label {
            font-weight: normal;
        }

        .value {
            font-weight: bold;
        }

        .mt {
            margin-top: 3px;
        }

        /* TOP SECTION */
        .header-top {
            display: grid;
            grid-template-columns: 20% 40% 20% 20%;
        }

        /* BOTTOM SECTION */
        .header-bottom {
            display: grid;
            grid-template-columns: 40% 30% 30%;
            margin-top: 4px;
        }

        .header-bottom .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }
        th, td {
            border: 0.5px solid #000;
            padding: 0.3px 0.5px;
            text-align: center;
            font-size: 8px;
            line-height: 1.0;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 6px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            display: grid;
            grid-template-columns: 50% 35% 15%;
            /* display: flex;
            justify-content: space-between;
            font-size: 8px;
            margin-top: 5px; */
        }

        /* .footer-left {
            width: 40%;
        } */

        .footer-left .row {
            display: grid;
            grid-template-columns: 20% 20% 30% 30%;
            font-size: 8px;
            /* display: flex;
            flex-wrap: nowrap;
            gap: 14px;
            margin-bottom: 2px; */
        }

        .footer-left .row div {
            white-space: nowrap;
        }

        .footer-right {
            width: 10%;
            text-align: left;
            line-height: 1.4;
        }

        .footer-right div {
            white-space: nowrap;
        }

        /* .footer-signature{
            width: 30%;

        } */

        .signature-line {
            margin-top: 60px;
            font-size: 8px;
            text-align: right;
        }
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
            transform: rotate(180deg);
            height: 70px;
            padding: 3px;
            text-align: left;
        }
        .day-summary {
            display: grid;
            grid-template-columns: auto 40px;
            column-gap: 8px;
            font-size: 8px;
            line-height: 1.4;
        }

        .day-summary div {
            display: contents;
        }

        .day-summary b {
            white-space: nowrap;
        }

        .day-summary span {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="form-header">
        <div><b>FORM 29</b></div>
        <div class="bold">COMBINED MUSTER ROLL-CUM-REGISTER OF WAGES</div>
        <div class="header-top">
            <div class="header-left">
                <div class="mt">
                    <span class="label">Name & Address of Factory Establishment</span>
                </div>
            </div>

            <div class="header-after-left">
                <div class="mt">
                    <span class="value">
                        {{ $company ? $company->name : 'SARA ENTERPRISES' }}<br>
                        {{ $company ? $company->address : 'Naaz Cottage Cross Road No-18 Jawahar Nagar' }}
                        {{ $company ? $company->city_pin : 'Mango, Jamshedpur-832110' }}
                    </span>
                </div>
            </div>

            <div class="header-before-right">
                <div class="mt">
                    <span class="label">Name & Address of Contractor :</span><br>
                    <span class="value">{{ $site ? $site->under_contract : 'VOLTAS LIMITED' }}</span>
                </div>
            </div>

            <div class="header-right">
                <div class="mt">
                    <span class="label">Name & Address of Principal Employer</span><br>
                    <span class="value">{{ $site ? $site->name : 'DALMIA CEMENT-RAJGANGPUR' }}</span>
                </div>
            </div>
        </div>

        <div class="header-bottom">
            <div>
                <span class="label">Nature of Work</span>
                <span class="value">{{ $site->natureofwork ?? 'HVAC AMC' }}</span>
            </div>
        </div>

        <div class="header-bottom">
            <div>
                <span class="label">Wages Period - Monthly</span>
                @php
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear ?? date('Y-m'));
                @endphp
                <span class="value">{{ strtoupper($date->format('F')) }}</span>
                &nbsp;&nbsp;
                <span class="value">{{ $date->format('Y') }}</span>
            </div>
        </div>
    </div>


    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:10px;vertical-align: bottom;">Sl. No.</th>
                <th rowspan="2" style="width:50px;vertical-align: bottom;">Name Of Workman</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Sex(M/ F )</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Date of Birth</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Employees SL No. in <br> Register of Employees</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Designation / Department</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Date of Joining</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">ESI IP No</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">PF No</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">UAN</th>
                <th colspan="31" style="height:35px">Attendance Sheet (Unit of Work Done)</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">No. of Payable Days <br>Total Work <br>Name of H & FH for which </th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Wages Rate</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Basic Wages</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">DA / VDA</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">HRA</th>
                <th rowspan="2" class="vertical-text" style="width:1px;">Conveyence Allowance</th>
                <th rowspan="2" class="vertical-text" style="width:1px;">Medical Allowance</th>
                <th rowspan="2" class="vertical-text" style="width:1px;">Washing Allowance</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Attn. Allowance/Bonus</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Spl. Allowance</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">O.T.Wages</th>
                <th rowspan="2" class="vertical-text" style="width:1px;">Misc. Earnings</th>
                <th rowspan="2" class="vertical-text" style="width:1px;">Others</th>
                <th rowspan="2" style="width:10px;vertical-align: bottom;">Gross Total</th>
                <th colspan="11" style="height:35px">DEDUCTION</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Net Payable</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Date of Payment</th>
                <th rowspan="2" class="vertical-text" style="width:10px;">Signature / Thumb</th>
            </tr>
            <tr>
                @for($day = 1; $day <= 31; $day++)
                    <th style="width:5px;text-align: left;vertical-align: bottom;">{{ $day }}</th>
                @endfor
                <th class="vertical-text" style="width:10px;">ESI</th>
                <th class="vertical-text" style="width:10px;">PF</th>
                <th class="vertical-text" style="width:1px;">Socy.</th>
                <th class="vertical-text" style="width:1px;">Insurance</th>
                <th class="vertical-text" style="width:1px;">Sal.Adv</th>
                <th class="vertical-text" style="width:10px;">PT</th>
                <th class="vertical-text" style="width:1px;">TDS</th>
                <th class="vertical-text" style="width:1px;">Fine</th>
                <th class="vertical-text" style="width:1px;">Damage & Loss</th>
                <th class="vertical-text" style="width:1px;">Others</th>
                <th class="vertical-text" style="width:10px;">Total Deduction</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1;
                $totalDays = 0;
                $totalBasic = 0;
                $totalDA = 0;
                $totalHRA = 0;
                $totalConveyence = 0;
                $totalMedical = 0;
                $totalWashing = 0;
                $totalAttendance = 0;
                $totalSplAllowance = 0;
                $totalOT = 0;
                $totalMisc = 0;
                $totalGross = 0;
                $totalESIC = 0;
                $totalPF = 0;
                $totalSociety = 0;
                $totalAdv = 0;
                $totalPT = 0;
                $totalTDS = 0;
                $totalFine = 0;
                $totalOtherDed = 0;
                $totalDeduction = 0;
                $totalDeductions = 0;
                $totalNetPayment = 0;
                $totalflday = 0;
                $totalclday = 0;
                $totalelday = 0;
                $totalnhday = 0;
                $totalmanday = 0;
                $totalpresentday = 0;
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
                    $fl = 0;
                    $cl = 0;
                    $el = 0; // EL/PL stored as E
                    
                    if ($attendance) {
                        for ($day = 1; $day <= 31; $day++) {
                            $dayValue = $attendance->{'day_' . $day} ?? '';
                            if ($dayValue === 'P') {
                                $present++;
                                $payable++;
                            } elseif ($dayValue === 'O') {
                                $l++;
                            } elseif ($dayValue === 'F') {
                                $fl++;
                            } elseif ($dayValue === 'N') {
                                $nh++;
                            } elseif ($dayValue === 'C' || $dayValue === 'CL') {
                                $cl++;
                            } elseif ($dayValue === 'E' || $dayValue === 'EL' || $dayValue === 'PL') {
                                $el++;
                            }
                        }
                    }
                    
                    // Count NH days from holiday records
                    $nhHoliday = 0;
                    if (!empty($holidayRecords) && $monthYear) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                        $daysInMonth = $date->daysInMonth;
                        $nhDates = $holidayRecords->where('leave_type', 'NH')->pluck('date')->toArray();
                        foreach ($nhDates as $nhDate) {
                            $day = \Carbon\Carbon::parse($nhDate)->day;
                            $nhHoliday++;
                        }
                    }
                    
                    $nh += $nhHoliday;
                    
                    // Total worked = Present + Festival Leave + National Holiday
                    $totalWorked = $present + $fl + $nh + $cl + $el;
                    $totalmanday += $totalWorked;
                    // Accumulate day totals
                    $totalflday += $fl;
                    $totalclday += $cl;
                    $totalelday += $el;
                    $totalnhday += $nh;
                    
                    // Wage calculations
                    $basic = $payroll ? ($payroll->basic_amount ?? 0) : 0;
                    $da = $payroll ? ($payroll->da_amount ?? 0) : 0;
                    $hra = $payroll ? ($payroll->hra ?? 0) : 0;
                    $conveyence = $payroll ? ($payroll->conveyence ?? 0) : 0;
                    $medical = $payroll ? ($payroll->medical ?? 0) : 0;
                    $washing = $payroll ? ($payroll->washing ?? 0) : 0;
                    $attndAllowance = $payroll ? ($payroll->attendance_allowance ?? 0) : 0;
                    $splAllowance = ($attendance->SpecialAllowance ?? 0) > 0 ? (($attendance->SpecialAllowance / 26) * $totalWorked) : 0;
                    $otAmount = $payroll ? ($payroll->ot_amount ?? 0) : 0;
                    $misc = $payroll ? ($payroll->misc_earnings ?? 0) : 0;
                    $others = 0;
                    
                    $gross = round($basic + $da + $hra + $conveyence + $medical + $washing + $attndAllowance + $splAllowance + $otAmount + $misc + $others);
                    
                    // Deductions
                    $esicDed = $payroll ? ($payroll->esic_employee ?? 0) : 0;
                    $pfDed = $payroll ? ($payroll->pf_employee ?? 0) : 0;
                    $society = 0;
                    $insurance = 0;
                    $damageadloss = 0;
                    $advDed = $payroll ? ($payroll->advance ?? 0) : 0;
                    $ptDed = $payroll ? ($payroll->pt ?? 0) : 0;
                    // If gross is more than 13304 and less than 24999: PRF Tax = 125
                    if ($gross > 13304 && $gross < 24999) {
                        $ptDed = 125;
                    }
                    
                    // If gross is more than 25000 and less than 30000: PRF Tax = 200
                    if ($gross >= 25000 && $gross < 30000) {
                        $ptDed = 200;
                    }
                    $tdsDed = 0;
                    $fineDed = 0;
                    $otherDed = 0;
                    $totalDed = $esicDed + $pfDed + $society + $insurance + $advDed + $ptDed + $tdsDed + $fineDed + $otherDed;
                    $totalDedshow = $esicDed + $pfDed + $society + $insurance + $advDed + 0 + $tdsDed + $fineDed + $otherDed;
                    $netPayment = round($gross - $totalDed);
                    
                    // Wage rate calculation
                    $wageRate = $payroll ? (($payroll->basic_rate ?? 0) + ($payroll->da_rate ?? 0)) : 0;
                    
                    // Calculated age and DOJ
                    $dobValue = $attendance->Dob ? \Carbon\Carbon::parse($attendance->Dob) : null;
                    $ageValue = $dobValue ? $dobValue->age : '';
                    $dojValue = $attendance->Doj ? \Carbon\Carbon::parse($attendance->Doj)->format('d-m-Y') : '';
                    $dobFormatted = $dobValue ? $dobValue->format('d-m-Y') : '';
                    
                    // Totals
                    $totalDays += $totalWorked;
                    $totalBasic += $basic;
                    $totalDA += $da;
                    $totalHRA += $hra;
                    $totalConveyence += $conveyence;
                    $totalMedical += $medical;
                    $totalWashing += $washing;
                    $totalAttendance += $attndAllowance;
                    $totalSplAllowance += $splAllowance;
                    $totalOT += $otAmount;
                    $totalMisc += $misc;
                    $totalGross += $gross;
                    $totalESIC += $esicDed;
                    $totalPF += $pfDed;
                    $totalSociety += $society;
                    $totalAdv += $advDed;
                    $totalPT += $ptDed;
                    $totalTDS += $tdsDed;
                    $totalFine += $fineDed;
                    $totalOtherDed += $otherDed;
                    $totalDeduction += $totalDed;
                    $totalDeductions+= $totalDedshow;
                    $totalNetPayment += $netPayment;
                    $totalpresentday += $present;
                @endphp
                <tr style="height:50px;">
                    <td>{{ $sl }}</td>
                    <td class="text-left">{{ $attendance->employee_name ?? '' }}</td>
                    <td>{{ $attendance->Gender ? substr($attendance->Gender, 0, 1) : '' }}</td>
                    <td>{{ $dobFormatted }}</td>
                    <td></td>
                    <td>{{ $attendance->Skill ?? '' }}</td>
                    <td>{{ $dojValue }}</td>
                    <td>{{ $attendance->Esic ?? '' }}</td>
                    <td>{{ $attendance->Uan ?? '' }}</td>
                    <td>{{ $attendance->Uan ?? '' }}</td>
                    @for($day = 1; $day <= 31; $day++)
                        @php
                            $dayValue = $attendance->{'day_' . $day} ?? '';
                            $otValue = $overtime ? ($overtime->{'day_' . $day} ?? 0) : 0;
                            $displayValue = $dayValue;
                            if ($otValue > 0 && $dayValue) {
                                $displayValue .= '+' . number_format($otValue, 0);
                            }
                        @endphp
                        <td>{{ $displayValue }}</td>
                    @endfor
                    <td class="text-right" style="font-size: 6px;padding-right:3px;">P:{{ $present }}<br>CL:{{ $cl }}<br>PL:{{ $el }}<br>FL:{{ $fl }}<br>NH:{{ $nh }}<br>Total:{{ $totalWorked }}</td>
                    <td class="text-right">{{ round($wageRate, 0) }}</td>
                    <td class="text-right">{{ round($basic, 0) }}</td>
                    <td class="text-right">{{ round($da, 0) }}</td>
                    <td class="text-right">{{ round($hra, 0) }}</td>
                    <td class="text-right">{{ round($conveyence, 0) }}</td>
                    <td class="text-right">{{ round($medical, 0) }}</td>
                    <td class="text-right">{{ round($washing, 0) }}</td>
                    <td class="text-right">{{ round($attndAllowance, 0) }}</td>
                    <td class="text-right">{{ round($splAllowance, 0) }}</td>
                    <td class="text-right">{{ round($otAmount, 0) }}</td>
                    <td class="text-right">{{ round($misc, 0) }}</td>
                    <td class="text-right">{{ round($others, 0) }}</td>
                    <td class="text-right">{{ round($gross, 0) }}</td>
                    <td class="text-right">{{ round($esicDed, 0) }}</td>
                    <td class="text-right">{{ round($pfDed, 0) }}</td>
                    <td class="text-right">{{ round($society, 0) }}</td>
                    <td class="text-right">{{ round($insurance, 0) }}</td>
                    <td class="text-right">{{ round($advDed, 0) }}</td>
                    <td class="text-right">{{ round($ptDed, 0) }}</td>
                    <td class="text-right">{{ round($tdsDed, 0) }}</td>
                    <td class="text-right">{{ round($fineDed, 0) }}</td>
                    <td class="text-right">{{ round($damageadloss, 0) }}</td>
                    <td class="text-right">{{ round($otherDed, 0) }}</td>
                    <td class="text-right">{{ round($totalDedshow, 0) }}</td>
                    <td class="text-right">{{ round($netPayment, 0) }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="height:25px;font-weight: bold;">
                <td colspan="10" class="text-right">TOTAL:</td>
                <td colspan="31"></td>
                <td class="text-right">{{ $totalDays }}</td>
                <td class="text-right">0</td>
                <td class="text-right">{{ round($totalBasic, 0) }}</td>
                <td class="text-right">{{ round($totalDA, 0) }}</td>
                <td class="text-right">{{ round($totalHRA, 0) }}</td>
                <td class="text-right">{{ round($totalConveyence, 0) }}</td>
                <td class="text-right">{{ round($totalMedical, 0) }}</td>
                <td class="text-right">{{ round($totalWashing, 0) }}</td>
                <td class="text-right">{{ round($totalAttendance, 0) }}</td>
                <td class="text-right">{{ round($totalSplAllowance, 0) }}</td>
                <td class="text-right">{{ round($totalOT, 0) }}</td>
                <td class="text-right">{{ round($totalMisc, 0) }}</td>
                <td class="text-right">0</td>
                <td class="text-right">{{ round($totalGross, 0) }}</td>
                <td class="text-right">{{ round($totalESIC, 0) }}</td>
                <td class="text-right">{{ round($totalPF, 0) }}</td>
                <td class="text-right">{{ round($totalSociety, 0) }}</td>
                <td class="text-right">{{ round($totalAdv, 0) }}</td>
                <td class="text-right">0</td>
                <td class="text-right">{{ round($totalPT, 0) }}</td>
                <td class="text-right">{{ round($totalTDS, 0) }}</td>
                <td class="text-right">{{ round($totalFine, 0) }}</td>
                <td class="text-right">{{ round($totalOtherDed, 0) }}</td>
                <td class="text-right">0</td>
                <td class="text-right">{{ round($totalDeductions, 0) }}</td>
                <td class="text-right">{{ round($totalNetPayment, 0) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="footer-left">
            <div class="row">
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total Basic :</b> <span style="margin-right: 15px;">{{ number_format(round($totalBasic,0),2) }}</span> </div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total DA :</b> <span style="margin-right: 15px;">{{ number_format(round($totalDA,0),2) }}</span> </div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total Att. Alw : </b> <span style="margin-right: 15px;">{{ number_format(round($totalAttendance,0),2) }}</span></div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total Spl. Allowance : </b> <span style="margin-right: 15px;">{{ number_format(round($totalSplAllowance,0),2) }}</span></div>
            </div>

            <div class="row">
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total ESI Amt :</b> <span style="margin-right: 15px;">{{ number_format(round($totalESIC,0),2) }}</span> </div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total PF :</b> <span style="margin-right: 15px;">{{ number_format(round($totalPF,0),2) }}</span> </div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Total Deduction : </b> <span style="margin-right: 15px;">{{ number_format(round($totalDeductions,0),2) }}</span></div>
                <div style="display:flex; justify-content: space-between; width: 100%;"> <b>Net Payment : </b> <span style="margin-right: 15px;">{{ number_format(round($totalNetPayment,0),2) }}</span></div>
            </div>
        </div>

        <div class="footer-right">
            <div class="day-summary">
                <div><b>Total Gross Payment :</b><span>{{ number_format(round($totalGross,0),2) }}</span></div>
                <div><b>Total Days Worked :</b><span>{{ $totalpresentday }}</span></div>
                <div><b>CL Day :</b><span>{{ $totalclday }}</span></div>
                <div><b>EL Day :</b><span>{{ $totalelday }}</span></div>
                <div><b>FL Day :</b><span>{{ $totalflday }}</span></div>
                <div><b>NH Day :</b><span>{{ $totalnhday }}</span></div>
                <div><b>Total Man Day :</b><span>{{ $totalmanday }}</span></div>
            </div>
        </div>

        <div class="footer-signature">
            <div class="signature-line" style="font-size: 8px;">
                Signature of the contractor or <br>his representative
            </div>
        </div>
    </div>


    
</body>
</html>
