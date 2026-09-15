<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();

include "../MCU/db.php";

function stc_challan_last_bracket($text){
  $text = trim((string) $text);
  if($text === '' || substr($text, -1) !== ')') return '';
  $depth = 0;
  $len = strlen($text);
  for($i = $len - 1; $i >= 0; $i--){
    $ch = $text[$i];
    if($ch === ')') $depth++;
    elseif($ch === '('){
      $depth--;
      if($depth === 0){
        return trim(substr($text, $i + 1, $len - $i - 2));
      }
    }
  }
  return '';
}

function stc_challan_is_wo_code($s){
  $s = trim((string) $s);
  if($s === '' || strpos($s, '/') === false) return false;
  return !preg_match('/[A-Za-z]{3,}(?:\s+[A-Za-z0-9#.\-]{2,})+/', $s);
}

function stc_challan_site_label($sitename, $prLocation){
  $sitename = trim(preg_replace('/\s+/', ' ', (string) $sitename));
  $prLocation = trim(preg_replace('/\s+/', ' ', (string) $prLocation));
  if($sitename === ''){
    return ($prLocation === '' || $prLocation === '-') ? '' : $prLocation;
  }
  if($prLocation === '' || $prLocation === '-' || strcasecmp($sitename, $prLocation) === 0){
    return $sitename;
  }
  if(stripos($sitename, $prLocation) !== false){
    return $sitename;
  }
  return $sitename.' ('.$prLocation.')';
}

function stc_challan_combination_label($sitename, $prLocation){
  $sitename = trim(preg_replace('/\s+/', ' ', (string) $sitename));
  $prLocation = trim(preg_replace('/\s+/', ' ', (string) $prLocation));
  $source = ($prLocation !== '' && $prLocation !== '-') ? $prLocation : $sitename;
  if($source === '') return '';
  $last = stc_challan_last_bracket($source);
  if($last !== '' && !stc_challan_is_wo_code($last)){
    return $last;
  }
  if($prLocation === '' || $prLocation === '-'){
    $fromSite = stc_challan_last_bracket($sitename);
    if($fromSite !== '' && !stc_challan_is_wo_code($fromSite)){
      return $fromSite;
    }
  }
  return $source;
}

$date = '';
if(isset($_GET['date']) && $_GET['date'] != ''){
  $date = date('Y-m-d', strtotime($_GET['date']));
} else if(isset($_GET['begdate']) && $_GET['begdate'] != ''){
  // Backward compatibility
  $date = date('Y-m-d', strtotime($_GET['begdate']));
} else {
  $date = date('Y-m-d');
}

$pm_no = 'STC/DC/'.date('dmY', strtotime($date));
$pm_date = date('d-m-Y', strtotime($date));
$site_name = 'Multiple';

