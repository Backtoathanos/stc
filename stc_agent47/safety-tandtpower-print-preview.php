<?php
include_once("../MCU/db.php");

$powertools_id = isset($_GET['powertools_id']) ? (int)$_GET['powertools_id'] : 0;

$main_data = null;
$items_data = [];

if ($powertools_id > 0) {
    $q = mysqli_query($con, "SELECT * FROM `stc_safety_powertools_aud07` WHERE `id` = $powertools_id");
    if ($q && mysqli_num_rows($q) > 0) {
        $main_data = mysqli_fetch_assoc($q);

        $q2 = mysqli_query($con, "SELECT * FROM `stc_safety_powertools_aud07_items` WHERE `powertools_id` = $powertools_id ORDER BY `id` ASC");
        if ($q2) {
            while ($row = mysqli_fetch_assoc($q2)) {
                $items_data[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUD-07 Power Tools &amp; Calibration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 4px 6px; text-align: center; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f0f0f0; }
        .status-ok   { background-color: #d4edda; color: #155724; }
        .status-notok{ background-color: #f8d7da; color: #721c24; }
        .status-yes  { background-color: #d4edda; color: #155724; }
        .status-no   { background-color: #f8d7da; color: #721c24; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container-fluid my-3">

    <!-- Header -->
    <div class="row" style="border-bottom: 2px solid black; padding-bottom: 8px; margin-bottom: 8px;">
        <div class="col-2 text-center">
            <img style="width: 90px;" src="images/globallogo.jpg" alt="Logo">
        </div>
        <div class="col-8 text-center">
            <h4 style="color:#fe7f26; margin-bottom:2px;"><strong>GLOBAL AC SYSTEM JSR PVT LTD.</strong></h4>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row mb-2">
        <div class="col-2"><strong>AUD-07</strong></div>
        <div class="col-10" style="font-size:11px;">
            <strong>AUDIT REGISTER ELECTRIC TOOL (DRILL MACHINE, HAMMER DRILL MACHINE, GRINDER, BLOWER, SOLDERING IRON ETC)</strong>
        </div>
    </div>

    <?php if ($main_data): ?>

    <!-- Work Order / Site / Date row -->
    <div class="row mb-2" style="font-size:12px;">
        <div class="col-4">
            <strong>Work Order No.:</strong> <?php echo htmlspecialchars($main_data['work_orderno']); ?>
        </div>
        <div class="col-4">
            <strong>JOB SITE NAME:</strong> <?php echo htmlspecialchars($main_data['sitename']); ?>
        </div>
        <div class="col-4">
            <strong>STARTING DATE:</strong>
            <?php
                $sd = $main_data['starting_date'];
                if ($sd && $sd !== '0000-00-00' && $sd !== '') {
                    echo htmlspecialchars(date('d-m-Y', strtotime($sd)));
                }
            ?>
        </div>
    </div>

    <!-- Main table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:30px;">SN</th>
                <th colspan="2">DESCRIPTION OF TOOL</th>
                <th colspan="7">INSPECTION REQUIREMENT AND STATUS</th>
            </tr>
            <tr>
                <th>NAME / TYPE / MAKE</th>
                <th>SERIAL No.</th>
                <th>3 CORE CABLE &amp; PLUG</th>
                <th>INSULATION</th>
                <th>EARTHING CONNECTION</th>
                <th>HANDLE</th>
                <th>SAFE TO WORK</th>
                <th>NAME: SUPR/ENGR</th>
                <th>SIGN/DATE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_rows = max(15, count($items_data));
            for ($i = 0; $i < $total_rows; $i++):
                if ($i < count($items_data)):
                    $item = $items_data[$i];
                    $coreClass    = (strtolower($item['three_core_cable_plug']) === 'ok') ? 'status-ok' : 'status-notok';
                    $insulClass   = (strtolower($item['insulation']) === 'ok')            ? 'status-ok' : 'status-notok';
                    $earthClass   = (strtolower($item['earthing_connection']) === 'ok')   ? 'status-ok' : 'status-notok';
                    $handleClass  = (strtolower($item['handle']) === 'ok')                ? 'status-ok' : 'status-notok';
                    $safeClass    = (strtolower($item['safe_to_work']) === 'yes')         ? 'status-yes' : 'status-no';
            ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td style="text-align:left;"><?php echo htmlspecialchars($item['name_type_make']); ?></td>
                <td><?php echo htmlspecialchars($item['serial_no']); ?></td>
                <td class="<?php echo $coreClass; ?>"><?php echo htmlspecialchars($item['three_core_cable_plug']); ?></td>
                <td class="<?php echo $insulClass; ?>"><?php echo htmlspecialchars($item['insulation']); ?></td>
                <td class="<?php echo $earthClass; ?>"><?php echo htmlspecialchars($item['earthing_connection']); ?></td>
                <td class="<?php echo $handleClass; ?>"><?php echo htmlspecialchars($item['handle']); ?></td>
                <td class="<?php echo $safeClass; ?>"><?php echo htmlspecialchars($item['safe_to_work']); ?></td>
                <td style="text-align:left;"><?php echo htmlspecialchars($item['supr_engg_name']); ?></td>
                <td><?php echo htmlspecialchars($item['sign_date']); ?></td>
            </tr>
            <?php else: ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            <?php endif; ?>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="row mt-3">
        <div class="col-6">
            <strong>Safety Supervisor:</strong> ____________________________
        </div>
        <div class="col-6">
            <strong>Date:</strong> ____________________________
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-warning mt-3">
        <h5>No Data Found</h5>
        <p>Power Tools record not found or no ID provided.</p>
        <a href="javascript:void(0)" class="btn btn-primary backbtn">Back</a>
    </div>
    <?php endif; ?>

    <!-- Footer copyright -->
    <div class="row mt-3" style="border-top:1px solid #ccc; padding-top:6px; font-size:11px;">
        <div class="col-12 text-center">
            &copy; Global AC System JSR Pvt Ltd. ALL RIGHTS RESERVED &nbsp;|&nbsp; Issue 01/Rev. 00 Jan 1, 2018_NA
        </div>
    </div>

</div>

<!-- Print / Back buttons -->
<div class="no-print" style="position:fixed; top:15px; right:15px;">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="javascript:void(0)" class="btn btn-secondary btn-sm backbtn">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.backbtn').forEach(function(btn){
    btn.addEventListener('click', function(){ window.history.back(); });
});
</script>
</body>
</html>
