<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>GRN - STC</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="css/awsomeminho.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <style>
      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
      }
    </style>
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big animated flipInX">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">GRN</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create order -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">GRN</h2></a>
              </div>
            </div>
            <?php
            include "../MCU/db.php";
            if(isset($_GET['purchaseorderid'])){
              $lokigrn=mysqli_query($con, "
                SELECT * FROM `stc_purchase_product`
                LEFT JOIN `stc_merchant`
                ON `stc_purchase_product_merchant_id`=`stc_merchant_id`
                WHERE `stc_purchase_product_id`='".$_GET['purchaseorderid']."'
              ");
              $lokigetorder=mysqli_fetch_assoc($lokigrn);
              $str_length = 5;
              // hardcoded left padding if number < $str_length
              $ponumber = substr("0000{$lokigetorder['stc_purchase_product_id']}", -$str_length);
            ?>
            <div class="row stc-grn-row">
              <div class="col-xl-6 col-md-6 col-sm-6">
                <form action="" class="stc-add-grn-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >GRN Number
                  </label>
                  <input
                    name="stcgrnnumber"
                    type="text"
                    placeholder="GRN Number"
                    class="form-control validate"
                    style="background-color: #186abb;"
                    Value=""
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="grndate"
                    >GRN Date
                  </label>
                  <input
                    name="grn_date"
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                    style="background-color: #186abb;"
                    value="<?php echo $date=date('l jS \ F Y h:i:s A');?>"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Purchase Order Number
                  </label>
                  <input
                    name="stcponumber"
                    type="text"
                    placeholder="Purchase order Number"
                    class="form-control validate"
                    style="background-color: #186abb;"
                    Value="<?php echo 'STC/'.$ponumber; ?>"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="orderdate"
                    >Purchase Order Date
                  </label>
                  <input
                    name="expire_date"
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                    style="background-color: #186abb;"
                    value="<?php echo $lokigetorder['stc_purchase_product_order_date'];?>"
                    disabled
                  />
                </div>
              </div>              
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Merchant Name
                  </label>
                  <input
                    id=""
                    name="stcmername"
                    type="text"
                    placeholder="Merchant Name"
                    class="form-control validate"
                    style="background-color: #186abb;"
                    value="<?php echo $lokigetorder['stc_merchant_name'];?>"
                    disabled
                  />
                </div>
              </div> 
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="invoicenumber"
                    >Invoice/Challan Number
                  </label>
                  <input
                    name="stcinvonumber"
                    type="text"
                    placeholder="Challan/Invoice Number"
                    class="form-control validate stcinvonumber"
                    data-large-mode="true"
                    style="background-color: #186abb;"
                    value=""
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="invoicedate"
                    >Invoice/Challan Date
                  </label>
                  <input
                    id="datepicke1r"
                    name="stcinvodate"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Challan Date"
                    class="form-control validate stcinvodate"
                    style="background-color: #186abb;"
                    value=""
                  />
                </div>
              </div>   
              <div class="col-xl-6 col-md-6 col-sm-12">
                <h2 class="tm-block-title d-inline-block">Products</h2> 
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12 showgrn">
                <a href="#" id="showgrn" class="btn btn-primary btn-block text-uppercase">Show GRN List</a>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12 docancel" style="display: none;">
                <a href="#" id="docancel" class="btn btn-primary btn-block text-uppercase">Cancel</a>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <table class="table table-hover grn_data" align="centre">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col" style="width: 20%;">Items Desc</th>
                      <th scope="col">HSN Code</th> 
                      <th scope="col">Unit</th>
                      <th scope="col">PO Qty</th>
                      <th scope="col">Accept Qty</th>
                      <th scope="col">Price</th>
                      <th scope="col">Amount</th>
                      <th scope="col">GST</th>
                      <th scope="col" width="10%">Amount</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $sl=0;
                    $total=0;
                    $totalgst=0;
                    $lokigetitems=mysqli_query($con, "
                      SELECT * FROM `stc_purchase_product_items`
                      LEFT JOIN `stc_product`
                      ON `stc_purchase_product_items_product_id`=`stc_product_id`
                      WHERE `stc_purchase_product_items_order_id`='".$_GET['purchaseorderid']."'
                    ");
                    $dataqty='';
                    foreach ($lokigetitems as $row) {
                      $lokigetqtyofpd=mysqli_query($con, "
                        SELECT SUM(`stc_product_grn_items_qty`) as acceptedv FROM `stc_product_grn_items` WHERE `stc_product_grn_items_purchase_order_id`='".$_GET['purchaseorderid']."' AND `stc_product_grn_items_product_id`='".$row['stc_purchase_product_items_product_id']."'
                      "); 
                      $dataqty=mysqli_fetch_assoc($lokigetqtyofpd);
                      $outting=round($dataqty['acceptedv'], 2);
                      $sl++;
                      $amount=$row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate'];
                      $gstamount = ($amount * $row["stc_product_gst"])/100;
                      if($row['stc_purchase_product_items_qty'] - $outting != 0 ){
                        echo "
                          <tr>
                            <td>".$sl."</td>
                            <td>".$row['stc_product_name']."</td>
                            <td>".$row['stc_product_hsncode']."</td>
                            <td>".$row['stc_product_unit']."</td>
                            <td>".number_format($row['stc_purchase_product_items_qty'], 2)."</td>
                            <td width='12%'>  
                              <input 
                                type='hidden' 
                                class='form-control stcpricegrnt".$row["stc_product_id"]."'
                                style='width:100%;'
                                value='".$row['stc_purchase_product_items_rate']."'
                              > 
                              <input 
                                type='hidden' 
                                class='form-control stcorderid".$row["stc_product_id"]."'
                                style='width:100%;'
                                value='".$_GET['purchaseorderid']."'
                              >                  
                              <input 
                                type='text' 
                                class='form-control stcqtygrnt".$row["stc_product_id"]."' 
                                placeholder='Quantity' 
                                value='".$outting."'
                                style='width:100%;'
                              >                            
                            </td>
                            <td><i class='fas fa-rupee-sign'></i> ".number_format($row['stc_purchase_product_items_rate'], 2)."</td>
                            <td><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
                            <td>".$row['stc_product_gst']."%</td>
                            <td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
                            </td>
                            <td align='right'>
                              <a class='btn btn-success addtogrn' id='".$row["stc_product_id"]."' href='#' style='font-size:40px;text-align:center;color:#fff;'>
                                <i class='fas fa-check-circle'></i>
                              </a>
                            </td>
                          </tr>
                        ";
                      }else{
                        echo "
                          <tr>
                            <td>".$sl."</td>
                            <td>".$row['stc_product_name']."</td>
                            <td align='center' colspan='9'>This Item is Accepted!!!</td>
                          </tr>
                        ";
                      }
                      $total = $total + ($row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate']);  
                      $totalgst +=$gstamount;
                      // $dataqty=0;
                    }
                    $tcs=(($total + $totalgst) * 0.075)/100;
                    echo '
                        <tr>
                          <td colspan="9"><h4 align="right">Total</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
                        </tr>
                        <tr>
                          <td colspan="9"><h4 align="right">CGST</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
                        </tr>
                        <tr>
                          <td colspan="9"><h4 align="right">SGST</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
                        </tr>
                        <tr>
                          <td colspan="9"><h4 align="right">Total Tax</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
                        </tr>
                        <tr>
                          <td colspan="9"><h4 align="right">TCS@ 0.075</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($tcs, 2).'</td>
                        </tr>
                        <tr>
                          <td colspan="9"><h4 align="right">Grand Total</h4></td>
                          <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total + $totalgst + $tcs, 2).'</td>
                        </tr>
                    ';
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="Notes"
                    >Notes
                  </label>
                  <textarea 
                    row="2"
                    placeholder="Notes"
                    class="form-control notesgrn"
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <!-- <button type="submit" class="btn btn-primary btn-block text-uppercase">For Quotation</button> -->
                <button type="submit" id="<?php echo $lokigetorder['stc_purchase_product_id'];?>" class="btn btn-primary btn-block text-uppercase stcsavegrn">Save</button>
              </div>
            </div>
            <?php
            }else{
            ?>
            <div class="row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <!-- Create order -->
              <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="tm-block-title d-inline-block">GRN</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
                    </div>
                  </div>
                  <div class="row stc-grn-row">
                    <div class="col-xl-6 col-md-6 col-sm-6">
                      <form action="" class="stc-add-grn-form"> 
                      <div class="form-group mb-3">
                        <label
                          for="name"
                          >GRN Number
                        </label>
                        <input
                          id="stcgrnnumbershow"
                          type="text"
                          placeholder="GRN Number"
                          class="form-control validate"
                          style="background-color: #186abb;"
                          Value=""
                          disabled
                        />
                        </div>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-6">
                        <div class="form-group mb-3">
                          <label
                            for="grndate"
                            >GRN Date
                          </label>
                          <input
                            id="grn_date_show"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background-color: #186abb;"
                            disabled
                          />
                        </div>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-6">
                        <div class="form-group mb-3">
                          <label
                            for="name"
                            >Purchase Order Number
                          </label>
                          <input
                            id="show_grn_po_no"
                            type="text"
                            placeholder="Purchase order Number"
                            class="form-control validate"
                            style="background-color: #186abb;"
                            Value="<?php echo 'STC/'.$ponumber; ?>"
                            disabled
                          />
                        </div>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-6">
                        <div class="form-group mb-3">
                          <label
                            for="orderdate"
                            >Purchase Order Date
                          </label>
                          <input
                            id="show_grn_po_date"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background-color: #186abb;"
                            value="<?php echo $lokigetorder['stc_purchase_product_order_date'];?>"
                            disabled
                          />
                        </div>
                      </div>              
                      <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                          <label
                            for="name"
                            >Merchant Name
                          </label>
                          <input
                            id="show_merchant_name"
                            type="text"
                            placeholder="Merchant Name"
                            class="form-control validate"
                            style="background-color: #186abb;"
                            disabled
                          />
                        </div>
                      </div> 
                      <div class="col-xl-6 col-md-6 col-sm-6">
                        <div class="form-group mb-3">
                          <label
                            for="invoicenumber"
                            >Invoice/Challan Number
                          </label>
                          <input
                            id="show_grn_invonumber"
                            type="text"
                            placeholder="Challan/Invoice Number"
                            class="form-control validate stcinvonumber"
                            data-large-mode="true"
                            style="background-color: #186abb;"
                            value=""
                            disabled
                          />
                        </div>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-6">
                        <div class="form-group mb-3">
                          <label
                            for="invoicedate"
                            >Invoice/Challan Date
                          </label>
                          <input
                            id="show_grn_invdate"
                            name="stcinvodate"
                            type="date"
                            placeholder="Challan Date"
                            class="form-control validate stcinvodate"
                            style="background-color: #186abb;"
                            value=""
                            disabled
                          />
                        </div>
                      </div>   
                      <div class="col-xl-6 col-md-6 col-sm-12">
                        <h2 class="tm-block-title d-inline-block">Products</h2> 
                      </div>

                      <!-- line item table -->
                      <div class="col-xl-12 col-md-12 col-sm-12 grn_show_items_div">
                        <table class="table table-hover" align="centre">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col" style="width: 20%;">Items</th>
                              <th scope="col">HSN Code</th> 
                              <th scope="col">Unit</th>
                              <th scope="col">Qty</th>                         
                              <th scope="col">Price</th>
                              <th scope="col">Amount</th>
                              <th scope="col">GST</th>
                              <th scope="col" style="width: auto;">Amount</th>
                            </tr>
                          </thead>
                          <tbody>                     
                            <tr>
                              <td>1</td>
                              <td>LEATHER SAFETY SHOES ACME STORM</td>
                              <td>6403</td>
                              <td>PAIR</td>
                              <td>14.00</td>
                              <td>850.00</td>
                              <td><i class="fas fa-rupee-sign"></i> 11,900.00</td>
                              <td> 5% </td>
                              <td align="right"><i class="fas fa-rupee-sign"></i> 12,495.00</td>
                            </tr>                                   
                            <tr>
                              <td colspan="8"><h4 align="right">Total</h4></td>
                              <td align="right"><i class="fas fa-rupee-sign"></i> 11,900.00</td>
                            </tr>
                            <tr>
                              <td colspan="2"><h6 align="right">Freight Charges : </h6></td>
                              <td align="right">
                                <i class="fas fa-rupee-sign"></i> 0
                              </td>
                              <td colspan="2"><h6 align="right">Packing &amp; Forwarding : </h6></td>
                              <td align="right">
                                <i class="fas fa-rupee-sign"></i> 0
                              </td>
                              <td colspan="2"><h6 align="right">Other Charges : </h6></td>
                              <td align="right">
                                <i class="fas fa-rupee-sign"></i> 0
                              </td>
                            </tr>
                            <tr>
                              <td colspan="8"><h4 align="right">CGST</h4></td>
                              <td align="right"><i class="fas fa-rupee-sign"></i> 297.50</td>
                            </tr>
                            <tr>
                              <td colspan="8"><h4 align="right">SGST</h4></td>
                              <td align="right"><i class="fas fa-rupee-sign"></i> 297.50</td>
                            </tr>
                            <tr>
                              <td colspan="8"><h4 align="right">Total Tax</h4></td>
                              <td align="right"><i class="fas fa-rupee-sign"></i> 595.00</td>
                            </tr>
                            <tr>
                              <td colspan="8"><h4 align="right">Grand Total</h4></td>
                              <td align="right">
                                <i class="fas fa-rupee-sign"></i> <span id="stc_final_grn_value">12,495.00</span>                 
                              </td>
                            </tr>                                  
                        </tbody>
                      </table>
                      </div>
                    </div>
                  </form>
                  </div>
              </div>

              <!-- view Purchase Orders -->
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col" width="50%">By PO Number/ Merchant/ Invoice Number</th>
                          <th scope="col">Search</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <?php 
                                $date = date("d-m-Y");
                                $newDate = date('Y-m-d', strtotime($date)); 
                                $effectiveDate = date('Y-m-d', strtotime("-3 months", strtotime($date)));
                            ?>   
                            <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                            <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                            <p><a href="#" id="grnproddatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i></a></p>
                          </td>
                          <td>
                            <input
                              id="tags"
                              data-role="tagsinput"
                              type="text"
                              placeholder="PO Number/ Merchant/ Invoice Number"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="search" class="btn btn-primary" id="grnfilter">Search</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
                  <div class="row stc-view-grn-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <form action="" class="stc-view-grn-form">
                          <table class="table table-hover ">
                            <tr>
                              <td>
                                Loading.....
                              </td>
                            </tr>
                          </table>
                      </form>
                    </div>
                  </div>

                  <div class="row stc-call-view-Merchant-row">
                    
                  </div>
                </div>
              </div> 
            </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
          $(function() {
            $( "#datepicker" ).datepicker();
          });
          $('.showgrn').on('click', function(){
            $(this).hide(100);
            $('.docancel').toggle(500);
          });
      });
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
        });

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
    <script>
      $(document).ready(function(){
       
        // load_data();
        function load_data(query, grnbegdate, grnenddate) {
          $.ajax({
            url:"asgard/do_grn.php",
            method:"POST",
            data:{
              stcfiltergrn:query,
              grnbegdate:grnbegdate,
              grnenddate:grnenddate
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
             $('.stc-view-grn-form').html(data);
            }
          });
        }

        $('#grnfilter').click(function(){
          var query = $('#tags').val();
          var grnbegdate=$('.begdate').val();
          var grnenddate=$('.enddate').val();
          if(query=='' || grnbegdate=='' || grnenddate==''){
            alert("date aur search field khaali mat de!!!");
          }else{
            load_data(query, grnbegdate, grnenddate);
          }
        });

      });
    </script>
  </body>
</html>
