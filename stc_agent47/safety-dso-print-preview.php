<?php
session_start();
include "../MCU/db.php";
require_once __DIR__ . '/../MCU/product_image_url.php';

function dso_fmt_date($d){
    if(!$d || $d === '0000-00-00') return '';
    $t = strtotime($d);
    return $t ? date('d-m-Y', $t) : '';
}

function dso_img($stored){
    if(trim((string)$stored) === '') return '';
    return htmlspecialchars(stc_safety_image_url($stored), ENT_QUOTES, 'UTF-8');
}

$from_date = isset($_GET['from_date']) ? trim((string)$_GET['from_date']) : date('Y-m-01');
$to_date   = isset($_GET['to_date']) ? trim((string)$_GET['to_date']) : date('Y-m-d');

if($from_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date)){
    $from_date = date('Y-m-01');
}
if($to_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)){
    $to_date = date('Y-m-d');
}
if($from_date !== '' && $to_date !== '' && $from_date > $to_date){
    $tmp = $from_date;
    $from_date = $to_date;
    $to_date = $tmp;
}

$where = "WHERE 1=1";
if($from_date !== ''){
    $from_esc = mysqli_real_escape_string($con, $from_date);
    $where .= " AND d.observation_date >= '".$from_esc."'";
}
if($to_date !== ''){
    $to_esc = mysqli_real_escape_string($con, $to_date);
    $where .= " AND d.observation_date <= '".$to_esc."'";
}
if(!empty($_SESSION['stc_agent_sub_id'])){
    $sid = mysqli_real_escape_string($con, (string)$_SESSION['stc_agent_sub_id']);
    $where .= " AND d.created_by = '".$sid."'";
}

$rows = [];
$qry = mysqli_query($con, "
    SELECT d.*, s.stc_cust_pro_supervisor_fullname
    FROM `stc_safety_dso` d
    LEFT JOIN `stc_cust_pro_supervisor` s ON s.stc_cust_pro_supervisor_id = d.created_by
    ".$where."
    ORDER BY d.observation_date ASC, d.id ASC
");
if($qry){
    while($r = mysqli_fetch_assoc($qry)){
        $rows[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Safety Observation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 11px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 4px 6px; vertical-align: top; text-align: center; }
        table { border-collapse: collapse; width: 100%; }
        .red-head { color: red; font-weight: bold; }
        .img-cell img { max-width: 120px; max-height: 100px; }
        .filter-bar { background: #f8f9fa; border: 1px solid #dee2e6; padding: 12px; border-radius: 6px; }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 10px; }
        }
    </style>
</head>
<body>
<div class="container-fluid my-3">
    <div class="row mb-3 no-print">
        <div class="col-12">
            <form method="get" class="filter-bar row g-2 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1"><b>From Date</b></label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($from_date); ?>">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1"><b>To Date</b></label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="<?php echo htmlspecialchars($to_date); ?>">
                </div>
                <div class="col-md-3 col-sm-6">
                    <button type="submit" class="btn btn-success btn-sm">Show Records</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Print</button>
                </div>
                <div class="col-md-3 col-sm-6 text-md-end">
                    <small class="text-muted"><?php echo count($rows); ?> record(s) found</small>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-2 align-items-center">
        <div class="col-3">
            <img style="width:90px;" src="images/globallogo.jpg" alt="Logo">
        </div>
        <div class="col-6 text-center">
            <h4 style="margin:0;"><b>DAILY SAFETY OBSERVATION</b></h4>
            <?php if($from_date !== '' || $to_date !== ''){ ?>
                <p style="margin:4px 0 0;">
                    <small>
                        Period:
                        <?php echo htmlspecialchars(dso_fmt_date($from_date)); ?>
                        to
                        <?php echo htmlspecialchars(dso_fmt_date($to_date)); ?>
                    </small>
                </p>
            <?php } ?>
        </div>
        <div class="col-3"></div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Date</th>
                <th>Area/Location</th>
                <th>Observation Details</th>
                <th>Observation Type</th>
                <th>Immediate Action Taken</th>
                <th>Responsible Person</th>
                <th>Target date</th>
                <th>Closure date</th>
                <th>Compliance Status</th>
                <th>Verified By</th>
                <th>Reviewed By</th>
                <th class="red-head">BEFORE</th>
                <th class="red-head">AFTER</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($rows) > 0){ ?>
                <?php $sl = 0; foreach($rows as $row){ $sl++; ?>
                    <tr>
                        <td><?php echo $sl; ?></td>
                        <td><?php echo dso_fmt_date($row['observation_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['area_location']); ?></td>
                        <td style="text-align:left;"><?php echo nl2br(htmlspecialchars($row['observation_details'])); ?></td>
                        <td><?php echo htmlspecialchars($row['observation_type']); ?></td>
                        <td style="text-align:left;"><?php echo nl2br(htmlspecialchars($row['immediate_action'])); ?></td>
                        <td><?php echo htmlspecialchars($row['responsible_person']); ?></td>
                        <td><?php echo dso_fmt_date($row['target_date']); ?></td>
                        <td><?php echo dso_fmt_date($row['closure_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['compliance_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['verified_by']); ?></td>
                        <td><?php echo htmlspecialchars($row['reviewed_by']); ?></td>
                        <td class="img-cell">
                            <?php if(dso_img($row['before_image'])){ ?>
                                <img src="<?php echo dso_img($row['before_image']); ?>" alt="Before">
                            <?php } ?>
                        </td>
                        <td class="img-cell">
                            <?php if(dso_img($row['after_image'])){ ?>
                                <img src="<?php echo dso_img($row['after_image']); ?>" alt="After">
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td colspan="14">No records found for selected date range.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
