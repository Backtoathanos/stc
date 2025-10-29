<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);

STCAuthHelper::checkAuth(); 
?>
<?php 
if(isset($_GET['pid'])){
    unset($_SESSION["stc_print_prevew"]);
    $num = $_GET['pid'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT * FROM `stc_sale_product_bill`
      LEFT JOIN `stc_sale_product_bill_no`
      ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
      LEFT JOIN `stc_sale_product`
      ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
      LEFT JOIN `stc_customer`
      ON `stc_sale_product_cust_id`=`stc_customer_id`
      WHERE `stc_sale_product_bill_id`='".$_GET['pid']."'
      AND `stc_sale_product_bill_no_bill_series`='1'
    ");
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_date=$get_stc_purchase_product['stc_sale_product_bill_date'];
    $get_purchase_product_merchant_name=$get_stc_purchase_product['stc_customer_name'];
    $get_purchase_product_notes=$get_stc_purchase_product['stc_sale_product_notes'];
    $yearchange = date('Y', strtotime($get_purchase_product_date));
    $monthchange = date('m', strtotime($get_purchase_product_date));
    $yearchangevalue = '';

    // Convert year and month to integers
    $year = intval($yearchange);
    $month = intval($monthchange);

    // Determine the fiscal year range
    if ($month > 3) {
        $startYear = $year % 100;
        $endYear = ($year + 1) % 100;
    } else {
        $startYear = ($year - 1) % 100;
        $endYear = $year % 100;
    }

    // Format the year change value
    $yearchangevalue = sprintf('%02d-%02d', $startYear, $endYear);
?>
<?php
  $value5=0;
  $value12=0;
  $value18=0;
  $value28=0;
  $gstval5=0;
  $gstval12=0;
  $gstval18=0;
  $gstval28=0;
  $sepvalqry=mysqli_query($con, "
    SELECT 
      `stc_sale_product_id`,
      `stc_sale_product_dc_keys`
    FROM `stc_sale_product_bill` 
    INNER JOIN `stc_sale_product_bill_no` 
    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
    INNER JOIN `stc_sale_product` 
    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
    WHERE `stc_sale_product_bill_id`='".$_GET['pid']."'
    AND `stc_sale_product_bill_no_bill_series`='1'
  ");

  foreach($sepvalqry as $comrow){
    if($comrow['stc_sale_product_dc_keys']=="directchallaned"){
      $nestsepqry=mysqli_query($con, "
        SELECT 
          `stc_product_gst`,
          `stc_sale_product_dc_items_sale_product_id`,
          `stc_sale_product_dc_items_product_qty`,
          `stc_sale_product_dc_items_product_sale_rate`
        FROM `stc_sale_product_dc_items`
        INNER JOIN `stc_product` 
        ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
        WHERE `stc_sale_product_dc_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
      ");
      foreach($nestsepqry as $nestrow){
        $value=0;
        if($nestrow['stc_product_gst']==5){
          $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
          $value5+=$value;
          $gstval5+=(round($value, 2) * 5)/100;
        }elseif($nestrow['stc_product_gst']==12){
          $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
          $value12+=$value;
          $gstval12+=(round($value, 2) * 12)/100;
        }elseif($nestrow['stc_product_gst']==18){
          $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
          $value18+=$value;
          $gstval18+=(round($value, 2) * 18)/100;
        }else{
          $value=round($nestrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestrow['stc_sale_product_dc_items_product_sale_rate'], 2);
          $value28+=$value;
          $gstval28+=(round($value, 2) * 28)/100;
        }
      }
    }elseif($comrow['stc_sale_product_dc_keys']=="virtualchallaned"){
      $nestsepqry=mysqli_query($con, "
        SELECT 
          `stc_product_gst`,
          `stc_sale_product_vc_items_sale_product_id`,
          `stc_sale_product_vc_items_product_qty`,
          `stc_sale_product_vc_items_product_sale_rate`
        FROM `stc_sale_product_vc`
        INNER JOIN `stc_product` 
        ON `stc_product_id`=`stc_sale_product_vc_items_product_id` 
        WHERE `stc_sale_product_vc_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
      ");
      foreach($nestsepqry as $nestrow){
        $value=0;
        if($nestrow['stc_product_gst']==5){
          $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
          $value5+=$value;
          $gstval5+=(round($value, 2) * 5)/100;
        }elseif($nestrow['stc_product_gst']==12){
          $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
          $value12+=$value;
          $gstval12+=(round($value, 2) * 12)/100;
        }elseif($nestrow['stc_product_gst']==18){
          $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
          $value18+=$value;
          $gstval18+=(round($value, 2) * 18)/100;
        }else{
          $value=round($nestrow['stc_sale_product_vc_items_product_qty'],2) * round($nestrow['stc_sale_product_vc_items_product_sale_rate'],2);
          $value28+=$value;
          $gstval28+=(round($value, 2) * 28)/100;
        }
      }
    }else{
      $nestsepqry=mysqli_query($con, "
        SELECT 
          `stc_product_gst`,
          `stc_sale_product_items_sale_product_id`,
          `stc_sale_product_items_product_qty`,
          `stc_sale_product_items_product_sale_rate`
        FROM `stc_sale_product_items`
        INNER JOIN `stc_product` 
        ON `stc_product_id`=`stc_sale_product_items_product_id` 
        WHERE `stc_sale_product_items_sale_product_id`='".$comrow['stc_sale_product_id']."'
      ");
      foreach($nestsepqry as $nestrow){
        $value=0;
        if($nestrow['stc_product_gst']==5){
          $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
          $value5+=$value;
          $gstval5+=(round($value, 2) * 5)/100;
        }elseif($nestrow['stc_product_gst']==12){
          $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
          $value12+=$value;
          $gstval12+=(round($value, 2) * 12)/100;
        }elseif($nestrow['stc_product_gst']==18){
          $value=round($nestrow['stc_sale_product_items_product_qty'], 2) * round($nestrow['stc_sale_product_items_product_sale_rate'], 2);
          $value18+=$value;
          $gstval18+=(round($value, 2) * 18)/100;
        }else{
          $value=round($nestrow['stc_sale_product_items_product_qty'],2) * round($nestrow['stc_sale_product_items_product_sale_rate'],2);
          $value28+=$value;
          $gstval28+=(round($value, 2) * 28)/100;
        }
      }
    }
  }

  if($get_stc_purchase_product['stc_customer_state_id']==16){
    $igstval5=0;
    $igstval12=0;
    $igstval18=0;
    $igstval28=0;                    
  }else{
    $igstval5=$gstval5;
    $igstval12=$gstval12;
    $igstval18=$gstval18;
    $igstval28=$gstval28;
    $gstval5=0;
    $gstval12=0;
    $gstval18=0;
    $gstval28=0;  
  }

  function RemoveSpecialChar($str) {
    // Using str_replace() function
    // to replace the word
    $res = str_replace( array( '\'', '"',
    ',' , ';', '<', '>', '#' ), ' ', $str);

    // Returning the result
    return $res;
  }

  $sitename=RemoveSpecialChar($get_stc_purchase_product['stc_sale_product_sitename']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tax Invoice - STC-<?php echo substr("0000{$_GET['invono']}", -$str_length);?></title>
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
          width: 50%;
        }

        .billshowvalue h6:nth-child(9) {
          float: left;
          width: 50%;
          text-align: right;
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
      <a href="invoicepdf.php?invoice_id=<?php 
        echo $num.
          '&cdate='.date('d-m-Y',strtotime($get_purchase_product_date)).
          '&invo_no='.$get_stc_purchase_product['stc_sale_product_bill_no'].
          '&cust_po_no='.$get_stc_purchase_product['stc_sale_product_cust_order_no'].
          '&cust_po_date='.date('d-m-Y',strtotime($get_stc_purchase_product['stc_sale_product_cust_order_date'])).
          '&refrence='.$get_stc_purchase_product['stc_sale_product_refrence'].
          '&waybill='.$get_stc_purchase_product['stc_customer_gstin'].
          '&dos='.$get_stc_purchase_product['stc_sale_product_dosupply'].
          '&pos='.$get_stc_purchase_product['stc_sale_product_posupply'].
          '&sitename='.$sitename.
          '&cust_id='.$get_stc_purchase_product['stc_customer_state_id'].
          '&cust_name='.$get_purchase_product_merchant_name.
          '&cust_address='.$get_stc_purchase_product['stc_customer_address'].
          '&cust_cont_person='.$get_stc_purchase_product['stc_customer_contact_person'].
          '&cust_cont_no='.$get_stc_purchase_product['stc_customer_phone'].
          '&cust_email='.$get_stc_purchase_product['stc_customer_email'].
          '&cust_gst='.$get_stc_purchase_product['stc_customer_gstin'].
          '&cust_site_addres='.$get_stc_purchase_product['stc_sale_product_siteaddress'].
          '&cust_site_cont_person='.$get_stc_purchase_product['stc_sale_product_cont_person'].
          '&cust_site_cont_no='.$get_stc_purchase_product['stc_sale_product_cont_number'];
        ?>" class="btn btn-info">
      <i class="fas fa-file-pdf-o"></i> Export as PDF</a>
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row header">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <div style="float: right;">
            <h4><b>ORIGINAL FOR RECIPIENT</b></h4>
          </div>
        </div>
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
          <h1 align="center"><b>TAX INVOICE</b></h1>
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
                    Invoice No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>STC/<?php echo substr("0000{$get_stc_purchase_product['stc_sale_product_bill_no']}", -5).'/'.$yearchangevalue;?></b>
                  </h6>
                  <h6>
                    Invoice Date &nbsp;&nbsp;: <b><?php echo date('d-m-Y',strtotime($get_purchase_product_date)); ?></b>
                  </h6>
                  <h6>
                    Reverse Charge (Y/N) &nbsp;: <b>NO</b>
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
                  <h6>
                    Agent Code &nbsp;&nbsp;: <b><?php 
                      if($get_stc_purchase_product['stc_sale_product_agent_id']!=0){
                        echo $get_stc_purchase_product['stc_sale_product_agent_id'];
                      }else{
                        echo "NA";
                      }

                    ?></b>
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
                  : <b>
                      <?php echo $get_stc_purchase_product['stc_sale_product_way_bill_no'];?>
                    </b>
                </h6>
                <h6>
                  LR No
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  : <b> 
                      <?php echo $get_stc_purchase_product['stc_sale_product_lr_no'];?>
                    </b>
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
                    <th class="text-right">HSN CODE</th>
                    <th class="text-right">UNIT</th>
                    <th class="text-right">QTY</th>
                    <th class="text-right">RATE</th>
                    <th class="text-right">AMOUNT</th>
                    <th class="text-right">GST</th>
                    <th class="text-right">TOTAL</th>
                  </tr>
                  <?php
                    $sl=0;
                    $total=0;
                    $totalgst=0;
                    $amount=0;
                    $gstamount=0;
                    $mtye='';
                    if($get_stc_purchase_product['stc_sale_product_dc_keys']=="directchallaned"){
                      $checkpurchaseorderitems=mysqli_query($con, "
                        SELECT 
                          `stc_product_id`,
                          `stc_sale_product_dc_items_product_id`,
                          `stc_product_name`,
                          `stc_product_hsncode`,
                          `stc_product_unit`,
                          `stc_sale_product_dc_items_product_qty`,
                          `stc_sale_product_dc_items_product_sale_rate`,
                          `stc_product_gst`,
                          `stc_sub_cat_name`,
                          `stc_product_brand_id`
                        FROM `stc_sale_product_dc_items` 
                        INNER JOIN `stc_sale_product`
                        ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
                        INNER JOIN `stc_product`
                        ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
                        INNER JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        INNER JOIN `stc_sale_product_bill_no`
                        ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                        INNER JOIN `stc_sale_product_bill`
                        ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                        WHERE `stc_sale_product_bill_id`='".$_GET['pid']."'
                        AND `stc_sale_product_bill_no_bill_series`='1'
                        ORDER BY `stc_product_desc` ASC
                      ");

                      foreach ($checkpurchaseorderitems as $key => $value) {
                        $brand=' MAKE - ';
                        $query=mysqli_query($con, "
                          SELECT * FROM `stc_brand` WHERE `stc_brand_id`='".$value['stc_product_brand_id']."'
                        ");
                        if(mysqli_num_rows($query)>0){
                          foreach($query as $brandrow){
                            if($brandrow['stc_brand_id']==0){
                              $brand='';
                            }else{
                              $brand.=$brandrow['stc_brand_title'];
                            }
                            break;
                          }
                        }else{
                          $brand='';
                        }
                            
                        $pdname=$value["stc_product_name"].' '.$brand;
                        if(isset($_SESSION["stc_print_prevew"])) {  
                          $is_available = 0;
                          foreach($_SESSION["stc_print_prevew"] as $keys => $values){  
                            if(($_SESSION["stc_print_prevew"][$keys]['product_id'] == $value["stc_product_id"]) && ($_SESSION["stc_print_prevew"][$keys]['product_price'] == $value["stc_sale_product_dc_items_product_sale_rate"])){  
                                 $is_available++;  
                                 $_SESSION["stc_print_prevew"][$keys]['product_quantity'] = $_SESSION["stc_print_prevew"][$keys]['product_quantity'] + $value["stc_sale_product_dc_items_product_qty"];  
                            }  
                          }  
                          if($is_available < 1) {  
                            $item_array = array(  
                                 'product_id'              =>     $value["stc_product_id"], 
                                 'product_cat'              =>     $value["stc_sub_cat_name"],
                                 'product_name'              =>     $pdname,
                                 'product_hsncode'            =>     $value["stc_product_hsncode"],
                                 'product_unit'               =>     $value["stc_product_unit"],
                                 'product_quantity'          =>     $value["stc_sale_product_dc_items_product_qty"],
                                 'product_price'            =>     $value["stc_sale_product_dc_items_product_sale_rate"],
                                 'product_gst'             =>     $value["stc_product_gst"]
                            );  
                            $_SESSION["stc_print_prevew"][] = $item_array;  
                          }
                        }else{  
                          $item_array = array(  
                                 'product_id'             =>     $value["stc_product_id"], 
                                 'product_cat'             =>     $value["stc_sub_cat_name"],
                                 'product_name'             =>     $pdname,
                                 'product_hsncode'            =>     $value["stc_product_hsncode"],
                                 'product_unit'                 =>     $value["stc_product_unit"],
                                 'product_quantity'           =>     $value["stc_sale_product_dc_items_product_qty"],
                                 'product_price'            =>     $value["stc_sale_product_dc_items_product_sale_rate"],
                                 'product_gst'            =>     $value["stc_product_gst"]
                          );   
                          $_SESSION["stc_print_prevew"][] = $item_array;  
                        }  
                      }
                      $sl=0;
                      foreach ($_SESSION["stc_print_prevew"] as $key => $value2) {

                        $sl++;
                        $amount=round($value2['product_quantity'], 2) * round($value2['product_price'], 2);
                        $gstamount = (round($amount, 2) * $value2["product_gst"])/100;
                        if($value2['product_cat']=='OTHERS'){
                          $mtye='';
                        }else{
                          $mtye=$value2['product_cat'];
                        }
                          ?>     
                        <tr>
                          <td class="no"><?php echo $sl;?></td>
                          <td class="text-left"><h6><?php echo $mtye?> <?php echo $value2['product_name'];?></h6></td>
                          <td class="unit"><?php echo $value2['product_hsncode'];?></td>
                          <td class="qty"><?php echo $value2['product_unit'];?></td>
                          <td class="total"><?php echo number_format($value2['product_quantity'], 2);?></td>
                          <td class="total"><?php echo number_format($value2['product_price'], 2);?></td>
                          <td class="total"><?php echo number_format($amount, 2);?></td>
                          <td class="total"><?php echo $value2["product_gst"];?>%</td>
                          <td class="total"><?php echo number_format($amount + $gstamount, 2);?></td>
                        </tr>
                        <?php                    
                              $total = $total + $amount;
                              $totalgst +=$gstamount;
                      }                      
                    }elseif($get_stc_purchase_product['stc_sale_product_dc_keys']=="virtualchallaned"){
                      $checkpurchaseorderitems=mysqli_query($con, "
                        SELECT 
                          `stc_product_id`,
                          `stc_sale_product_vc_items_product_id`,
                          `stc_product_name`,
                          `stc_product_hsncode`,
                          `stc_product_unit`,
                          `stc_sale_product_vc_items_product_qty`,
                          `stc_sale_product_vc_items_product_sale_rate`,
                          `stc_product_gst`,
                          `stc_sub_cat_name`,
                          `stc_product_brand_id`
                        FROM `stc_sale_product_vc` 
                        INNER JOIN `stc_sale_product`
                        ON `stc_sale_product_vc_items_sale_product_id`=`stc_sale_product_id`
                        INNER JOIN `stc_product`
                        ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
                        INNER JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        INNER JOIN `stc_sale_product_bill_no`
                        ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                        INNER JOIN `stc_sale_product_bill`
                        ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                        WHERE `stc_sale_product_bill_id`='".$_GET['pid']."'
                        AND `stc_sale_product_bill_no_bill_series`='1'
                        ORDER BY `stc_product_desc` ASC
                      ");

                      foreach ($checkpurchaseorderitems as $key => $value) {
                        $brand=' MAKE - ';
                        $query=mysqli_query($con, "
                          SELECT * FROM `stc_brand` WHERE `stc_brand_id`='".$value['stc_product_brand_id']."'
                        ");
                        if(mysqli_num_rows($query)>0){
                          foreach($query as $brandrow){
                            if($brandrow['stc_brand_id']==0){
                              $brand='';
                            }else{
                              $brand.=$brandrow['stc_brand_title'];
                            }
                            break;
                          }
                        }else{
                          $brand='';
                        }
                            
                            
                        $pdname=$value["stc_product_name"].' '.$brand;
                        if(isset($_SESSION["stc_print_prevew"])) {  
                          $is_available = 0;
                          foreach($_SESSION["stc_print_prevew"] as $keys => $values){  
                            if(($_SESSION["stc_print_prevew"][$keys]['product_id'] == $value["stc_product_id"]) && ($_SESSION["stc_print_prevew"][$keys]['product_price'] == $value["stc_sale_product_vc_items_product_sale_rate"])){  
                                 $is_available++;  
                                 $_SESSION["stc_print_prevew"][$keys]['product_quantity'] = $_SESSION["stc_print_prevew"][$keys]['product_quantity'] + $value["stc_sale_product_vc_items_product_qty"]; 
                            }  
                          }  
                          if($is_available < 1) {  
                            $item_array = array(  
                                 'product_id'             =>     $value["stc_product_id"], 
                                 'product_cat'             =>     $value["stc_sub_cat_name"],
                                 'product_name'             =>     $pdname,
                                 'product_hsncode'           =>     $value["stc_product_hsncode"],
                                 'product_unit'              =>     $value["stc_product_unit"],
                                 'product_quantity'         =>     $value["stc_sale_product_vc_items_product_qty"],
                                 'product_price'           =>     $value["stc_sale_product_vc_items_product_sale_rate"],
                                 'product_gst'            =>     $value["stc_product_gst"]
                            );  
                            $_SESSION["stc_print_prevew"][] = $item_array;  
                          }
                        }else{  
                          $item_array = array(  
                                 'product_id'              =>     $value["stc_product_id"], 
                                 'product_cat'              =>     $value["stc_sub_cat_name"],
                                 'product_name'              =>     $pdname,
                                 'product_hsncode'            =>     $value["stc_product_hsncode"],
                                 'product_unit'               =>     $value["stc_product_unit"],
                                 'product_quantity'          =>     $value["stc_sale_product_vc_items_product_qty"],
                                 'product_price'            =>     $value["stc_sale_product_vc_items_product_sale_rate"],
                                 'product_gst'             =>     $value["stc_product_gst"]
                          );   
                          $_SESSION["stc_print_prevew"][] = $item_array;  
                        }  
                      }
                      $sl=0;
                      foreach ($_SESSION["stc_print_prevew"] as $key => $value2) {

                        $sl++;
                        $amount=round($value2['product_quantity'], 2) * round($value2['product_price'], 2);
                        $gstamount = (round($amount, 2) * $value2["product_gst"])/100;
                        if($value2['product_cat']=='OTHERS'){
                          $mtye='';
                        }else{
                          $mtye=$value2['product_cat'];
                        }
                          ?>     
                        <tr>
                          <td class="no"><?php echo $sl;?></td>
                          <td class="text-left"><h6><?php echo $mtye?> <?php echo $value2['product_name'];?></h6></td>
                          <td class="unit"><?php echo $value2['product_hsncode'];?></td>
                          <td class="qty"><?php echo $value2['product_unit'];?></td>
                          <td class="total"><?php echo number_format($value2['product_quantity'], 2);?></td>
                          <td class="total"><?php echo number_format($value2['product_price'], 2);?></td>
                          <td class="total"><?php echo number_format($amount, 2);?></td>
                          <td class="total"><?php echo $value2["product_gst"];?>%</td>
                          <td class="total"><?php echo number_format($amount + $gstamount, 2);?></td>
                        </tr>
                        <?php
                              $total = $total + $amount;
                              $totalgst +=$gstamount;
                      }
                    }else{
                      $checkpurchaseorderitems=mysqli_query($con, "
                        SELECT 
                          `stc_product_id`,
                          `stc_sale_product_items_id`,
                          `stc_product_name`,
                          `stc_product_hsncode`,
                          `stc_product_unit`,
                          `stc_sale_product_items_product_qty`,
                          `stc_sale_product_items_product_sale_rate`,
                          `stc_product_gst`,
                          `stc_sub_cat_name`,
                          `stc_product_brand_id`
                        FROM `stc_sale_product_items` 
                        INNER JOIN `stc_sale_product`
                        ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                        INNER JOIN `stc_product`
                        ON `stc_sale_product_items_product_id`=`stc_product_id`
                        INNER JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        INNER JOIN `stc_sale_product_bill_no`
                        ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                        INNER JOIN `stc_sale_product_bill`
                        ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                        WHERE `stc_sale_product_bill_id`='".$_GET['pid']."'
                        AND `stc_sale_product_bill_no_bill_series`='1'
                        ORDER BY `stc_product_desc` ASC
                      ");

                      foreach ($checkpurchaseorderitems as $key => $value) {
                        $brand=' MAKE - ';
                        $query=mysqli_query($con, "
                          SELECT * FROM `stc_brand` WHERE `stc_brand_id`='".$value['stc_product_brand_id']."'
                        ");
                        if(mysqli_num_rows($query)>0){
                          foreach($query as $brandrow){
                            if($brandrow['stc_brand_id']==0){
                              $brand='';
                            }else{
                              $brand.=$brandrow['stc_brand_title'];
                            }
                            break;
                          }
                        }else{
                          $brand='';
                        }
                            
                            
                        $pdname=$value["stc_product_name"].' '.$brand;
                        if(isset($_SESSION["stc_print_prevew"])) {  
                          $is_available = 0;
                          foreach($_SESSION["stc_print_prevew"] as $keys => $values){  
                            if(($_SESSION["stc_print_prevew"][$keys]['product_id'] == $value["stc_product_id"]) && ($_SESSION["stc_print_prevew"][$keys]['product_price'] == $value["stc_sale_product_items_product_sale_rate"])){  
                                 $is_available++;  
                                 $_SESSION["stc_print_prevew"][$keys]['product_quantity'] = $_SESSION["stc_print_prevew"][$keys]['product_quantity'] + $value["stc_sale_product_items_product_qty"];  
                            }
                          }  
                          if($is_available < 1) {  
                            $item_array = array(  
                                 'product_id'             =>     $value["stc_product_id"], 
                                 'product_cat'              =>     $value["stc_sub_cat_name"],
                                 'product_name'               =>     $pdname,
                                 'product_hsncode'              =>     $value["stc_product_hsncode"],
                                 'product_unit'                 =>     $value["stc_product_unit"],
                                 'product_quantity'           =>     $value["stc_sale_product_items_product_qty"],
                                 'product_price'            =>     $value["stc_sale_product_items_product_sale_rate"],
                                 'product_gst'            =>     $value["stc_product_gst"]
                            );  
                            $_SESSION["stc_print_prevew"][] = $item_array;  
                          }
                        }else{  
                          $item_array = array(  
                                 'product_id'             =>     $value["stc_product_id"], 
                                 'product_cat'              =>     $value["stc_sub_cat_name"],
                                 'product_name'               =>     $pdname,
                                 'product_hsncode'              =>     $value["stc_product_hsncode"],
                                 'product_unit'                 =>     $value["stc_product_unit"],
                                 'product_quantity'           =>     $value["stc_sale_product_items_product_qty"],
                                 'product_price'            =>     $value["stc_sale_product_items_product_sale_rate"],
                                 'product_gst'            =>     $value["stc_product_gst"]
                          );   
                          $_SESSION["stc_print_prevew"][] = $item_array; 
                        }  
                      }
                      $sl=0;
                      foreach ($_SESSION["stc_print_prevew"] as $key => $value2) {

                        $sl++;
                        $amount=round($value2['product_quantity'], 2) * round($value2['product_price'], 2);
                        $gstamount = (round($amount, 2) * $value2["product_gst"])/100;
                        if($value2['product_cat']=='OTHERS'){
                          $mtye='';
                        }else{
                          $mtye=$value2['product_cat'];
                        }
                          ?>     
                        <tr>
                          <td class="no"><?php echo $sl;?></td>
                          <td class="text-left"><h6><?php echo $mtye?> <?php echo $value2['product_name'];?></h6></td>
                          <td class="unit"><?php echo $value2['product_hsncode'];?></td>
                          <td class="qty"><?php echo $value2['product_unit'];?></td>
                          <td class="total"><?php echo number_format($value2['product_quantity'], 2);?></td>
                          <td class="total"><?php echo number_format($value2['product_price'], 2);?></td>
                          <td class="total"><?php echo number_format($amount, 2);?></td>
                          <td class="total"><?php echo $value2["product_gst"];?>%</td>
                          <td class="total"><?php echo number_format($amount + $gstamount, 2);?></td>
                        </tr>
                        <?php                    
                              $total = $total + $amount;
                              $totalgst +=$gstamount;
                      }
                    }
                  ?>
              </table>
            </div>
          </div>
          <div class="row footer" style="margin-top: 10px;">
            <!-- total and charges -->
            <div class="col-xl-12 col-lg-12 col-md-12 uparse">
              <div class="pre-cond-plus-total-taxa">
                <table>
                  <thead>
                    <tr>
                      <th>Tax Name</th>
                      <th>Basic</th>
                      <th>CGST</th>
                      <th>SGST</th>
                      <th>IGST</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>5%</td>
                      <td><?php echo number_format($value5, 2);?></td>
                      <td><?php echo number_format($gstval5/2, 2);?></td>
                      <td><?php echo number_format($gstval5/2, 2);?></td>
                      <td><?php echo number_format($igstval5, 2);?></td>
                    </tr>
                    <tr>
                      <td>12%</td>
                      <td><?php echo number_format($value12, 2);?></td>
                      <td><?php echo number_format($gstval12/2, 2);?></td>
                      <td><?php echo number_format($gstval12/2, 2);?></td>
                      <td><?php echo number_format($igstval12, 2);?></td>
                    </tr>
                    <tr>
                      <td>18%</td>
                      <td><?php echo number_format($value18, 2);?></td>
                      <td><?php echo number_format($gstval18/2, 2);?></td>
                      <td><?php echo number_format($gstval18/2, 2);?></td>
                      <td><?php echo number_format($igstval18, 2);?></td>
                    </tr>
                    <tr>
                      <td>28%</td>
                      <td><?php echo number_format($value28, 2);?></td>
                      <td><?php echo number_format($gstval28/2, 2);?></td>
                      <td><?php echo number_format($gstval28/2, 2);?></td>
                      <td><?php echo number_format($igstval28, 2);?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <?php
                $gstfc=(
                $get_stc_purchase_product["stc_sale_product_freight_charge"] * $get_stc_purchase_product["stc_sale_product_gst_calc_value"])/100;
                $gstpf=($get_stc_purchase_product["stc_sale_product_packing_and_forwarding_charge"] * $get_stc_purchase_product["stc_sale_product_gst_calc_value"])/100;
                $finalcharges=($gstfc + $get_stc_purchase_product["stc_sale_product_freight_charge"]) + ($gstpf + $get_stc_purchase_product["stc_sale_product_packing_and_forwarding_charge"]);
                $grtotal= $total + $finalcharges;

                $finalgstcal= $totalgst + $gstpf + $gstfc;
              ?>
              <div class="pre-cond-plus-total-taxb">                
                <table border="0" cellspacing="0" cellpadding="0" id="amount-row-calc">
                  <tr class="">
                    <td align="right">SUBTOTAL :</td>
                    <td align="left"><b><?php echo number_format($total, 2);?></b></td>
                  </tr>
                  <tr class="">
                    <td align="right">PACKING & FORWARDING : </td>
                    <td align="left"><b><?php echo number_format($get_stc_purchase_product["stc_sale_product_packing_and_forwarding_charge"], 2);?></b></td>
                  </tr>
                  <tr class="">
                    <td align="right">FREIGHT CHARGES : </td>
                    <td align="left"><b><?php echo number_format($get_stc_purchase_product["stc_sale_product_freight_charge"], 2);?></b></td>
                  </tr>
                  <?php  
                  $tcskeeper=0;
                  $outtcs=0;
                  $checkarr=array();
                  $tcssql=mysqli_query($con, "
                    SELECT 
                      `stc_sale_product_id`,
                      `stc_sale_product_bill_id`, 
                      `stc_sale_product_items_product_sale_rate`, 
                      `stc_sale_product_items_product_qty` 
                    FROM `stc_sale_product`
                    INNER JOIN `stc_sale_product_items`
                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                    WHERE `stc_sale_product_cust_id`='".$get_stc_purchase_product['stc_customer_id']."'
                    AND `stc_sale_product_bill_no_bill_series`='1'
                    ORDER BY `stc_sale_product_bill_id` ASC
                  ");

                  foreach ($tcssql as $tcsvalue) {
                    $checkval=0;
                    $tcskeeper = $tcsvalue['stc_sale_product_items_product_sale_rate'] * $tcsvalue['stc_sale_product_items_product_qty'];
                    $checkval = $outtcs + $tcskeeper;
                    if($checkval>=100000000){
                      break;
                    }else{
                      $outtcs += $tcsvalue['stc_sale_product_items_product_sale_rate'] * $tcsvalue['stc_sale_product_items_product_qty'];
                      $checkarr[]= array($tcsvalue["stc_sale_product_bill_id"]);
                    }
                    $tcskeeper=0;
                  }
                  $tcsvalue=0;
                  if(in_array($_GET['pid'], $checkarr)){ 
                    $tcsvalue=0; 
                  }else{
                    // $tcsvalue=(($totalgst + $grtotal) * 0.075)/100;
                  }
                      if($get_stc_purchase_product['stc_customer_state_id']==16){
                        echo '
                            <tr class="">
                                <td align="right">CGST :</td>
                                <td align="right"><b>'.number_format($finalgstcal/2, 2).'</b></td>
                            </tr>
                            <tr class="">
                                <td align="right">SGST :</td>
                                <td align="right"><b>'.number_format($finalgstcal/2, 2).'</b></td>
                            </tr>                                
                              ';
                      }else{
                        echo '
                            <tr class="">
                                <td align="right">IGST :</td>
                                <td align="right"><b>'.number_format($finalgstcal, 2).'</b></td>
                            </tr>
                        ';
                      }
                  ?>
                  <tr class="">
                    <td align="right">TCS @0.075 :</td>
                    <td align="left"><b><?php echo round($tcsvalue, 2);?></b></td>
                  </tr>               
                  <tr class="">
                    <td align="right">GRAND TOTAL :</td>
                    <td align="left">
                      <b><?php 
                        $grandtotal=round($totalgst + $grtotal + $tcsvalue, 2);
                        echo number_format(ceil($grandtotal), 2);
                        // echo '<br>'.$grandtotal;
                      ?></b>
                    </td>
                  </tr>
                </table>
              </div>
            </div>         

            <!-- for bank details -->
            <div class="col-xl-12 col-lg-12 col-md-12 invoice-setion">
              <div class="uparse">
                <div class="pre-cond-plus-total-taxa">
                  <H5>Bank Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: AXIS BANK</H5>
                  <H5>Branch Name &nbsp;&nbsp;&nbsp;&nbsp;: AZADNAGAR</H5>
                  <H5>A/c No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 920020051082641</H5>
                  <H5>IFSC Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: UTIB0003459</H5>
                </div>
                <div class="pre-cond-plus-total-taxb">      
            <?php
            // Create a function for converting the amount in words
              function AmountInWords($amount){
                 $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
                 // Check if there is any number after decimal
                 $amt_hundred = null;
                 $count_length = strlen($num);
                 $x = 0;
                 $string = array();
                 $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
                   3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
                   7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                   10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
                   13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                   16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
                   19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
                   40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
                   70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
                  $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
                  while( $x < $count_length ) {
                    $get_divider = ($x == 2) ? 10 : 100;
                    $amount = floor($num % $get_divider);
                    $num = floor($num / $get_divider);
                    $x += $get_divider == 10 ? 1 : 2;
                    if ($amount) {
                     $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                     $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                     $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                     '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                     '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
                      }
                 else $string[] = null;
                 }
                 $implode_to_Rupees = implode('', array_reverse($string));
                 $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
                 " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
                 return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
              }

              $amt_words=$totalgst + $total;
              // nummeric value in variable
             
              $get_amount= AmountInWords($amt_words);
            ?>          
                  <h5><b>Invoice Amount (In Words)</b></h5>
                  <p><?php echo $get_amount;?> Only</p>
                </div>
              </div>
            </div>
            <!-- signatory & term & condition -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="pre-cond-plus-total-taxa">
                <h4><b>Terms & Conditions</b></h4>
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
        var height = $('tr').height();

        if(height>20){
          var totalRowCount = 0;
          var rowCount = 0;
          var table = document.getElementById("stc-table-count");
          var rows = table.getElementsByTagName("tr")
          for (var i = 0; i < rows.length; i++) {
              totalRowCount++;
              if (rows[i].getElementsByTagName("td").length > 0) {
                  rowCount++;
              }
          }
          var html = 
          '<tr><td colspan="9">Continue......</td></tr><tr style="height: 275px;"><td colspan="9"></td></tr><tr><th>#</th><th width="30%" class="text-left">ITEMS</th><th class="text-right">HSN CODE</th><th class="text-right">UNIT</th><th class="text-right">QTY</th><th class="text-right">RATE</th><th class="text-right">AMOUNT</th></tr>';
            
          $('table > tbody > tr').eq(18).after(html);
        }
      });
    </script>
  </body>
</html>
<?php
}
?>