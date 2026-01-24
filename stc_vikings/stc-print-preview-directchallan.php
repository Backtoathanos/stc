<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
// Include authentication helper
require_once 'kattegat/auth_helper.php';

STCAuthHelper::checkAuth(); 
?>
<?php 
if(isset($_GET['requi_id'])){
    $num = $_GET['requi_id'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT
          `stc_requisition_combiner_id`,
          `stc_requisition_combiner_date`,
          `stc_requisition_combiner_refrence`
      FROM `stc_requisition_combiner`
      INNER JOIN `stc_requisition_combiner_req`
      ON `stc_requisition_combiner_req_comb_id`=`stc_requisition_combiner_id`
      INNER JOIN `stc_cust_super_requisition_list`
      ON `stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
      WHERE `stc_requisition_combiner_id`='".$_GET['requi_id']."'
    ");
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_date=$get_stc_purchase_product['stc_requisition_combiner_date'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Requisiton Print - STC-<?php echo $str;?></title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="../stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="../stc_symbiote/css/awsomeminho.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

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
      }

      .invoice table th {
          white-space: nowrap;
          font-weight: 400;
          font-size: 16px;
      }

      .invoice table td h3 {
          margin: 0;
          font-weight: 400;
          color: #bbbed4;
          font-size: 1.2em;
      }

      .invoice table .qty,.invoice table .total,.invoice table .unit {
          text-align: right;
          font-size: 1.2em;
      }

      .invoice table .no {
          color: #fff;
          font-size: 1.6em;
          background: #3989c6;
      }

      .invoice table .unit {
          background: #ddd;
      }

      .invoice table .total {
          /*background: #3989c6;*/
          /*color: #fff;*/
      }

      .invoice table tbody tr:last-child td {
          border: none;
      }

      .invoice table tfoot td {
          background: 0 0;
          border-bottom: none;
          white-space: nowrap;
          text-align: right;
          padding: 10px 20px;
          font-size: 1.2em;
          border-top: 1px solid #aaa;
      }

      .invoice table tfoot tr:first-child td {
          border-top: none;
      }

      .invoice table tfoot tr:last-child td {
          color: #3989c6;
          font-size: 1.4em;
          border-top: 1px solid #3989c6;
      }

      .invoice table tfoot tr td:first-child {
          border: none;
      }
      
      .tfootar{
        font-size: 20px;
        font-weight: bold;
      }

      .invoice{
        background-color: #FFF;
      }

      @media print {
          .invoice {
            margin-top : -5px;
              font-size: 15px!important;
              overflow: hidden!important;
          }

          /*.invoice footer {
              position: absolute;
              bottom: 10px;
              page-break-after: always;
          }*/

          /*.invoice>div:last-child {
              page-break-before: always;
          }*/

          .hidden-print footer { 
             visibility: hidden; 
          }

          .hidden-print { 
             visibility: hidden; 
          }

          .tm-footer{
            visibility: hidden; 
          }

          .block-foot{
            position: relative;
            bottom: 0px;
          }
          /*tfoot {
            page-break-inside: avoid;
          }*/
          .invoice .header{
            position: relative;
            border-bottom: 2px solid;
            margin-bottom: 10px;
          }

          .invoice .med{
            position: absolute;
            top:250px; 
            bottom: 0px;
          }

          .block-foot{
            position: fixed;
            margin-bottom: -8px;
          }

          .table{
            height: 100%;
          }

          /* #logo_print_pre{
            margin-top: 6px;
            float: right;
            margin-right: 0px;
          }

          #logo_print_pre{
            margin-top: 6px;
            float: right;
            margin-right: 0px;
          } */
          #logo_print_pre{
            position: relative;
            float: right;
            right: -180px;
          }
          
          .stc-table-req{
            width:100%;
          }
      }
    </style>
  </head>

  <body>
    <div class="text-right hidden-print">
        <input type="date" class="btn begdate" value="<?php echo date('Y-m-d'); ?>">
        <input type="date" class="btn enddate" value="<?php echo date('Y-m-d'); ?>">
      <a class="btn btn-info filterbydate"><i class="fas fa-print"></i> Update</a>
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
      <!-- <button class="btn btn-info"><i class="fas fa-file-pdf-o"></i> Export as PDF</button> -->
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row header">
        <!-- Create order -->
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
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-4">
          <h2 align="center" style="font-size:40px;">Delivery Challan</h2>
          <div>
            <h4 align="left">P.M No : STC/DC/<?php echo $str; ?></h4>
            <h4 align="left">P.M Date : <?php echo date('d-m-Y',strtotime($get_purchase_product_date)); ?></h4>
            <h4 align="left">Site Name : <?php echo $get_stc_purchase_product['stc_requisition_combiner_refrence']; ?></h4>
          </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
            <a target="_blank" id="logo_print_pre" href="#">
              <img src="../stc_symbiote/img/stc_logo.png">
            </a>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <div class="row">
            <!-- table -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table class="stc-table-req" style="color: black;">
                    <tr style="border:1px solid black;">
                        <th style="border:1px solid black;" class="text-center">#</th>
                        <th style="border:1px solid black;" class="text-center">----------------SITE NAME----------------</th>
                        <th style="border:1px solid black;" class="text-center" width="70%" class="text-left">---------------------------------------ITEMS DESCRIPTIONS-----------------------------</th>
                        <th style="border:1px solid black;" class="text-center">RACK</th>
                        <th style="border:1px solid black;" class="text-center">UNIT</th>
                        <th style="border:1px solid black;" class="text-center">QTY</th>
                        <th style="border:1px solid black;" class="text-center">----SIGNATURE----</th>
                        <th style="border:1px solid black;" class="text-center">--------DATE--------</th>
                    </tr>
                    <?php
                      $sl = 0;
                      $begdate = isset($_GET['begdate']) ? date('Y-m-d', strtotime($_GET['begdate'])) : '';
                      $enddate = isset($_GET['enddate']) ? date('Y-m-d', strtotime($_GET['enddate'])) : '';
                      $datefilter = ($begdate && $enddate) ? "AND DATE(rrec.stc_cust_super_requisition_list_items_rec_date) BETWEEN '$begdate' AND '$enddate'" : '';

                      $query = mysqli_query($con, "
                          SELECT 
                              items.stc_cust_super_requisition_list_id,
                              items.stc_cust_super_requisition_list_items_req_id,
                              items.stc_cust_super_requisition_list_items_title,
                              items.stc_cust_super_requisition_list_items_unit,
                              items.stc_cust_super_requisition_items_priority,
                              proj.stc_cust_project_title,
                              sup.stc_cust_pro_supervisor_fullname,
                              IFNULL(SUM(rrec.stc_cust_super_requisition_list_items_rec_recqty), 0) AS dispatchqty
                          FROM stc_cust_super_requisition_list_items AS items
                          INNER JOIN stc_requisition_combiner_req AS comb 
                              ON comb.stc_requisition_combiner_req_requisition_id = items.stc_cust_super_requisition_list_items_req_id
                          LEFT JOIN stc_cust_super_requisition_list AS req 
                              ON req.stc_cust_super_requisition_list_id = items.stc_cust_super_requisition_list_items_req_id
                          LEFT JOIN stc_cust_project AS proj 
                              ON proj.stc_cust_project_id = req.stc_cust_super_requisition_list_project_id
                          LEFT JOIN stc_cust_pro_supervisor AS sup 
                              ON sup.stc_cust_pro_supervisor_id = req.stc_cust_super_requisition_list_super_id
                          LEFT JOIN stc_cust_super_requisition_list_items_rec AS rrec 
                              ON rrec.stc_cust_super_requisition_list_items_rec_list_item_id = items.stc_cust_super_requisition_list_id 
                              AND rrec.stc_cust_super_requisition_list_items_rec_recqty != 0
                              $datefilter
                          WHERE comb.stc_requisition_combiner_req_comb_id = '".$_GET['requi_id']."'
                          GROUP BY items.stc_cust_super_requisition_list_id
                      ");

                      while($row = mysqli_fetch_assoc($query)) {
                          if($row['dispatchqty'] > 0) {
                              $sl++;
                              $priority = ($row['stc_cust_super_requisition_items_priority'] == 2) ? "Urgent" : "";
                              $filterdate="AND DATE(stc_cust_super_requisition_list_items_rec_date) BETWEEN '$begdate' AND '$enddate'";
                              $query2=mysqli_query($con, "
                                  SELECT 
                                      stc_rack_name
                                  FROM stc_purchase_product_adhoc
                                  INNER JOIN `stc_rack`
                                  ON stc_purchase_product_adhoc_rackid = stc_rack_id
                                  INNER JOIN `stc_cust_super_requisition_list_items_rec`
                                  ON stc_purchase_product_adhoc_id = stc_cust_super_requisition_list_items_rec_list_poaid
                                  WHERE stc_cust_super_requisition_list_items_rec_list_item_id = '".$row['stc_cust_super_requisition_list_id']."'
                                  $filterdate
                              ");
                              $rack = mysqli_num_rows($query2)>0?mysqli_fetch_assoc($query2): array('stc_rack_name' => 'N/A');
                              $rack = $rack['stc_rack_name'];
                      ?>
                      <tr style="border:1px solid black;">
                          <td style="border:1px solid black;"><?php echo $sl; ?></td>
                          <td style="border:1px solid black;" class="text-left"><?php echo $row['stc_cust_project_title']; ?></td>
                          <td style="border:1px solid black;" class="text-left">
                              <h6><?php echo nl2br($row['stc_cust_super_requisition_list_items_title']) . ' ' . $priority; ?></h6>
                          </td>
                          <td style="border:1px solid black;" class="unit"><?php echo $rack;?></td>
                          <td style="border:1px solid black;" class="unit"><?php echo $row['stc_cust_super_requisition_list_items_unit']; ?></td>
                          <td style="border:1px solid black;" class="qty"><?php echo number_format($row['dispatchqty'], 2); ?></td>
                          <td style="border:1px solid black;" class="text-center"><?php echo $row['stc_cust_pro_supervisor_fullname']; ?></td>
                          <td style="border:1px solid black;" class="text-center"></td>
                      </tr>
                      <?php
                          }
                      }

                    ?>
                  </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "../stc_symbiote/footer.php";?>
    <script>
      $(document).ready(function(){
         $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data){
                window.print();
                return true;
            }
        });

        $('.filterbydate').on('click', function(){
          var begdate=$('.begdate').val();
          var enddate=$('.enddate').val();
          var req_id="<?php echo $_GET['requi_id'];?>";
          window.location.href="stc-print-preview-directchallan.php?requi_id=" + req_id + "&begdate=" + begdate + "&enddate=" + enddate;
        });
      });
    </script>
  </body>
</html>
<?php
}
?>
