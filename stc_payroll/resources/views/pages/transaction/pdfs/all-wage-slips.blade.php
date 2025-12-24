<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Wage Slips - {{ $monthDisplay }}</title>
    <style>
        @page { 
            margin: 5mm;
            size: A4;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 9px; 
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .wage-slip-container {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 5mm;
            height: 90mm; /* Fixed height: A4 is 297mm, minus margins (10mm) = 287mm, divided by 3 ≈ 95mm, using 90mm for safety */
            min-height: 90mm;
            max-height: 90mm;
            box-sizing: border-box;
            page-break-inside: avoid;
            overflow: hidden;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        .header-left {
            font-weight: bold;
            font-size: 10px;
        }
        .header-center {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            flex: 1;
        }
        .header-right {
            font-weight: bold;
            font-size: 10px;
        }
        .content-row {
            display: flex;
            margin-bottom: 5px;
        }
        .content-col {
            flex: 1;
            padding: 0 5px;
            font-size: 8px;
        }
        .content-col:first-child {
            border-right: 1px solid #000;
        }
        .label {
            font-weight: bold;
            margin-bottom: 1px;
            font-size: 8px;
            line-height: 1.1;
        }
        .value {
            margin-bottom: 3px;
            font-size: 8px;
            line-height: 1.1;
        }
        .wage-details {
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 3px;
        }
        .wage-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 8px;
            line-height: 1.2;
        }
        .wage-item-label {
            font-weight: bold;
        }
        .wage-item-value {
            text-align: right;
        }
        .deductions {
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 3px;
        }
        .signature-area {
            margin-top: 8px;
            text-align: right;
            position: relative;
        }
        .stamp-area {
            position: relative;
            min-height: 50px;
            margin-top: 5px;
        }
        .stamp {
            position: absolute;
            right: 0;
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            text-align: center;
            padding: 5px;
            line-height: 1.1;
        }
        .signature-text {
            margin-top: 60px;
            font-size: 7px;
            text-align: right;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach($slipsData as $index => $slip)
        @php
            $employee = $slip['employee'];
            $payroll = $slip['payroll'];
            $rate = $slip['rate'];
            // Add page break before every 4th slip (after every 3 slips)
            $shouldPageBreak = ($index > 0 && $index % 3 == 0);
        @endphp
        
        @if($shouldPageBreak)
            <div style="page-break-before: always; break-before: page;"></div>
        @endif
        
        <div class="wage-slip-container">
            <!-- Header -->
            <div class="header">
                <div class="header-left">FOR XV</div>
                <div class="header-center">WAGES SLIP</div>
                <div class="header-right">[See Rule 78(2)(B)]</div>
            </div>

            <!-- Main Content -->
            <div class="content-row">
                <!-- Left Column -->
                <div class="content-col">
                    <div class="label">Name & Address of Contractor</div>
                    <div class="value">{{ $site ? $site->name : 'GLOBAL AC SYSTEM' }}</div>
                    <div class="value">502/A Jawaharnagar Road-17 Azadnagar Mango Jsr-832110</div>
                    
                    <div class="label" style="margin-top: 5px;">Nature & Location of Work:</div>
                    <div class="value"></div>
                    
                    <div class="label" style="margin-top: 3px;">Name of the work man:</div>
                    <div class="value">{{ $employee->Name ?? '' }}</div>
                    
                    <div class="label">Father Name:</div>
                    <div class="value">{{ $employee->Father ?? '' }}</div>
                    
                    <div class="label">Payment Month:</div>
                    <div class="value">{{ $monthDisplay }}</div>
                    
                    <div class="label">Workman No.:</div>
                    <div class="value">{{ $employee->EmpId ?? '' }}</div>
                </div>

                <!-- Right Column -->
                <div class="content-col">
                    <div class="label">Name and Address of Establishment in/under which Contract is Carried On</div>
                    <div class="value">{{ $site ? $site->name : 'VOLTAS LIMITED' }}</div>
                    
                    <div class="label" style="margin-top: 5px;">Name & Address of the Principal Employer</div>
                    <div class="value">JSR - TATA STEEL LTD</div>
                    
                    <div class="label" style="margin-top: 5px;">UAN No.:</div>
                    <div class="value">{{ $employee->Uan ?? '' }}</div>
                    
                    <div class="label">ESIC No.:</div>
                    <div class="value">{{ $employee->Esic ?? '' }}</div>
                    
                    <div class="label">A/C No.:</div>
                    <div class="value">{{ $employee->Ac ?? '' }}</div>
                </div>
            </div>

            <!-- Wage Details and Signature Section (Split in Half) -->
            <div class="content-row" style="margin-top: 5px; border-top: 1px solid #000; padding-top: 3px;">
                <!-- Left Half: Wage Calculation Details -->
                <div class="content-col">
                    <!-- Wage Calculation Details -->
                    <div class="wage-details" style="margin-top: 0; border-top: none; padding-top: 0;">
                        <div class="wage-item">
                            <span class="wage-item-label">1. No. of Days Worked :</span>
                            <span class="wage-item-value text-bold">{{ $slip['totalWorked'] }}</span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">2. Rate of Daily Wages:</span>
                            <span class="wage-item-value text-bold">{{ number_format($slip['dailyRate'], 2) }}</span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">3. Other Pay:</span>
                            <span class="wage-item-value text-bold">{{ number_format($slip['otherPay'], 0) }}</span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">4. Overtime :</span>
                            <span class="wage-item-value text-bold">Hrs - {{ $slip['otHours'] > 0 ? number_format($slip['otHours'], 0) : 0 }} Amt - {{ $slip['otAmount'] > 0 ? number_format($slip['otAmount'], 0) : 0 }}</span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">5. Amount of Wages:</span>
                            <span class="wage-item-value text-bold">{{ number_format($slip['amountOfWages'], 2) }}</span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">6. Gross Wages Payable:</span>
                            <span class="wage-item-value text-bold">{{ number_format($slip['grossWages'], 0) }}</span>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div class="deductions" style="margin-top: 5px; border-top: 1px solid #000; padding-top: 3px;">
                        <div class="wage-item">
                            <span class="wage-item-label">7. Deductions:</span>
                            <span class="wage-item-value text-bold">
                                PF - {{ number_format($slip['pfDeduction'], 0) }} 
                                ESIC - {{ number_format($slip['esicDeduction'], 0) }} 
                                PRF - {{ number_format($slip['prfDeduction'], 0) }}
                            </span>
                        </div>
                        <div class="wage-item">
                            <span class="wage-item-label">8. Net Amount Paid:</span>
                            <span class="wage-item-value text-bold">{{ number_format($slip['netAmount'], 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Half: Signature Area -->
                <div class="content-col">
                    <div class="signature-area" style="margin-top: 0;">
                        <div class="stamp-area">
                            <div class="stamp">
                                <div>
                                    {{ $site ? $site->name : 'GLOBAL AC SYSTEM' }}<br>
                                    JAMSHEDPUR
                                </div>
                            </div>
                        </div>
                        <div class="signature-text">Signature of the contractor or his representative</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>

