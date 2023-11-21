<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_empl_id'])){
    header('location:index.html');
}
?>
<?php 
if(isset($_GET['pid'])){
    $num = $_GET['pid'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT * FROM `stc_quotation`
      LEFT JOIN `stc_customer`
      ON `stc_quotation_customer_id`=`stc_customer_id`
      WHERE `stc_quotation_id`='".$_GET['pid']."'
    ");
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_date=$get_stc_purchase_product['stc_quotation_date'];
    $get_purchase_product_merchant_name=$get_stc_purchase_product['stc_customer_name'];
    $get_purchase_product_notes=$get_stc_purchase_product['stc_quotation_tandc'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Quotation - STC-<?php echo $str;?></title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/awsomeminho.css">

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
          background: ##7d6161;
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
          color: #7d6161;
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

          .invoice footer {
              position: absolute;
              bottom: 10px;
              page-break-after: always;
          }

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
            position: fixed;
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

          #logo_print_pre{
            margin-top: 6px;
            float: right;
            margin-right: 0px;
          }

          #logo_print_pre{
            margin-top: 6px;
            float: right;
            margin-right: 0px;
          }
      }
    </style>
  </head>

  <body>
    <div class="text-right hidden-print">
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
      <button class="btn btn-info"><i class="fas fa-file-pdf-o"></i> Export as PDF</button>
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row header">
        <!-- Create order -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
          <div style="height: 50px;"><img style="height: 50px;" src="https://stcassociate.com/stc_symbiote/img/stc-header.png"></div>
          <p>
            Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110
          </p>
          <p>
            Mobile No. : +91-8986811304<br>
            E.Mail:stc111213@gmail.com<br>
            GSTIN: 20JCBPS6008G1ZT           
          </p>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
          <h2 align="center">QUOTATION</h2>
          <div style="float: right;"  >
            <h4 align="right">Quotation No : STC/Q/<?php echo $str; ?></h4>
            <h4 align="right">Quotation Date : <?php echo date('d-m-Y',strtotime($get_purchase_product_date)); ?></h4>
          </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-2">
            <a target="_blank" id="logo_print_pre" href="#">
              <img src="https://stcassociate.com/stc_symbiote/img/stc_logo.png">
              <!-- <h2><strong><b>STC</b></strong></h2> -->
            </a>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <!-- Create order -->
          <div class="row" style="margin-top: 10px;">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="">
                <h4><b>Kind Attn: Mr/Miss. <?php echo $get_stc_purchase_product['stc_customer_contact_person']; ?></b></h4>
              </div>
              <div style="font-size: 20px;" class="">
                <p>Dear Sir/Madam,<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    It is a pleasure to associate with you through your following product in our progressive journey. Please arrange to deliver the same.
                </p>
              </div>
            </div>
            <!-- table -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="0" cellspacing="0" cellpadding="0">
                <!-- <thead> -->
                  <tr>
                      <th>#</th>
                      <th width="50%" class="text-left">ITEMS</th>
                      <th class="text-right">HSN CODE</th>
                      <th class="text-right">UNIT</th>
                      <th class="text-right">QTY</th>
                      <th class="text-right">RATE</th>
                      <th class="text-right">TOTAL</th>
                  </tr>
                  <?php
                    $sl=0;
                    $total=0;
                    $totalgst='';
                    $mtype='';
                      $checkpurchaseorderitems=mysqli_query($con, "
                        SELECT * FROM `stc_quotation_items`
                        LEFT JOIN `stc_product`
                        ON `stc_product_id`=`stc_quotation_items_product_product_id`
                        LEFT JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        WHERE `stc_quotation_items_quotation_id`='".$_GET['pid']."'
                      ");
                      while ($row=mysqli_fetch_assoc($checkpurchaseorderitems)) {
                        $sl++;
                        if($row['stc_sub_cat_name']=="OTHERS"){
                          $mtype='';
                        }else{
                          $mtype=$row['stc_sub_cat_name'];
                        }
                        $total=$row['stc_quotation_items_rate'] * $row['stc_quotation_items_qty'];
                    ?>                  
                  <tr>
                    <td class="no"><?php echo $sl;?></td>
                    <td class="text-left"><h6><?php echo $mtype;?> <?php echo $row['stc_product_name'];?></h6></td>
                    <td class="unit"><?php echo $row['stc_product_hsncode'];?></td>
                    <td class="qty"><?php echo $row['stc_product_unit'];?></td>
                    <td class="total"><?php echo number_format($row['stc_quotation_items_qty'], 2);?></td>
                    <td class="total"><?php echo number_format($row['stc_quotation_items_rate'], 2);?></td>
                    <td class="total"><?php echo number_format($total, 2);?></td>
                  </tr>
                  <?php
                    }
                  ?>
              </table>
            </div>                        
          </div>
        </div>
      </div>
      <!-- footer -->
      <div class="row">
          
        <!-- Create order -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 block-foot">
          <div class="col-xl-12 col-lg-6 col-md-12">
            <div class="notices">
              <div>Terms & Conditions:</div>
              <div class="notice">
                <p><?php echo nl2br($get_purchase_product_notes);?></p>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-6 col-md-12">
            <div class="">
              <div>For <b>STC ASSOCIATES</b></div>
              <div>
                <p>NAUSHER KHAN</p>
              </div>
            </div>
          </div>
          <h4 align="center">Office Add: Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110, INDIA, Ph. No : 0567-123456, E-mail : stc111213@gmail.com.</h4>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "https://stcassociate.com/stc_symbiote/footer.php";?>
    <script>
      $(document).ready(function(){
         $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data){
                window.print();
                return true;
            }
        });
      });
    </script>
  </body>
</html>
<?php
}
?>
