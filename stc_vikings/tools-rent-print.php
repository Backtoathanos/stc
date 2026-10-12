<?php
require_once __DIR__ . '/kattegat/auth_helper.php';
STCAuthHelper::checkAuth();
require_once __DIR__ . '/kattegat/ragnar_purchase.php';

$adhoc = new ragnarPurchaseAdhoc();
$rows = $adhoc->stc_tools_rent_dispatch_rows();
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if ($q !== '') {
    $needle = function_exists('mb_strtolower') ? mb_strtolower($q) : strtolower($q);
    $rows = array_values(array_filter($rows, function ($row) use ($needle) {
        $blob = strtolower(($row['item_name'] ?? '') . ' ' . ($row['stc_product_name'] ?? '') . ' ' . ($row['project_title'] ?? '') . ' ' . ($row['rec_date'] ?? ''));
        return strpos($blob, $needle) !== false;
    }));
}

$rent_no = 'STC/TR/' . date('dmY');
$statement_date = date('d-m-Y');
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Tools Rent</title>
<style>
@page { size: A4 portrait; margin: 10mm; }
body { margin: 0; color: #000; font-family: Arial, Helvetica, sans-serif; }
.sheet { width: 190mm; margin: 0 auto; }
.head { position: relative; width: 100%; border-bottom: 2px solid #000; margin-bottom: 10px; padding-bottom: 8px; min-height: 118px; }
.head .brand { width: 42%; font-size: 11px; line-height: 1.35; }
.head .brand img { height: 46px; display: block; margin-bottom: 4px; }
.head .mid { position: absolute; left: 0; right: 0; top: 0; text-align: center; }
.head .mid h2 { margin: 0 0 8px; text-align: center; font-size: 32px; font-weight: 700; }
.head .mid .meta { display: inline-block; text-align: left; }
.head .mid h4 { margin: 0 0 4px; font-size: 14px; font-weight: 600; }
.head .logo { position: absolute; right: 0; top: 0; text-align: right; }
.head .logo img { height: 60px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #000; padding: 4px; font-size: 11px; color: #000; }
th { background: #bbbed4; text-align: center; font-weight: 600; }
td { background: #fff; }
.c { text-align: center; }
.r { text-align: right; }
.tools { margin-top: 8px; }
.rent-note { margin: 8px 0 0; font-size: 12px; }
@media print { .noprint { display: none; } }
</style>
</head>
<body>
<div class="noprint" style="text-align:right;margin:8px;">
  <button type="button" onclick="window.print()">Print / Save PDF</button>
</div>
<div class="sheet">
  <div class="head">
    <div class="brand">
      <img src="../stc_symbiote/img/stc-header.png" alt="STC">
      <div>Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, <br>Jamshedpur, Jharkhand 832110</div>
      <div>Mobile No. : +91-8986811304<br>E.Mail:stc111213@gmail.com<br>GSTIN: 20JCBPS6008G1ZT</div>
    </div>
    <div class="mid">
      <h2>Tools Rent</h2>
      <div class="meta">
        <h4>Rent No : <?php echo htmlspecialchars($rent_no); ?></h4>
        <h4>Date : <?php echo htmlspecialchars($statement_date); ?></h4>
      </div>
    </div>
    <div class="logo"><img src="../stc_symbiote/img/stc_logo.png" alt="STC"></div>
  </div>
  <table class="tools">
    <thead>
      <tr>
        <th>Sl No</th>
        <th>Product</th>
        <th>Site</th>
        <th>Qty</th>
        <th>Month</th>
        <th>Dispatch</th>
        <th>Days</th>
        <th>Rent/day</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$rows) { ?>
        <tr><td colspan="9" class="c">No dispatched returnable tools.</td></tr>
      <?php } else { $sl = 0; foreach ($rows as $row) {
        $sl++;
        $days = (int)($row['days'] ?? 0);
        $amount = (float)($row['amount'] ?? 0);
        $total += $amount;
      ?>
        <tr>
          <td class="c"><?php echo $sl; ?></td>
          <td><?php echo htmlspecialchars($row['item_name'] ?: $row['stc_product_name']); ?></td>
          <td><?php echo htmlspecialchars($row['project_title'] ?? ''); ?></td>
          <td class="r"><?php echo number_format((float)$row['qty'], 2); ?></td>
          <td class="r"><?php echo number_format($days / 30, 2); ?></td>
          <td class="c"><?php echo htmlspecialchars($row['rec_date'] ?? ''); ?></td>
          <td class="r"><?php echo $days; ?></td>
          <td class="r"><?php echo number_format((float)$row['stc_product_rent_per_day'], 2); ?></td>
          <td class="r"><b><?php echo number_format($amount, 2); ?></b></td>
        </tr>
      <?php } ?>
        <tr>
          <td colspan="8" class="r"><b>Total</b></td>
          <td class="r"><b><?php echo number_format($total, 2); ?></b></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <p class="rent-note">Amount = qty × rent/day × days from dispatch through today</p>
</div>
<script>window.onload = function () { setTimeout(function () { window.print(); }, 400); };</script>
</body>
</html>