$chkCol = mysqli_query($con, "SHOW COLUMNS FROM `stc_cust_super_requisition_list` LIKE 'stc_cust_super_requisition_list_order_number'");
if($chkCol && mysqli_num_rows($chkCol) == 0){
  mysqli_query($con, "
    ALTER TABLE `stc_cust_super_requisition_list`
    ADD `stc_cust_super_requisition_list_order_number` VARCHAR(100) NOT NULL DEFAULT ''
    AFTER `stc_cust_super_requisition_list_project_id`
  ");
}

$order_number = isset($_GET['order_number']) ? trim((string) $_GET['order_number']) : '';
$site_label = isset($_GET['site']) ? trim((string) $_GET['site']) : '';
$date_esc = mysqli_real_escape_string($con, $date);

$challanFrom = "
  FROM `stc_verify_dispatch_accept` VA
  INNER JOIN `stc_cust_super_requisition_list_items` I
    ON I.`stc_cust_super_requisition_list_id` = VA.`item_id`
  INNER JOIN `stc_cust_super_requisition_list` L
    ON L.`stc_cust_super_requisition_list_id` = I.`stc_cust_super_requisition_list_items_req_id`
  LEFT JOIN `stc_cust_project` P
    ON P.`stc_cust_project_id` = L.`stc_cust_super_requisition_list_project_id`
  LEFT JOIN `stc_cust_pro_supervisor` S
    ON S.`stc_cust_pro_supervisor_id` = L.`stc_cust_super_requisition_list_super_id`
  LEFT JOIN `stc_requisition_combiner_req` CR
    ON CR.`stc_requisition_combiner_req_requisition_id` = L.`stc_cust_super_requisition_list_id`
  LEFT JOIN `stc_requisition_combiner` C
    ON C.`stc_requisition_combiner_id` = CR.`stc_requisition_combiner_req_comb_id`
  WHERE DATE(VA.`created_date`) = '".$date_esc."'
";

$order_options = array();
$site_options = array();
$siteSeen = array();
$orderSeen = array();

$listQ = mysqli_query($con, "
  SELECT DISTINCT
    TRIM(COALESCE(L.`stc_cust_super_requisition_list_order_number`, '')) AS order_number,
    P.`stc_cust_project_title` AS sitename,
    C.`stc_requisition_combiner_refrence` AS pr_location
  ".$challanFrom."
");
if($listQ){
  while($sr = mysqli_fetch_assoc($listQ)){
    $label = stc_challan_combination_label($sr['sitename'] ?? '', $sr['pr_location'] ?? '');
    $on = trim((string)($sr['order_number'] ?? ''));

    if($label !== ''){
      $siteKey = strtoupper($label);
      if(!isset($siteSeen[$siteKey])){
        $siteSeen[$siteKey] = true;
        $site_options[] = array('sitename' => $label);
      }
    }

    if($on === '') continue;
    if($site_label !== ''){
      if($label === '' || strcasecmp($label, $site_label) !== 0) continue;
    }
    $orderKey = strtoupper($on);
    if(!isset($orderSeen[$orderKey])){
      $orderSeen[$orderKey] = true;
      $order_options[] = $on;
    }
  }
  usort($site_options, function($a, $b){
    return strcasecmp($a['sitename'], $b['sitename']);
  });
  usort($order_options, function($a, $b){
    return strcasecmp($a, $b);
  });
}

if($order_number !== '' && !in_array($order_number, $order_options, true)){
  $order_number = '';
}

$filter_sql = '';
if($order_number !== ''){
  $filter_sql .= " AND L.`stc_cust_super_requisition_list_order_number` = '".mysqli_real_escape_string($con, $order_number)."'";
}

$selected_site_title = $site_label;
$hide_extra_cols = (strcasecmp($selected_site_title, 'TATA STEEL AMC') === 0);

$challan_rows = array();
$sql = mysqli_query($con, "
  SELECT
    VA.`item_id`,
    VA.`qty` AS accepted_qty,
    VA.`created_date` AS accepted_date,
    I.`stc_cust_super_requisition_list_items_title` AS item_desc,
    I.`stc_cust_super_requisition_list_items_unit` AS unit,
    I.`stc_cust_super_requisition_list_id` AS item_id,
    L.`stc_cust_super_requisition_list_id` AS requisition_id,
    L.`stc_cust_super_requisition_list_date` AS requisition_date,
    P.`stc_cust_project_title` AS sitename,
    S.`stc_cust_pro_supervisor_fullname` AS req_from,
    S.`stc_cust_pro_supervisor_contact` AS req_from_contact,
    C.`stc_requisition_combiner_id` AS pr_no,
    C.`stc_requisition_combiner_date` AS pr_date,
    C.`stc_requisition_combiner_refrence` AS pr_location,
    L.`stc_cust_super_requisition_list_order_number` AS order_number
  ".$challanFrom."
  ".$filter_sql."
  ORDER BY TIMESTAMP(VA.`created_date`) DESC, VA.`id` DESC
");

if($sql && mysqli_num_rows($sql) > 0){
  while($row = mysqli_fetch_assoc($sql)){
    $combinationName = stc_challan_combination_label($row['sitename'], $row['pr_location']);
    if($site_label !== '' && strcasecmp($combinationName, $site_label) !== 0){
      continue;
    }
    $reqFrom = trim((string)($row['req_from'] ?? ''));
    $reqContact = trim((string)($row['req_from_contact'] ?? ''));
    $reqFromHtml = htmlspecialchars($reqFrom);
    if($reqContact !== ''){
      $reqFromHtml .= ($reqFromHtml !== '' ? '<br>' : '').htmlspecialchars($reqContact);
    }
    $item_id_esc = mysqli_real_escape_string($con, $row['item_id']);
    $query2 = mysqli_query($con, "
        SELECT GROUP_CONCAT(DISTINCT RK.`stc_rack_name` ORDER BY RK.`stc_rack_name` SEPARATOR ', ') AS stc_rack_name
        FROM `stc_cust_super_requisition_list_items_rec` REC
        INNER JOIN `stc_purchase_product_adhoc` APA ON APA.`stc_purchase_product_adhoc_id` = REC.`stc_cust_super_requisition_list_items_rec_list_poaid`
        LEFT JOIN `stc_rack` RK ON RK.`stc_rack_id` = APA.`stc_purchase_product_adhoc_rackid`
        WHERE REC.`stc_cust_super_requisition_list_items_rec_list_item_id` = '".$item_id_esc."'
    ");
    $rackRow = ($query2 && mysqli_num_rows($query2) > 0) ? mysqli_fetch_assoc($query2) : array();
    $rack = ($rackRow['stc_rack_name'] ?? '') ?: '-';
    $challan_rows[] = array(
      'sitename' => stc_challan_site_label($row['sitename'], $row['pr_location']),
      'item_desc' => (string)($row['item_desc'] ?? ''),
      'unit' => (string)($row['unit'] ?? ''),
      'accepted_qty' => number_format((float)$row['accepted_qty'], 2),
      'rack' => $rack,
      'req_from_html' => $reqFromHtml
    );
  }
}

if(isset($_GET['ajax']) && $_GET['ajax'] !== '' && $_GET['ajax'] !== '0'){
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array(
    'success' => true,
    'date' => $date,
    'pm_no' => $pm_no,
    'pm_date' => $pm_date,
    'order_number' => $order_number,
    'site' => $site_label,
    'hide_extra_cols' => $hide_extra_cols,
    'order_options' => $order_options,
    'site_options' => array_map(function($s){ return $s['sitename']; }, $site_options),
    'rows' => $challan_rows
  ));
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Verify Challan - STC</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../stc_symbiote/css/templatemo-style.css">
    <style>
      .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }
      .invoice table td,.invoice table th {
        padding: 5px;
        background: #bbbed4;
        border: 1px solid #000;
        border-bottom: 1px solid #000;
        color: #000;
      }
      .invoice table th {
        white-space: nowrap;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        color: #000;
      }
      .invoice table td {
        background: #fff;
        font-size: 13px;
        color: #000;
      }
      .invoice{
        margin-top: 0 !important;
      }
      .stc-dd {
        display: inline-block;
        position: relative;
        vertical-align: middle;
        text-align: left;
        margin-right: 6px;
        width: 200px;
      }
      #stc-dd-site {
        width: 260px;
      }
      .stc-dd-toggle {
        width: 100%;
        min-width: 0;
        max-width: none;
        box-sizing: border-box;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .stc-dd-menu {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        z-index: 40;
        width: 100%;
        min-width: 0;
        max-width: none;
        max-height: 320px;
        overflow-y: auto;
        margin: 2px 0 0;
        padding: 4px 0;
        list-style: none;
        background: #fff;
        border: 1px solid #bbb;
        border-radius: 4px;
        box-shadow: 0 6px 16px rgba(0,0,0,.18);
        box-sizing: border-box;
      }
      .stc-dd.open .stc-dd-menu { display: block; }
      .stc-dd-menu li {
        padding: 7px 12px;
        cursor: pointer;
        color: #222;
        font-size: 13px;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .stc-dd-menu li:hover,
      .stc-dd-menu li.is-active {
        background: #17a2b8;
        color: #fff;
      }
      .stc-dd-menu li .stc-dd-ord {
        display: inline-block;
        min-width: 70px;
        margin-right: 8px;
        font-weight: 700;
      }
      #stcCustomerFormatModal .modal-dialog {
        max-width: 980px;
        width: 96vw;
        margin: 1.2vh auto;
      }
      #stcCustomerFormatModal .modal-content {
        height: 96vh;
        display: flex;
        flex-direction: column;
      }
      #stcCustomerFormatModal .modal-header {
        flex: 0 0 auto;
        padding: 8px 12px;
        background: #f8f9fa;
      }
      #stcCustomerFormatModal .modal-title {
        font-size: 15px;
        font-weight: 700;
      }
      #stcCustomerFormatModal .modal-body {
        flex: 1 1 auto;
        padding: 0;
        overflow: hidden;
        background: #d8dde3;
      }
      #stcCustomerFormatModal iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
      }
      @media print {
        body{ margin: 0 !important; }
        .hidden-print { display: none !important; }
        .tm-footer { display: none !important; }
        #stcCustomerFormatModal, .modal-backdrop { display: none !important; }
        .tm-mt-big{ margin-top: 0 !important; }
        .tm-mb-big{ margin-bottom: 0 !important; }
        .invoice{ margin-top: 0 !important; overflow: visible !important; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; }
        .row.header {
          display: flex !important;
          flex-wrap: nowrap !important;
          overflow: visible !important;
          page-break-inside: avoid;
        }
        .row.header > [class*="col-"] {
          display: block !important;
          float: none !important;
          max-width: none !important;
        }
        .row.header img {
          display: block !important;
          visibility: visible !important;
          max-width: 100%;
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
          color-adjust: exact !important;
        }
        #logo_print_pre {
          display: block !important;
          visibility: visible !important;
          position: static !important;
          float: none !important;
          right: auto !important;
          margin: 0 !important;
          text-align: right;
        }
        #logo_print_pre img {
          display: inline-block !important;
          height: 70px !important;
          width: auto !important;
        }
        #verifyChallanTable, #verifyChallanTable th, #verifyChallanTable td {
          color: #000 !important;
          border: 1px solid #000 !important;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }
      }
      @media screen {
        thead th {
          position: sticky;
          top: 0;
          z-index: 2;
          background: #bbbed4;
        }
      }
    </style>
  </head>

  <body>
    <div class="text-right hidden-print" style="margin:10px;">
      <a href="#" class="btn btn-success" id="stc-customer-format-btn" title="View customer format challan" style="margin-right:6px;">
        <i class="fas fa-file-alt"></i> Customer Format
      </a>
      <div class="stc-dd" id="stc-dd-order">
        <button type="button" class="btn stc-dd-toggle" title="Order Number">
          <?php echo $order_number !== '' ? htmlspecialchars($order_number) : 'All Order Numbers'; ?>
        </button>
        <input type="hidden" class="vorder-number" value="<?php echo htmlspecialchars($order_number); ?>">
        <ul class="stc-dd-menu">
          <li data-value="" class="<?php echo $order_number === '' ? 'is-active' : ''; ?>">All Order Numbers</li>
          <?php foreach($order_options as $on){ ?>
            <li data-value="<?php echo htmlspecialchars($on); ?>" class="<?php echo ($order_number === $on) ? 'is-active' : ''; ?>"><?php echo htmlspecialchars($on); ?></li>
          <?php } ?>
        </ul>
      </div>
      <div class="stc-dd" id="stc-dd-site">
        <button type="button" class="btn stc-dd-toggle" title="Site">
          <?php echo $selected_site_title !== '' ? htmlspecialchars($selected_site_title) : 'All Sites'; ?>
        </button>
        <input type="hidden" class="vsite" value="<?php echo htmlspecialchars($site_label); ?>">
        <ul class="stc-dd-menu">
          <li data-value="" class="<?php echo $site_label === '' ? 'is-active' : ''; ?>">All Sites</li>
          <?php foreach($site_options as $so){
            $soName = $so['sitename'];
          ?>
            <li data-value="<?php echo htmlspecialchars($soName); ?>" class="<?php echo ($site_label === $soName) ? 'is-active' : ''; ?>">
              <?php echo htmlspecialchars($soName); ?>
            </li>
          <?php } ?>
        </ul>
      </div>
      <input type="date" class="btn vdate" value="<?php echo $date; ?>">
      <a class="btn btn-info filterbydate"><i class="fas fa-print"></i> Update</a>
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row header" style="border-bottom:2px solid #000; margin-bottom:10px;">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
          <div style="height: 50px;"><img style="height: 50px;" src="../stc_symbiote/img/stc-header.png"></div>
          <p>
            Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110
          </p>
          <p>
            Mobile No. : +91-8986811304<br>
            E.Mail:stc111213@gmail.com<br>
            GSTIN: 20JCBPS6008G1ZT
          </p>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <h2 align="center" style="font-size:40px;">Delivery Challan</h2>
          <div>
            <h4 align="left">P.M No : <span id="pmNoDisplay"><?php echo htmlspecialchars($pm_no); ?></span></h4>
            <h4 align="left">P.M Date : <span id="challanPmDate"><?php echo htmlspecialchars($pm_date); ?></span></h4>
            <h4 align="left" id="challanMetaOrder" style="<?php echo $order_number === '' ? 'display:none;' : ''; ?>">Order Number : <span id="challanMetaOrderVal"><?php echo htmlspecialchars($order_number); ?></span></h4>
            <h4 align="left" id="challanMetaSite" style="<?php echo $selected_site_title === '' ? 'display:none;' : ''; ?>">Site : <span id="challanMetaSiteVal"><?php echo htmlspecialchars($selected_site_title); ?></span></h4>
          </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
          <a target="_blank" id="logo_print_pre" href="#">
            <img style="height:60px;" src="../stc_symbiote/img/stc_logo.png" alt="STC" />
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <div class="hidden-print mb-2">
            <input type="text" id="tableSearch" class="form-control" placeholder="Search table..." style="background-color: #e5f3b2;color:black">
          </div>
          <div style="overflow-x:auto;">
            <table class="table table-bordered table-hover" style="color:#000; border:1px solid #000;" id="verifyChallanTable">
              <thead id="verifyChallanThead">
                <tr>
                  <th>Sl No</th>
                  <th>Sitename</th>
                  <th>Item Desc</th>
                  <th>Unit</th>
                  <th>Dispatched Qty</th>
                  <?php if(!$hide_extra_cols){ ?>
                    <th>Rack</th>
                    <th>Req From</th>
                  <?php } ?>
                  <th>Sign</th>
                </tr>
              </thead>
              <tbody id="verifyChallanTbody">
                <?php
                if(count($challan_rows) > 0){
                  $sl = 0;
                  foreach($challan_rows as $row){
                    $sl++;
                ?>
                  <tr>
                    <td class="text-center dr-slno"><?php echo $sl; ?></td>
                    <td><?php echo htmlspecialchars($row['sitename']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['item_desc'])); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td class="text-right"><b><?php echo htmlspecialchars($row['accepted_qty']); ?></b></td>
                    <?php if(!$hide_extra_cols){ ?>
                      <td><?php echo htmlspecialchars($row['rack']); ?></td>
                      <td><?php echo $row['req_from_html']; ?></td>
                    <?php } ?>
                    <td><span style="opacity: 0;">..................................</span></td>
                  </tr>
                <?php
                  }
                }else{
                  $colspan = $hide_extra_cols ? 6 : 8;
                  echo '<tr><td colspan="'.$colspan.'" class="text-center">No accepted items found for this date.</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="stcCustomerFormatModal" tabindex="-1" role="dialog" aria-labelledby="stcCustomerFormatModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="stcCustomerFormatModalLabel">Customer Format Challan</h5>
            <div>
              <button type="button" class="btn btn-danger btn-sm" id="stc-customer-format-pdf">Export to PDF</button>
              <button type="button" class="btn btn-success btn-sm" id="stc-customer-format-excel" style="margin-left:6px;">Export to Excel</button>
              <button type="button" class="btn btn-primary btn-sm" id="stc-customer-format-word" style="margin-left:6px;">Export to Word</button>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:0 0 0 8px;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <div class="modal-body">
            <iframe id="stc-customer-format-frame" src="about:blank" title="Customer Format Challan"></iframe>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "../stc_symbiote/footer.php";?>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script>
      $(document).ready(function(){
        var basePmNo = '<?php echo addslashes($pm_no); ?>';
        var challanLoading = false;

        function escHtml(str){
          return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
        }
        function nl2brEsc(str){
          return escHtml(str).replace(/\r\n|\r|\n/g, '<br>');
        }
        function challanFilterUrl(orderNo, siteLabel){
          var date = $('.vdate').val();
          if (typeof orderNo === 'undefined') orderNo = $('.vorder-number').val() || '';
          if (typeof siteLabel === 'undefined') siteLabel = $('.vsite').val() || '';
          var url = 'verify-challan.php?date=' + encodeURIComponent(date);
          if (orderNo) url += '&order_number=' + encodeURIComponent(orderNo);
          if (siteLabel) url += '&site=' + encodeURIComponent(siteLabel);
          return url;
        }
        function updateBrowserUrl(orderNo, siteLabel){
          if (window.history && window.history.pushState) {
            window.history.pushState({challan:1}, '', challanFilterUrl(orderNo, siteLabel));
          }
        }
        function renderOrderOptions(options, selected){
          var html = '<li data-value="" class="'+(selected === '' ? 'is-active' : '')+'">All Order Numbers</li>';
          (options || []).forEach(function(on){
            html += '<li data-value="'+escHtml(on)+'" class="'+(selected === on ? 'is-active' : '')+'">'+escHtml(on)+'</li>';
          });
          $('#stc-dd-order .stc-dd-menu').html(html);
          $('#stc-dd-order .stc-dd-toggle').text(selected !== '' ? selected : 'All Order Numbers');
          $('.vorder-number').val(selected || '');
        }
        function renderSiteOptions(options, selected){
          var html = '<li data-value="" class="'+(selected === '' ? 'is-active' : '')+'">All Sites</li>';
          (options || []).forEach(function(site){
            html += '<li data-value="'+escHtml(site)+'" class="'+(selected === site ? 'is-active' : '')+'">'+escHtml(site)+'</li>';
          });
          $('#stc-dd-site .stc-dd-menu').html(html);
          $('#stc-dd-site .stc-dd-toggle').text(selected !== '' ? selected : 'All Sites');
          $('.vsite').val(selected || '');
        }
        function renderChallanTable(rows, hideExtra){
          var thead = '<tr><th>Sl No</th><th>Sitename</th><th>Item Desc</th><th>Unit</th><th>Dispatched Qty</th>';
          if(!hideExtra) thead += '<th>Rack</th><th>Req From</th>';
          thead += '<th>Sign</th></tr>';
          $('#verifyChallanThead').html(thead);

          var body = '';
          if(rows && rows.length){
            rows.forEach(function(row, i){
              body += '<tr>'
                + '<td class="text-center dr-slno">'+(i+1)+'</td>'
                + '<td>'+escHtml(row.sitename)+'</td>'
                + '<td>'+nl2brEsc(row.item_desc)+'</td>'
                + '<td class="text-center">'+escHtml(row.unit)+'</td>'
                + '<td class="text-right"><b>'+escHtml(row.accepted_qty)+'</b></td>';
              if(!hideExtra){
                body += '<td>'+escHtml(row.rack)+'</td>'
                  + '<td>'+(row.req_from_html || '')+'</td>';
              }
              body += '<td><span style="opacity: 0;">..................................</span></td></tr>';
            });
          }else{
            var cols = hideExtra ? 6 : 8;
            body = '<tr><td colspan="'+cols+'" class="text-center">No accepted items found for this date.</td></tr>';
          }
          $('#verifyChallanTbody').html(body);
          $('#tableSearch').val('');
          bindTableSearch();
        }
        function applyChallanMeta(data){
          basePmNo = data.pm_no || basePmNo;
          $('#pmNoDisplay').text(basePmNo);
          $('#challanPmDate').text(data.pm_date || '');
          if(data.order_number){
            $('#challanMetaOrderVal').text(data.order_number);
            $('#challanMetaOrder').show();
          }else{
            $('#challanMetaOrder').hide();
            $('#challanMetaOrderVal').text('');
          }
          if(data.site){
            $('#challanMetaSiteVal').text(data.site);
            $('#challanMetaSite').show();
          }else{
            $('#challanMetaSite').hide();
            $('#challanMetaSiteVal').text('');
          }
          if(data.date) $('.vdate').val(data.date);
        }
        function loadChallanRecords(orderNo, siteLabel, pushUrl){
          if (typeof orderNo === 'undefined') orderNo = $('.vorder-number').val() || '';
          if (typeof siteLabel === 'undefined') siteLabel = $('.vsite').val() || '';
          if (pushUrl !== false) updateBrowserUrl(orderNo, siteLabel);
          if (challanLoading) return;
          challanLoading = true;
          var url = challanFilterUrl(orderNo, siteLabel);
          url += (url.indexOf('?') === -1 ? '?' : '&') + 'ajax=1';
          $('#verifyChallanTbody').css('opacity', 0.45);
          $.getJSON(url)
            .done(function(data){
              if(!data || !data.success) return;
              applyChallanMeta(data);
              renderOrderOptions(data.order_options || [], data.order_number || '');
              renderSiteOptions(data.site_options || [], data.site || '');
              renderChallanTable(data.rows || [], !!data.hide_extra_cols);
            })
            .fail(function(){
              alert('Failed to load challan records.');
            })
            .always(function(){
              challanLoading = false;
              $('#verifyChallanTbody').css('opacity', 1);
            });
        }

        $('#printInvoice').click(function(){
          window.print();
        });
        $('.stc-dd-toggle').on('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          var $dd = $(this).closest('.stc-dd');
          $('.stc-dd').not($dd).removeClass('open');
          $dd.toggleClass('open');
        });
        $(document).on('click', function(){
          $('.stc-dd').removeClass('open');
        });
        $(document).on('click', '.stc-dd-menu', function(e){ e.stopPropagation(); });
        $(document).on('click', '#stc-dd-order .stc-dd-menu li', function(){
          var orderNo = $(this).attr('data-value') || '';
          var siteLabel = $('.vsite').val() || '';
          $('.stc-dd').removeClass('open');
          loadChallanRecords(orderNo, siteLabel, true);
        });
        $(document).on('click', '#stc-dd-site .stc-dd-menu li', function(){
          var siteLabel = $(this).attr('data-value') || '';
          $('.stc-dd').removeClass('open');
          loadChallanRecords('', siteLabel, true);
        });
        $('.filterbydate').on('click', function(e){
          e.preventDefault();
          loadChallanRecords($('.vorder-number').val() || '', $('.vsite').val() || '', true);
        });
        $(window).on('popstate', function(){
          var params = new URLSearchParams(window.location.search);
          var date = params.get('date') || $('.vdate').val();
          var orderNo = params.get('order_number') || '';
          var siteLabel = params.get('site') || '';
          if(date) $('.vdate').val(date);
          loadChallanRecords(orderNo, siteLabel, false);
        });

        $('#stc-customer-format-btn').on('click', function(e){
          e.preventDefault();
          var url = challanFilterUrl().replace('verify-challan.php', 'verify-challan-customer.php');
          url += (url.indexOf('?') === -1 ? '?' : '&') + 'embed=1';
          $('#stc-customer-format-frame').attr('src', url);
          if ($.fn.modal) {
            $('#stcCustomerFormatModal').modal('show');
          } else {
            $('#stcCustomerFormatModal').addClass('show').css('display', 'block').attr('aria-hidden', 'false');
            $('body').addClass('modal-open');
            if (!$('.modal-backdrop').length) $('body').append('<div class="modal-backdrop fade show"></div>');
          }
        });
        function customerFormatExportUrl(type){
          var url = challanFilterUrl().replace('verify-challan.php', 'verify-challan-customer.php');
          url += (url.indexOf('?') === -1 ? '?' : '&') + 'export=' + encodeURIComponent(type);
          return url;
        }
        function downloadCustomerFormat(type){
          var a = document.createElement('a');
          a.href = customerFormatExportUrl(type);
          a.target = '_blank';
          a.rel = 'noopener';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        }
        $('#stc-customer-format-pdf').on('click', function(){ downloadCustomerFormat('pdf'); });
        $('#stc-customer-format-excel').on('click', function(){ downloadCustomerFormat('excel'); });
        $('#stc-customer-format-word').on('click', function(){ downloadCustomerFormat('word'); });
        function hideCustomerFormatModal(){
          $('#stcCustomerFormatModal').removeClass('show').css('display', 'none').attr('aria-hidden', 'true');
          $('.modal-backdrop').remove();
          $('body').removeClass('modal-open');
          $('#stc-customer-format-frame').attr('src', 'about:blank');
        }
        $('#stcCustomerFormatModal').on('hidden.bs.modal', function(){
          $('#stc-customer-format-frame').attr('src', 'about:blank');
        });
        $(document).on('click', '#stcCustomerFormatModal [data-dismiss="modal"]', function(){
          if (!$.fn.modal) hideCustomerFormatModal();
        });

        function bindTableSearch(){
          var $rows = $('#verifyChallanTable tbody tr');
          $rows.each(function(i){ $(this).data('origSl', i + 1); });
          $('#tableSearch').off('keyup.challanSearch').on('keyup.challanSearch', function(){
            var val = $(this).val().trim();
            var valLower = val.toLowerCase();
            $rows = $('#verifyChallanTable tbody tr');
            $rows.each(function(){
              var text = $(this).text().toLowerCase();
              $(this).toggle(text.indexOf(valLower) > -1);
            });
            var sl = 0;
            $('#verifyChallanTable tbody tr:visible').each(function(){
              sl++;
              $(this).find('.dr-slno').text(sl);
            });
            var pmNo = basePmNo;
            if (val.length >= 2) {
              pmNo += ' (' + val.charAt(0).toUpperCase() + '-' + val.charAt(val.length - 1).toUpperCase() + ')';
            } else if (val.length === 1) {
              pmNo += ' (' + val.charAt(0).toUpperCase() + ')';
            }
            $('#pmNoDisplay').text(pmNo);
          });
        }
        bindTableSearch();
      });
    </script>
  </body>
</html>

