<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_trading_user_id'])){
    header('location:../index.html');
}

if(isset($_GET['pid'])){
    unset($_SESSION["stc_silent_print_prevew"]);
    $num = $_GET['pid'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT * FROM `stc_trading_sale`
      INNER JOIN `stc_trading_customer` 
      ON `stc_trading_customer_id`=`stc_trading_sale_cust_id`
      WHERE `stc_trading_sale_id`='".$_GET['pid']."'
    ");
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_date=$get_stc_purchase_product['stc_trading_sale_date'];
    $get_purchase_product_merchant_name=$get_stc_purchase_product['stc_trading_customer_title'];
    $get_purchase_product_notes=$get_stc_purchase_product['stc_trading_sale_remarks'];
    $yearchange=date('Y', strtotime($get_purchase_product_date));
    $monthchange=date('m', strtotime($get_purchase_product_date));
    $yearchangevalue='';
    if($yearchange=="2020" && $monthchange>3){
      $yearchangevalue="20-21";
    }elseif($yearchange=="2020" && $monthchange<=3){
      $yearchangevalue="19-20";
    }elseif($yearchange=="2021" && $monthchange>3){
      $yearchangevalue="21-22";
    }elseif($yearchange=="2021" && $monthchange<=3){
      $yearchangevalue="20-21";
    }elseif($yearchange=="2022" && $monthchange>3){
      $yearchangevalue="22-23";
    }elseif($yearchange=="2022" && $monthchange<=3){
      $yearchangevalue="21-22";
    }elseif($yearchange=="2023" && $monthchange>3){
      $yearchangevalue="23-24";
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Retail Invoice - STC-<?php echo substr("000{$get_stc_purchase_product['stc_trading_sale_id']}", -$str_length);?></title>

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="../../stc_symbiote/css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="../../stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="../../stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="../../stc_symbiote/css/awsomeminho.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <style>
      .fa-arrow-left {
        font-size: 20px;
        padding: 5px;
        background: yellow;
        border-radius: 50%;
        margin: 0;
        color: #e63535;
      }
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

      .billshowvalue h6:nth-child(9) {
        float: right;
        width: 50%;
        text-align: right;
        padding-right: 71px;
      }

      .text-left h6 span{
        font-size: 10px;
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
            margin-top: -58px;
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
   <?php 
   $prevvalue=0;
   $nextvalue=0;
   $prevvalue=$_GET['pid'];
   $nextvalue=$_GET['pid'];
   ?>
    <div class="text-right hidden-print">
      <a href="silent-invoice-print-preview.php?pid=<?php echo --$prevvalue;?>" class="btn btn-info" style="float: left" ><i class="fas fa-arrow-left"></i> </a>
      <a href="silent-invoice-print-preview.php?pid=<?php echo ++$nextvalue;?>" class="btn btn-info"><i class="fas fa-arrow-right"></i></a>
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
      <button class="btn btn-info"><i class="fas fa-file-pdf-o"></i> Export as PDF</button>
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
          <div style="height: 70px;"><img style="height: 70px;" src="../../stc_symbiote/img/stc-header.png"></div>
          <p>
            Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand 832110
          </p>
          <p>
            Mobile No. &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;+91-7004376229<br>
            E-Mail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;stc111213@gmail.com                     
          </p>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
          <h1 align="center"><b>RETAIL INVOICE</b></h1>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-2">
            <a target="_blank" id="logo_print_pre" href="#">
              <img src="../../stc_symbiote/img/stc_logo.png">
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
                    Invoice No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>STC/<?php echo substr("0000{$get_stc_purchase_product['stc_trading_sale_id']}", -5).'/';?>22-23</b>
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
                </div>
              </div>
            </div>
              
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col cust-ref-sec">
                <h6>
                  Transportation Mode&nbsp;&nbsp;&nbsp; : <b> <?php echo $get_stc_purchase_product['stc_trading_sale_delever_through']; ?></b>
                </h6>
                <h6>
                  Way Bill No
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  : <b>NA</b>
                </h6>
                <h6>
                  LR No
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  : <b> NA</b>
                </h6>
                <h6>
                  Date of Supply &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
                  <b>With in days</b>
                </h6>
                <h6>
                  Place of Supply&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 
                  <b>
                    NA
                  </b>
                </h6>
              </div>
            </div>
          </div>
          <?php 
          if($get_stc_purchase_product['stc_trading_sale_status']=='returned'){
            echo '
          <div class="row return-setion">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div class="row">
                <div class="col-lg-12 retrun">
                  <h1 class="text-center"><p style="border: 2px solid red;color: red;transform: rotate(331deg);position: fixed;top: 50%;left: 0;font-size: 200px;z-index:100;">RETURNED</p></h1>
                </div>
              </div>
            </div>
          </div>
            ';
          }else{
            echo "";
          }
          ?>
          <div class="row ship-bill-section">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col invoice-to">
                <div class="text-gray-light"><b>Bill To Party :</b></div>
                <h5 class="to"><?php echo $get_purchase_product_merchant_name; ?></h5>
                <div class="address">Address : <?php echo $get_stc_purchase_product['stc_trading_customer_address']; ?></div>
                <div class="address">Contact Person : <?php echo $get_purchase_product_merchant_name; ?></div>
                <div class="email">Contact No : <a ><?php echo $get_stc_purchase_product['stc_trading_customer_cont_no']; ?></a></div>
                <div class="email">Email &nbsp;: <a >NA</a></div>
                <div class="address">GSTIN : NA</div>
                <div class="email">State &nbsp;&nbsp;: JHARKHAND</div>
              </div>
            </div>
              
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col invoice-to">
                <div class="text-gray-light"><b>Ship To Party :</b></div>
                <h5 class="to"><?php echo $get_purchase_product_merchant_name; ?></h5>
                <div class="address">Address : <?php echo $get_stc_purchase_product['stc_trading_sale_cust_address']; ?></div>
                <div class="address">Contact Person : <?php echo $get_purchase_product_merchant_name; ?></div>
                <div class="email">Contact No : <a ><?php echo $get_stc_purchase_product['stc_trading_customer_cont_no']; ?></a></div>
                <div class="email">Email &nbsp;: <a >NA</a></div>
                <div class="address">GSTIN : NA</div>
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
                    <th class="text-right">UNIT</th>
                    <th class="text-right">QTY</th>
                    <th class="text-right">RATE</th>
                    <th class="text-right">AMOUNT</th>
                  </tr>
                  <?php
                    $sl=0;
                    $total=0;
                    $totalgst=0;
                    $amount=0;
                    $gstamount=0;
                    $mtye='';
                    $checkpurchaseorderitems=mysqli_query($con, "
                      SELECT
                        `stc_trading_sale_items_id`,
                        `stc_trading_sale_items_sale_id`,
                        `stc_trading_product_title`,
                        `stc_trading_sale_items_item_qty`,
                        `stc_trading_sale_items_item_price`,
                        `stc_trading_sale_items_item_unit`,
                        `stc_trading_sale_items_item_gst`
                      FROM
                        `stc_trading_sale_items`
                      INNER JOIN `stc_trading_product` 
                      ON `stc_trading_product_id` = `stc_trading_sale_items_item_id`
                      WHERE `stc_trading_sale_items_sale_id`='".$get_stc_purchase_product['stc_trading_sale_id']."'
                      ORDER BY `stc_trading_product_title` ASC
                    ");
                    $sl=0;
                    foreach ($checkpurchaseorderitems as $key => $value2) {
                      // $gstamount=0;
                      $sl++;
                      $amount=round($value2['stc_trading_sale_items_item_qty'], 2) * round($value2['stc_trading_sale_items_item_price'], 2);
                      $gstamount = (round($value2['stc_trading_sale_items_item_price'], 2) * $value2["stc_trading_sale_items_item_gst"])/100;

                      $stcelecrate=round($value2['stc_trading_sale_items_item_price'], 2) + $gstamount;
                      $stcelectotal=round($stcelecrate, 2) * round($value2['stc_trading_sale_items_item_qty'], 2);
                        ?>     
                      <tr>
                        <td class="no"><?php echo $sl;?></td>
                        <td class="text-left"><h6><?php echo $value2['stc_trading_product_title'];?></h6></td>
                        <td class="qty"><?php echo $value2['stc_trading_sale_items_item_unit'];?></td>
                        <td class="total"><?php echo number_format(round($value2['stc_trading_sale_items_item_qty'], 2), 2);?></td>
                        <td class="total"><?php echo number_format($stcelecrate, 2);?></td>
                        <td class="total"><?php echo number_format($stcelectotal, 2);?></td>
                      </tr>
                      <?php                    
                            $total += $stcelectotal;
                            $totalgst +=$gstamount;
                    }
                  ?>
              </table>
            </div>
          </div>
          <div class="row footer" style="margin-top: 10px;">
            <!-- total and charges -->
            <div class="col-xl-12 col-lg-12 col-md-12 uparse">
              <div class="pre-cond-plus-total-taxa">
              </div>
              <div class="pre-cond-plus-total-taxb">                
                <table border="0" cellspacing="0" cellpadding="0" id="amount-row-calc">
                  <tr class="">
                    <td align="right">TOTAL :</td>
                    <td align="left">
                      <b><?php 
                        echo $finalvalue=number_format($stcelectotal, 2).'<br>';
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
              $amt_words=str_replace(',', '', $stcelectotal);
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
    <?php include "../../stc_symbiote/footer.php";?>
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
