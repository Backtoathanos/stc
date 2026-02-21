<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
require_once 'kattegat/auth_helper.php';
STCAuthHelper::checkAuth();

include "../MCU/db.php";

$date = '';
if(isset($_GET['date']) && $_GET['date'] != ''){
  $date = date('Y-m-d', strtotime($_GET['date']));
} else if(isset($_GET['begdate']) && $_GET['begdate'] != ''){
  // Backward compatibility
  $date = date('Y-m-d', strtotime($_GET['begdate']));
} else {
  $date = date('Y-m-d');
}

$pm_no = 'STC/DC/VERIFY/'.date('dmY', strtotime($date));
$pm_date = date('d-m-Y', strtotime($date));
$site_name = 'Multiple';
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
        border-bottom: 1px solid #fff;
        border: 1px solid #000;
      }
      .invoice table th {
        white-space: nowrap;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
      }
      .invoice table td {
        background: #fff;
        font-size: 13px;
      }
      .invoice{
        margin-top: 0 !important;
      }
      @media print {
        body{ margin: 0 !important; }
        .hidden-print { visibility: hidden; }
        .tm-footer { visibility: hidden; }
        .tm-mt-big{ margin-top: 0 !important; }
        .tm-mb-big{ margin-bottom: 0 !important; }
        .invoice{ margin-top: -5px !important; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; }
        #logo_print_pre{
          position: relative;
          float: right;
          right: -180px;
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
            <h4 align="left">P.M No : <?php echo $pm_no; ?></h4>
            <h4 align="left">P.M Date : <?php echo $pm_date; ?></h4>
            <h4 align="left">Site Name : <?php echo $site_name; ?></h4>
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
          <div style="overflow-x:auto;">
            <table class="table table-bordered table-hover" style="color:#000;">
              <thead>
                <tr>
                  <th>Sl No</th>
                  <th>PR No &amp; Date</th>
                  <th>PR Location</th>
                  <th>Sitename</th>
                  <th>Item Desc</th>
                  <th>Unit</th>
                  <th>Dispatched Qty</th>
                  <th>Req From</th>
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
                    L.`stc_cust_super_requisition_list_id` AS requisition_id,
                    L.`stc_cust_super_requisition_list_date` AS requisition_date,
                    P.`stc_cust_project_title` AS sitename,
                    S.`stc_cust_pro_supervisor_fullname` AS req_from,
                    S.`stc_cust_pro_supervisor_contact` AS req_from_contact,
                    C.`stc_requisition_combiner_id` AS pr_no,
                    C.`stc_requisition_combiner_date` AS pr_date,
                    C.`stc_requisition_combiner_refrence` AS pr_location
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
                  WHERE DATE(VA.`created_date`) = '".$date."'
                  ORDER BY TIMESTAMP(VA.`created_date`) DESC, VA.`id` DESC
                ");

                if($sql && mysqli_num_rows($sql) > 0){
                  while($row = mysqli_fetch_assoc($sql)){
                    $sl++;
                    $prLocation = $row['pr_location'] ? $row['pr_location'] : '-';
                    $prNoDate = '-';
                    if($row['pr_no']){
                      $prNoDate = $row['pr_no'].' <br>'.date('d-m-Y', strtotime($row['pr_date']));
                    }
                    $reqFrom = $row['req_from'];
                    if($row['req_from_contact']){
                      $reqFrom .= '<br>'.$row['req_from_contact'];
                    }
                ?>
                  <tr>
                    <td class="text-center"><?php echo $sl; ?></td>
                    <td class="text-center"><?php echo $prNoDate; ?></td>
                    <td><?php echo htmlspecialchars($prLocation); ?></td>
                    <td><?php echo htmlspecialchars($row['sitename']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['item_desc'])); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td class="text-right"><b><?php echo number_format((float)$row['accepted_qty'], 2); ?></b></td>
                    <td><?php echo $reqFrom; ?></td>
                    <td></td>
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

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "../stc_symbiote/footer.php";?>
    <script>
      $(document).ready(function(){
        $('#printInvoice').click(function(){
          window.print();
        });
        $('.filterbydate').on('click', function(){
          var date=$('.vdate').val();
          window.location.href="verify-challan.php?date=" + encodeURIComponent(date);
        });
      });
    </script>
  </body>
</html>

