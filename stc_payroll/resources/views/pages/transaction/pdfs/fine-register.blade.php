<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fine Register - {{ $monthDisplay ?? '' }}</title>
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
        .underline { text-decoration: underline; }
        .small { font-size: 10px; }
        .mt-6 { margin-top: 6px; }
        .mt-10 { margin-top: 10px; }
        .row { display: flex; gap: 10px; }
        .col { flex: 1; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: middle; }
        th { font-size: 10px; }
        .nowrap { white-space: nowrap; }
        .twogrid{            
            display: grid;
            grid-template-columns: 50% 50%;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="bold">Annexure-B</div>
        <div class="bold">Form-XVI,XVI,XVIII &amp; I</div>
        <div class="bold">Appendix-2 (b)</div>
        <div class="bold underline" style="text-transform: uppercase;">
            Combined Register of Fines, Deductions for Damage or Loss and Advances
        </div>
        <div class="small">[See rule 77 (2) (d),78(d)]</div>
    </div>

    <div class="small mt-10">
        <div>Under Rule 21 (4) of Orissa Minimum Wages Rules, 1954</div>
        <div>Under Rule, 78 (d) (fine), 77 (22) (d) (dedu.), 77 (2) (d) (adv.) of Orissa Contract Labour (R &amp; A) Rules, 1975</div>
        <div>Under Rule 3 (1) (fine), 4 (deductions) and 17 (3) (advances) of Orissa Payment of Wages Rules, 1936</div>
        <div>Under Rule 52 (2) C of Orissa I.S.M.W (RE &amp; CS) Rules, 1980</div>
        <div>Under Rule-239 (1) (b) of Orissa Building other Construction Workers (RE &amp; CS) Rules, 2002</div>
        <div>Under Sec-18 Minimum Wages Act-1948</div>
        <div class="col right">
            <div class="nowrap">Month &amp; Year</div>
            <div class="bold">{{ $monthShort ?? ($monthDisplay ?? '') }}</div>
        </div>
    </div>
    <div class="twogrid">
        <div class="col mt-10 small">
            <div class="col">
                <div><span class="nowrap">Name &amp; Address Of the Contractor</span> <span class="bold" style="margin-left:10px;">{{ $company ? $company->name : 'SARA ENTERPRISES' }}</span></div>
                <div class="mt-6"><span class="nowrap">Nature of Work</span> <span class="bold" style="margin-left:10px;">{{ $site->natureofwork ?? 'HVAC AMC' }}</span></div>
            </div>
        </div>

        <div class="col mt-6 small">
            <div class="col">
                <div>
                    <span class="nowrap">Name &amp; Address of the Establishment in / <br>under which contract is carried on</span>
                    <span class="bold" style="margin-left:10px;">{{ $site ? $site->under_contract : 'VOLTAS LIMITED' }}</span>
                </div>
                <div class="mt-6">
                    <span class="nowrap">Name &amp; Address of Principal Employer</span>
                    <span class="bold" style="margin-left:10px;">{{ $site ? $site->name : 'NA' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <table>
            <thead>
                <tr>
                <td style="widtd: 45px;">Sl.No.</td>
                    <td style="widtd: 190px;">Name of tde Employee<br><span class="small">Fatders / Husbands Name</span></td>
                    <td style="widtd: 140px;">Designation<br><span class="small">Emp.no./Sl.No. in Register of Employee</span></td>
                    <td >Nature &amp; Date of Offence for which<br>Fine imposed</td>
                    <td >Date of Particu-<br>lars of Damage<br>/Loss Caused</td>
                    <td >Amount of Fine imposed / Dedu-<br>ction made</td>
                    <td >Amount of Adv-<br>ance made &amp;<br>Purpose tdereof</td>
                    <td style="widtd: 160px;">No. of Installments Granted for repayment of fines/ded./Advances</td>
                    <td style="widtd: 140px;">Wages Period and rate of<br>Wages payable</td>
                    <td colspan="2" style="widtd: 170px;">Date of Recovery of Fine/Deduction/Adv.<br><span class="small">First Inst&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Last Inst</span></td>
                    <td style="widtd: 110px;">Remarks</td>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td>
                            <div>{{ $r->name ?? '' }}</div>
                            <div class="small">{{ $r->father ?? '' }}</div>
                        </td>
                        <td class="center">
                            <div>{{ $r->designation ?? ($r->category ?? '') }}</div>
                            <div class="small">{{ $r->empid ?? '' }}</div>
                        </td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                        <td class="center bold">NIL</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="center" style="padding: 8px;">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-10 right small">
        <div style="margin-top: 30px;" class="bold">
            Signature of the Employer/Principal Employer/<br>
            Authorized signatory
        </div>
    </div>
</body>
</html>

