<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Overtime Register - {{ $monthDisplay ?? '' }}</title>
    <style>
        @page {
            margin: 8mm;
            size: A4 landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.2;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 10px; }
        .tiny { font-size: 9px; }
        .mt-8 { margin-top: 8px; }
        .mt-12 { margin-top: 12px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: middle; }
        th { font-size: 10px; font-weight: bold; }
        .nowrap { white-space: nowrap; }
        .wrap { white-space: normal; }

        .header-top {
            display: grid;
            grid-template-columns: 42% 16% 42%;
            align-items: start;
            margin-top: 6px;
        }
        .rules {
            font-size: 10px;
            line-height: 1.25;
        }

        .stamp-area {
            position: relative;
            min-height: 80px;
            margin-top: 8px;
        }
        .stamp {
            position: absolute;
            right: 0;
            width: 90px;
            height: 90px;
            border: 1px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            text-align: center;
            padding: 6px;
            line-height: 1.1;
        }
        .seal-text {
            margin-top: 92px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="center bold">Form-XIX <span style="float:right;" class="small">Appendix-2 (c)</span></div>
    <div class="center bold mt-8">COMBINED REGISTER OF OVERTIME WORKING AND</div>
    <div class="center bold">PAYMENT <span class="small">[See rule – 77 (2) (c)]</span></div>

    <div class="header-top mt-12">
        <div class="rules">
            <div class="bold">Rule 79 of Orissa Factories Rules, 1950 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;(N.B.: Rule 80 &amp; Form 11 may be annulled)</div>
            <div class="bold">Rule 25(2) of Orissa Minimum Wages Rules, 1954</div>
            <div class="bold">(e) of Orissa Contract Labour (R&amp;A) Rules, 1975</div>
            <div class="bold">Rule 12(4) &amp; Rule 15(3) of Orissa Shops &amp; Commercial Establishment Rules, 1956</div>
            <div class="bold">Rules 33(5) of Orissa B.C.W.(COE) Rules, 1969.</div>
            <div class="bold">Rule 37 of Orissa M.T. Workers Rules, 1966</div>
            <div class="bold">Rule 52(2)(a) of Orissa ISMW (RE &amp; CS) Rules, 1980</div>
            <div class="bold">Rule 239(1)(c) of Orissa Building and other Construction Workers<br>(Regulation of Employment &amp; Condition of Service) Rules, 2002</div>
        </div>
        <div class="center tiny">
            <div class="bold"></div>
        </div>
    </div>
        <div class="right rules">
            <div class="bold">Month &amp; Year&nbsp;&nbsp;&nbsp;{{ $monthShort ?? '' }}</div>
        </div>

    <div class="mt-12">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width:45px;">S.No.</th>
                    <th rowspan="2" style="width:260px;">Name of<br>Workman / Father / Husband's Name</th>
                    <th rowspan="2" style="width:60px;">Sex</th>
                    <th rowspan="2" style="width:120px;">Desig/<br>Dept</th>
                    <th rowspan="2" style="width:140px;">Emp No./Sl No.<br>in register of<br>Employees</th>
                    <th colspan="2" style="width:200px;">Particular of O.T<br>Worked</th>
                    <th rowspan="2" style="width:130px;">Normal Rate<br>Wages per<br>Day / Hours</th>
                    <th rowspan="2" style="width:140px;">Over time<br>Rate of Wages<br>Per Hours</th>
                    <th rowspan="2" style="width:120px;">Total Over<br>Time<br>Earnings</th>
                    <th rowspan="2" style="width:110px;">Signature<br>of the<br>Employees</th>
                    <th rowspan="2" style="width:120px;">Signature<br>of the<br>Paying Authority</th>
                </tr>
                <tr>
                    <th style="width:100px;">Date</th>
                    <th style="width:100px;">Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                    @php
                        $gender = isset($r->gender) ? strtoupper(substr((string)$r->gender, 0, 1)) : '';
                        $dates = is_array($r->ot_dates ?? null) ? $r->ot_dates : [];
                        $datesText = count($dates) ? implode(', ', $dates) : 'NIL';
                        $hoursTotal = (int)($r->ot_hours_total ?? 0);
                        $hoursText = $hoursTotal > 0 ? $hoursTotal : 'NIL';
                        $earn = (float)($r->ot_amount ?? 0);
                        $earnText = $earn > 0 ? number_format($earn, 0) : 'NIL';
                    @endphp
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td class="bold">{{ $r->name ?? '' }}</td>
                        <td class="center">{{ $gender ?: 'NIL' }}</td>
                        <td class="center">{{ $r->designation ?? 'NIL' }}</td>
                        <td class="center">{{ $r->empid ?? 'NIL' }}</td>
                        <td class="center tiny wrap">{{ $datesText }}</td>
                        <td class="center bold">{{ $hoursText }}</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">{{ $earnText }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="center" style="padding: 10px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="stamp-area">
        <div class="stamp">
            <div>
                {{ $company ? ($company->name ?? 'CONTRACTOR') : 'CONTRACTOR' }}<br>
                {{ $site ? ($site->name ?? '') : '' }}
            </div>
        </div>
        <div class="seal-text">Stamp &amp; Seal of the Contractor</div>
    </div>
</body>
</html>

