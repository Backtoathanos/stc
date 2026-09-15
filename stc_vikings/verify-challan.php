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
$orderListQ = mysqli_query($con, "
  SELECT DISTINCT TRIM(L.`stc_cust_super_requisition_list_order_number`) AS order_number
  ".$challanFrom."
  AND TRIM(COALESCE(L.`stc_cust_super_requisition_list_order_number`, '')) <> ''
  ORDER BY order_number ASC
");
if($orderListQ){
  while($or = mysqli_fetch_assoc($orderListQ)){
    if($or['order_number'] !== ''){
      $order_options[] = $or['order_number'];
    }
  }
}

$siteFrom = $challanFrom;
if($order_number !== ''){
  $siteFrom .= " AND L.`stc_cust_super_requisition_list_order_number` = '".mysqli_real_escape_string($con, $order_number)."'";
}
$site_options = array();
$siteSeen = array();
$siteListQ = mysqli_query($con, "
  SELECT DISTINCT
    TRIM(COALESCE(L.`stc_cust_super_requisition_list_order_number`, '')) AS order_number,
    P.`stc_cust_project_title` AS sitename,
    C.`stc_requisition_combiner_refrence` AS pr_location
  ".$siteFrom."
");
if($siteListQ){
  while($sr = mysqli_fetch_assoc($siteListQ)){
    $label = stc_challan_combination_label($sr['sitename'] ?? '', $sr['pr_location'] ?? '');
    if($label === '') continue;
    $key = strtoupper($label);
    $on = trim((string)($sr['order_number'] ?? ''));
    if(!isset($siteSeen[$key])){
      $siteSeen[$key] = array(
        'sitename' => $label,
        'order_number' => $on
      );
    }elseif($siteSeen[$key]['order_number'] === '' && $on !== ''){
      $siteSeen[$key]['order_number'] = $on;
    }
  }
  $site_options = array_values($siteSeen);
  usort($site_options, function($a, $b){
    return strcasecmp($a['sitename'], $b['sitename']);
  });
}

if($site_label !== '' && $order_number === ''){
  foreach($site_options as $so){
    if(strcasecmp($so['sitename'], $site_label) === 0 && $so['order_number'] !== ''){
      $order_number = $so['order_number'];
      break;
    }
  }
}

$filter_sql = '';
if($order_number !== ''){
  $filter_sql .= " AND L.`stc_cust_super_requisition_list_order_number` = '".mysqli_real_escape_string($con, $order_number)."'";
}

$selected_site_title = $site_label;
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
            <li data-value="<?php echo htmlspecialchars($soName); ?>" data-order="<?php echo htmlspecialchars($so['order_number'] ?? ''); ?>" class="<?php echo ($site_label === $soName) ? 'is-active' : ''; ?>">
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
            <h4 align="left">P.M Date : <?php echo $pm_date; ?></h4>
            <?php if($order_number !== ''){ ?>
              <h4 align="left">Order Number : <?php echo htmlspecialchars($order_number); ?></h4>
            <?php } ?>
            <?php if($selected_site_title !== ''){ ?>
              <h4 align="left">Site : <?php echo htmlspecialchars($selected_site_title); ?></h4>
            <?php } ?>
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
              <thead>
                <tr>
                  <th>Sl No</th>
                  <th>Sitename</th>
                  <th>Item Desc</th>
                  <th>Unit</th>
                  <th>Dispatched Qty</th>
                  <?php if($selected_site_title!='TATA STEEL AMC'){ ?>
                    <th>Rack</th>
                    <th>Req From</th>
                  <?php } ?>
                  <th>Sign</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sl = 0;
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
                    $reqFrom = $row['req_from'];
                    if($row['req_from_contact']){
                      $reqFrom .= '<br>'.$row['req_from_contact'];
                    }
                    $displaySite = stc_challan_site_label($row['sitename'], $row['pr_location']);
                    $combinationName = stc_challan_combination_label($row['sitename'], $row['pr_location']);
                    if($site_label !== '' && strcasecmp($combinationName, $site_label) !== 0){
                      continue;
                    }
                    $sl++;
                    $sitename = htmlspecialchars($displaySite);
                    $item_id_esc = mysqli_real_escape_string($con, $row['item_id']);
                    $query2 = mysqli_query($con, "
                        SELECT GROUP_CONCAT(DISTINCT RK.`stc_rack_name` ORDER BY RK.`stc_rack_name` SEPARATOR ', ') AS stc_rack_name
                        FROM `stc_cust_super_requisition_list_items_rec` REC
                        INNER JOIN `stc_purchase_product_adhoc` APA ON APA.`stc_purchase_product_adhoc_id` = REC.`stc_cust_super_requisition_list_items_rec_list_poaid`
                        LEFT JOIN `stc_rack` RK ON RK.`stc_rack_id` = APA.`stc_purchase_product_adhoc_rackid`
                        WHERE REC.`stc_cust_super_requisition_list_items_rec_list_item_id` = '".$item_id_esc."'
                    ");
                    $rackRow = mysqli_num_rows($query2)>0 ? mysqli_fetch_assoc($query2) : array();
                    $rack = ($rackRow['stc_rack_name'] ?? '') ?: '-';
                ?>
                  <tr>
                    <td class="text-center dr-slno"><?php echo $sl; ?></td>
                    <td><?php echo $sitename; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['item_desc'])); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td class="text-right"><b><?php echo number_format((float)$row['accepted_qty'], 2); ?></b></td>
                    <?php if($selected_site_title!='TATA STEEL AMC'){ ?>
                      <td><?php echo htmlspecialchars($rack); ?></td>
                      <td><?php echo $reqFrom; ?></td>
                    <?php } ?>
                    <td><span style="opacity: 0;">..................................</span></td>
                  </tr>
                <?php
                  }
                }else{
                  echo '<tr><td colspan="9" class="text-center">No accepted items found for this date.</td></tr>';
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
        $('#printInvoice').click(function(){
          window.print();
        });
        function challanFilterUrl(orderNo, siteLabel){
          var date = $('.vdate').val();
          if (typeof orderNo === 'undefined') orderNo = $('.vorder-number').val() || '';
          if (typeof siteLabel === 'undefined') siteLabel = $('.vsite').val() || '';
          var url = 'verify-challan.php?date=' + encodeURIComponent(date);
          if (orderNo) url += '&order_number=' + encodeURIComponent(orderNo);
          if (siteLabel) url += '&site=' + encodeURIComponent(siteLabel);
          return url;
        }
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
        $('.stc-dd-menu').on('click', function(e){ e.stopPropagation(); });
        $('#stc-dd-order .stc-dd-menu li').on('click', function(){
          var orderNo = $(this).attr('data-value') || '';
          window.location.href = challanFilterUrl(orderNo, '');
        });
        $('#stc-dd-site .stc-dd-menu li').on('click', function(){
          var siteLabel = $(this).attr('data-value') || '';
          var orderNo = siteLabel ? ($(this).attr('data-order') || '') : ($('.vorder-number').val() || '');
          window.location.href = challanFilterUrl(orderNo, siteLabel);
        });
        $('.filterbydate').on('click', function(){
          window.location.href = challanFilterUrl();
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

        var basePmNo = '<?php echo addslashes($pm_no); ?>';
        var $rows = $('#verifyChallanTable tbody tr');
        $rows.each(function(i){ $(this).data('origSl', i + 1); });
        function renumberSlNo(){
          var sl = 0;
          $('#verifyChallanTable tbody tr:visible').each(function(){
            sl++;
            $(this).find('.dr-slno').text(sl);
          });
        }
        $('#tableSearch').on('keyup', function(){
          var val = $(this).val().trim();
          var valLower = val.toLowerCase();
          $rows.each(function(){
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(valLower) > -1);
          });
          renumberSlNo();
          var pmNo = basePmNo;
          if (val.length >= 2) {
            pmNo += ' (' + val.charAt(0).toUpperCase() + '-' + val.charAt(val.length - 1).toUpperCase() + ')';
          } else if (val.length === 1) {
            pmNo += ' (' + val.charAt(0).toUpperCase() + ')';
          }
          $('#pmNoDisplay').text(pmNo);
        });
      });
    </script>
  </body>
</html>

