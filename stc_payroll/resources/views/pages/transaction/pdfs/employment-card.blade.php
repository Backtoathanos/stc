<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employment Card - {{ $monthYear ?? '' }}</title>
    <style>
        @page {
            margin: 8mm;
            size: A4 portrait;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.2;
        }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 4px 6px; vertical-align: top; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 10px; }
        .card {
            border: 1px solid #000;
            margin-bottom: 14px;
        }
        .no-border td { border: none; }
        .label { width: 55%; }
        .value { width: 45%; font-weight: bold; }

        .stamp-area {
            position: relative;
            height: 70px;
        }
        .stamp {
            position: absolute;
            right: 10px;
            top: 5px;
            width: 65px;
            height: 65px;
            border: 1px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7px;
            text-align: center;
            line-height: 1.1;
            padding: 4px;
        }
        .signature-text {
            position: absolute;
            right: 6px;
            bottom: 0;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
            break-before: page;
        }
    </style>
</head>
<body>
    @foreach($rows as $i => $r)
        @php
            $companyName = $company ? ($company->name ?? '') : '';
            $companyAddress = $company ? ($company->address ?? '') : '';

            $establishment = $site ? ($site->name ?? '') : '';
            $natureOfWork = $site ? ($site->natureofwork ?? '') : '';

            $empName = $r->name ?? '';
            $empId = $r->empid ?? '';
            $designation = $r->designation ?? ($r->category ?? '');

            $wageRate = ((float)($r->basic_rate ?? 0)) + ((float)($r->da_rate ?? 0));

            $doj = $r->doj ? \Carbon\Carbon::parse($r->doj) : null;
            $doe = $r->doe ? \Carbon\Carbon::parse($r->doe) : null;
            if ($doj && !$doe) {
                $doe = $doj->copy()->addYear()->subDay();
            }
            $tenure = ($doj ? $doj->format('d.m.Y') : '') . ($doj || $doe ? ' TO ' : '') . ($doe ? $doe->format('d.m.Y') : '');
        @endphp

        @if($i > 0 && $i % 2 === 0)
            <div class="page-break"></div>
        @endif

        <div class="card">
            <table>
                <tr>
                    <td colspan="2" class="center bold">FOR X</td>
                </tr>
                <tr>
                    <td colspan="2" class="center bold">[ See Rule 75 ]</td>
                </tr>
                <tr>
                    <td colspan="2" class="center bold">Employment Card</td>
                </tr>

                <tr>
                    <td class="label">Name &amp; Address of Contractor :</td>
                    <td class="value">
                        {{ $companyName ?: 'SARA ENTERPRISES' }}<br>
                        @if(!empty($companyAddress))
                            {!! nl2br(e($companyAddress)) !!}<br>
                        @else
                            Naaz Cottage Cross Road No-18 Jawahar Nagar<br>
                            Mango, Jamshedpur-832110<br>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Name &amp; Address of the Establishment<br>in under which contract is carried on</td>
                    <td class="value">{{ $establishment ?: 'NA' }}</td>
                </tr>
                <tr>
                    <td class="label">Nature of Work &amp; Location of Work</td>
                    <td class="value">{{ $natureOfWork ?: 'NA' }}</td>
                </tr>
                <tr>
                    <td class="label">1. Name of the Work Man</td>
                    <td class="value">{{ $empName }}</td>
                </tr>
                <tr>
                    <td class="label">2. Sl. No. of Register of Work Man Employed</td>
                    <td class="value">{{ $empId }}</td>
                </tr>
                <tr>
                    <td class="label">3. Nature of Employment / Designation</td>
                    <td class="value">{{ $designation ?: 'NA' }}</td>
                </tr>
                <tr>
                    <td class="label">4. Wages Rate (With Particular of Unit<br>in case of Piece Work)</td>
                    <td class="value">{{ number_format($wageRate, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">5. Wages Period</td>
                    <td class="value">Monthly</td>
                </tr>
                <tr>
                    <td class="label">6. Tenure Employment</td>
                    <td class="value">{{ $tenure }}</td>
                </tr>
                <tr>
                    <td class="label">7. Remarks</td>
                    <td class="value">
                        <div class="stamp-area">
                            <div class="">
                                <div>
                                    {{ $companyName ?: 'CONTRACTOR' }}
                                </div>
                            </div>
                            <div class="signature-text">    @php
                                    $signaturePath = public_path('dist/img/signature.jpeg');
                                    $signatureDataUri = null;
                                    if (file_exists($signaturePath)) {
                                        $ext = strtolower(pathinfo($signaturePath, PATHINFO_EXTENSION));
                                        $mime = $ext === 'png' ? 'image/png' : ($ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : null);
                                        if ($mime) {
                                            $signatureDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($signaturePath));
                                        }
                                    }
                                @endphp
                                @if(!empty($signatureDataUri))
                                    <div style="margin-top: 0;">
                                        <img src="{{ $signatureDataUri }}" alt="Signature" style="width: 140px; height: 50px;">
                                    </div>
                                @endif
                                Signature of Contractor</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>

