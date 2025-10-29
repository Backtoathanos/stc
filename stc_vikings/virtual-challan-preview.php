<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
STCAuthHelper::checkAuth(); 
?>
<?php 
if(isset($_GET['vcid'])){
    $num = $_GET['vcid'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT * FROM `stc_sale_product`
      LEFT JOIN `stc_customer`
      ON `stc_sale_product_cust_id`=`stc_customer_id`
      WHERE `stc_sale_product_id`='".$_GET['vcid']."'
    ");
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_date=$get_stc_purchase_product['stc_sale_product_date'];
    $get_purchase_product_merchant_name=$get_stc_purchase_product['stc_customer_name'];
    $get_purchase_product_notes=$get_stc_purchase_product['stc_sale_product_notes'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Challan - STC-DC-<?php echo $str;?></title>
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
            padding: 0px;
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

        .billshowvalue h6{
          /*float: left;*/
          font-size: 15px;
        }
    
        .billshowvalue h6:nth-child(1) {
          float: left;
          width: 100%;
        }

        .billshowvalue h6:nth-child(2) {
          float: left;
          width: 50%;
        }

        .billshowvalue h6:nth-child(3) {
          float: right;
          width: 50%;
          text-align: right;
        }

        .billshowvalue h6:nth-child(4) {
          float: left;
          width: 100%;
        }

        .billshowvalue h6:nth-child(5) {
          float: left;
          width: 100%;
        }      

        .billshowvalue h6:nth-child(6) {
          float: left;
          width: 50%;
        }
    
        .billshowvalue h6:nth-child(7) {
          float: right;
          width: 50%;
          text-align: right;
        }

        .billshowvalue h6:nth-child(8) {
          float: left;
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
              margin-top: -28px;
              margin-bottom: 10px;
          }

          .invoice .med{
              position: absolute;
              top:250px; 
              bottom: 0px;
          }

          .block-foot{
              position: fixed;
              margin-bottom: 0px;
          }

          .table{
            height: 100%;
          }

          #logo_print_pre{
              float: right;
              margin-right: 100px;
          }

          .cust-ref-sec p{
            font-size: 20px;
          }

          .invoice-setion{
            border-bottom: 2px solid;
          }

          .ship-bill-section{
            border-bottom: 2px solid;
            padding-bottom: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
          }
          .pre-cond-plus-total-taxa{
            padding-top: 20px;
            float: left;
            width: 60%;
          }

          .pre-cond-plus-total-taxb{
            padding-top: 20px;
            float: left;
            width: 40%;
          }

          #amount-row-calc{
            width: 95%;
          }
          #amount-row-calc tr td{
            text-align: right;
            right: 10px;
          }

          #signatory-stc{
            margin-left: 10px;
            margin-right: 10px; 
            text-align: center;
          }

          .footer{
            position: absolute;
            bottom: 0px;
          }

          .uparse{
            border-top: 2px solid;
          }

          .billshowvalue h6{
            float: left;
          }
      
          .billshowvalue h6:nth-child(1) {
            float: left;
            width: 100%;
          }

          .billshowvalue h6:nth-child(2) {
            float: left;
            width: 50%;
          }

          .billshowvalue h6:nth-child(3) {
            float: right;
            width: 50%;
            text-align: right;
          }

          .billshowvalue h6:nth-child(4) {
            float: left;
            width: 100%;
          }

          .billshowvalue h6:nth-child(5) {
            float: left;
            width: 100%;
          }      

          .billshowvalue h6:nth-child(6) {
            float: left;
            width: 50%;
          }
      
          .billshowvalue h6:nth-child(7) {
            float: right;
            width: 50%;
            text-align: right;
          }

          .billshowvalue h6:nth-child(8) {
            float: left;
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
          <div style="height: 70px;"><img style="height: 70px;" src="https://stcassociate.com/stc_symbiote/img/stc-header.png"></div>
          <p>
            Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110
          </p>
          <p>
            Mobile No. &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;+91-8986811304<br>
            E-Mail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;stc111213@gmail.com                     
          </p>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
          <h1 align="center"><b>DELIVERY CHALLAN</b></h1>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-2">
            <a target="_blank" id="logo_print_pre" href="#">
              <img src="https://stcassociate.com/stc_symbiote/img/stc_logo.png">
            </a>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">            
          <div class="row invoice-setion">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="row">
                <div class="col-lg-12 billshowvalue">
                  <h6>
                    <span>GSTIN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>20JCBPS6008G1ZT</b></span>
                  </h6>
                  <h6>
                    Challan No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>STC/DC/<?php echo $str;?></b>
                  </h6>
                  <h6>
                    Challan Date &nbsp;&nbsp;: <b><?php echo date('d-m-Y',strtotime($get_purchase_product_date)); ?></b>
                  </h6>
                  <h6>
                    Reverse Charge (Y/N) &nbsp;:
                  </h6>
                  <h6>
                    State &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>JHARKHAND</b>
                  </h6>
                  <h6>
                    Customer PO No &nbsp;&nbsp;: <b><?php echo $get_stc_purchase_product['stc_sale_product_cust_order_no'];?></b>
                  </h6>
                  <h6>
                    Customer PO Date &nbsp;&nbsp;: <b><?php echo $newDate = date("d-m-Y", strtotime($get_stc_purchase_product['stc_sale_product_cust_order_date']));?></b>
                  </h6>
                  <h6>
                    Reference &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 
                    <b>
                      <?php echo $get_stc_purchase_product['stc_sale_product_refrence'];?>
                    </b>
                  </h6>
                </div>
              </div>
            </div>
              
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col cust-ref-sec">
                <h6>
                  Transportation Mode&nbsp;&nbsp;&nbsp; : <b> Any Local Transport</b>
                </h6>
                <h6>
                  Way Bill No
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  : <b> NA</b>
                </h6>
                <h6>
                  LR No
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  : <b> NA</b>
                </h6>
                <h6>
                  Date of Supply &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
                  <b>
                    <?php echo $get_stc_purchase_product['stc_sale_product_dosupply'];?>
                  </b>
                </h6>
                <h6>
                  Place of Supply&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
                  <b>
                    <?php echo $get_stc_purchase_product['stc_sale_product_posupply'];?>
                  </b>
                </h6>
                <h6>
                  Site Name 
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  : 
                  <b>
                    <?php echo $get_stc_purchase_product['stc_sale_product_sitename'];?>
                  </b>
                </h6>
              </div>
            </div>
          </div>
          <div class="row ship-bill-section">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col invoice-to">
                <div class="text-gray-light"><b>Bill To Party :</b></div>
                <h5 class="to"><?php echo $get_purchase_product_merchant_name; ?></h5>
                <div class="address">Address : <?php echo $get_stc_purchase_product['stc_customer_address']; ?></div>
                <div class="address">Contact Person : <?php echo $get_stc_purchase_product['stc_customer_contact_person']; ?></div>
                <div class="email">Contact No : <a ><?php echo $get_stc_purchase_product['stc_customer_phone']; ?></a></div>
                <div class="email">Email &nbsp;: <a ><?php echo $get_stc_purchase_product['stc_customer_email']; ?></a></div>
                <div class="address">GSTIN : <?php echo $get_stc_purchase_product['stc_customer_gstin']; ?></div>
                <div class="email">State &nbsp;&nbsp;: JHARKHAND</div>
              </div>
            </div>
              
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col invoice-to">
                <div class="text-gray-light"><b>Ship To Party [Delivery Address] :</b></div>
                <h5 class="to"><?php echo $get_purchase_product_merchant_name; ?></h5>
                <div class="address">Address : <?php echo $get_stc_purchase_product['stc_sale_product_siteaddress']; ?></div>
                <div class="address">Contact Person : <?php echo $get_stc_purchase_product['stc_sale_product_cont_person']; ?></div>
                <div class="email">Contact No : <a ><?php echo $get_stc_purchase_product['stc_sale_product_cont_number']; ?></a></div>
                <div class="email">Email &nbsp;: <a ><?php echo $get_stc_purchase_product['stc_customer_email']; ?></a></div>
                <div class="address">GSTIN : <?php echo $get_stc_purchase_product['stc_customer_gstin']; ?></div>
                <div class="email">State &nbsp;&nbsp;: JHARKHAND</div>
              </div>
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <!-- table line items -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="0" cellspacing="0" cellpadding="0">
                <!-- <thead> -->
                  <tr>
                      <th>#</th>
                      <th width="30%" class="text-left">ITEMS</th>
                      <th class="text-right">QTY</th>
                      <th class="text-right">UNIT</th>
                      <th class="text-right">RATE</th>
                      <th class="text-right">GST</th>
                      <th class="text-right">Rack</th>
                  </tr>
                  <?php
                    $sl=0;
                    $total=0;
                    $totalgst=0;
                    $mtye='';
                      $checkpurchaseorderitems=mysqli_query($con, "
                        SELECT * FROM `stc_sale_product_vc`
                        LEFT JOIN `stc_product`
                        ON `stc_product_id`=`stc_sale_product_vc_items_product_id`
                        LEFT JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        LEFT JOIN `stc_rack`
                        ON `stc_product_rack_id`=`stc_rack_id`
                        WHERE `stc_sale_product_vc_items_sale_product_id`='".$_GET['vcid']."'
                      ");
                      while ($row=mysqli_fetch_assoc($checkpurchaseorderitems)) {
                        $sl++;
                        $amount=$row['stc_sale_product_vc_items_product_qty'] * $row['stc_sale_product_vc_items_product_sale_rate'];
                        $gstamount = ($amount * $row["stc_product_gst"])/100;
                        if($row['stc_sub_cat_name']=='OTHERS'){
                          $mtye='';
                        }else{
                          $mtye=$row['stc_sub_cat_name'];
                        }
                    ?>     
                  <tr>
                    <td class="no"><?php echo $sl;?></td>
                    <td class="text-left"><h6><?php echo $mtye?> <?php echo $row['stc_product_name'];?></h6></td>
                    <td class="total"><?php echo number_format($row['stc_sale_product_vc_items_product_qty'], 2);?></td>
                    <td class="qty"><?php echo $row['stc_product_unit'];?></td>
                    <td class="total"><?php echo number_format($row['stc_sale_product_vc_items_product_sale_rate'], 2);?></td>
                    <td class="total"><?php echo $row["stc_product_gst"];?>%</td>
                    <td class="total"><?php echo $row["stc_rack_name"];?></td>
                  </tr>
                  <?php                    
                    $total = $total + $amount;
                    $totalgst +=$gstamount;
                    }
                  ?>
              </table>
            </div>
          </div>
          <div class="row footer">
            <!-- signatory & term & condition -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="pre-cond-plus-total-taxa">
                <!--<h4><b>Terms & Conditions</b></h4>-->
              </div>
              <div class="pre-cond-plus-total-taxb">                
                <div id="signatory-stc">
                  <p>For <span><b>STC ASSOCIATES</b></span></p>
                  <br><br><br> 
                  <p><span align="center">Authorised Signatory</span></p>
                </div>
              </div>
            </div>
          </div>
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