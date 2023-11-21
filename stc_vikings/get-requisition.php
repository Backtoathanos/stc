<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_empl_id'])){
    header('location:index.html');
}
include_once('../MCU/db.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Requisition Process - STC</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="../stc_symbiote/css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="../stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="../stc_symbiote/css/awsomeminho.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <style>
      .fade:not(.show) {
        opacity: 10;
      }

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
    <?php
      if(!empty($_SESSION['stc_get_req_use_sitename'])){
        foreach($_SESSION['stc_get_req_use_sitename'] as $row2){
          foreach($row2 as $row){
    ?>
    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row med">
        <div class="container-fluid">
          <div class="row">
            <!-- table -->
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table class="table table-bordered table-responsive" style="color: black;">
                <?php 
                  $stcrqsitequery=mysqli_query($con, "
                    SELECT DISTINCT
                      `stc_requisition_combiner_req_comb_id`,
                      `stc_cust_super_requisition_list_id`,
                      `stc_cust_super_requisition_list_date`,
                      `stc_cust_project_title`,
                      `stc_cust_project_id`
                    FROM `stc_requisition_combiner_req` 
                    INNER JOIN `stc_cust_super_requisition_list` 
                    ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list_id` 
                    INNER JOIN `stc_cust_project` 
                    ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
                    WHERE `stc_requisition_combiner_req_comb_id`='".$row."'
                  ");
                  if(mysqli_num_rows($stcrqsitequery)>0){
                    foreach($stcrqsitequery as $reqprrow){
                ?>
                  <tr>
                    <td align="center">
                      <?php echo $reqprrow['stc_cust_super_requisition_list_id'];?>
                    </td>
                    <td align="center">
                      <?php echo date('d-m-Y', strtotime($reqprrow['stc_cust_super_requisition_list_date']));?>
                    </td>
                    <td colspan="9" align="center">
                      <?php echo $reqprrow['stc_cust_project_title'];?>
                    </td>
                  </tr>
                  <tr>
                      <th class="text-center">#</th>
                      <th class="text-center" width="25%">ITEMS DESCRIPTIONS</th>
                      <th class="text-center">TYPE</th>
                      <th class="text-center">UNIT</th>
                      <th class="text-center">REQUISITION QTY</th>
                      <th class="text-center">PREVIOUS QTY</th>
                      <th class="text-center">APPROVE QTY</th>
                      <th class="text-center">APPROVED QTY</th>
                      <th class="text-center">RATE</th>
                      <th class="text-center">AMOUNT</th>
                      <th class="text-center">ACTION</th>
                  </tr>
                  <?php
                      $sl=0;
                      $total=0;
                      $totalgst=0;
                      $mtype='';
                      $currentrequisition=mysqli_query($con, "
                        SELECT 
                          `stc_cust_super_requisition_list_id`,
                          `stc_cust_super_requisition_list_items_title`,
                          `stc_product_id`,
                          `stc_product_name`,
                          `stc_product_unit`,
                          `stc_product_gst`,
                          `stc_cust_super_requisition_list_items_unit`,
                          `stc_cust_super_requisition_list_items_approved_qty`,
                          `stc_cust_super_requisition_items_type`,
                          `stc_cust_super_requisition_list_purchaser_pd_id`,
                          `stc_cust_super_requisition_list_purchaser_qty`
                        FROM 
                          `stc_cust_super_requisition_list_items`
                        INNER JOIN 
                          `stc_cust_super_requisition_list_purchaser` 
                        ON 
                          `stc_cust_super_requisition_list_purchaser_list_item_id`=`stc_cust_super_requisition_list_id`
                        INNER JOIN 
                          `stc_product` 
                        ON 
                          `stc_product_id`=`stc_cust_super_requisition_list_purchaser_pd_id`
                        WHERE 
                          `stc_cust_super_requisition_list_items_req_id`='".$reqprrow['stc_cust_super_requisition_list_id']."'
                      ");
                      foreach($currentrequisition as $row){
                          $rate=0;
                          $preqty=0;

                          $getitemqtyqry=mysqli_query($con, "
                            SELECT
                                SUM(`stc_sale_product_items_product_qty`) as previous_row
                            FROM
                                `stc_sale_product_items`
                            INNER JOIN 
                                `stc_sale_product` ON `stc_sale_product_id` = `stc_sale_product_items_sale_product_id`
                            INNER JOIN 
                                `stc_cust_super_requisition_list` ON `stc_cust_super_requisition_list_id` = `stc_sale_product_order_id`
                            WHERE
                                `stc_sale_product_items_product_id` = '".$row['stc_product_id']."'
                            AND 
                                `stc_cust_super_requisition_list_project_id` = '".$reqprrow['stc_cust_project_id']."'
                          ");
                          foreach($getitemqtyqry as $getitemqtyrow){
                            $preqty+=$getitemqtyrow['previous_row'];
                          }

                          if(strtoupper($row['stc_cust_super_requisition_list_items_unit'])==$row['stc_product_unit']){
                            $getitemrateqry=mysqli_query($con, "
                              SELECT 
                                `stc_purchase_product_items_rate` as rate
                              FROM `stc_purchase_product_items` 
                              WHERE `stc_purchase_product_items_product_id`='".$row['stc_cust_super_requisition_list_purchaser_pd_id']."'
                              ORDER BY `stc_purchase_product_items_id` DESC LIMIT 0,1
                            ");
                            foreach($getitemrateqry as $itrow){
                              $rate=$itrow['rate'];
                            }
                          }
                          $amount=$row['stc_cust_super_requisition_list_items_approved_qty'] * $rate;
                          $gst=$amount * $row['stc_product_gst']/100;
                          $sl++;
                          ?>
                          <tr>
                            <td class="no">
                                <?php echo $sl;?>
                            </td>
                            <td class="text-left">
                              <h6>
                                >  
                                <a href="#" id="<?php echo $row['stc_cust_super_requisition_list_id'];?>" class="stc-call-merchant-post" style="color:black;font-size: 15px;font-weight: bold;"><?php echo nl2br($row['stc_cust_super_requisition_list_items_title']);?> <span><i class="fa fa-edit"></i></span></a><br>
                                > 
                                <a href="#" id="<?php echo $row['stc_product_id'];?>" class="stc-call-merchant-pre" style="color:black;font-size: 15px;font-weight: bold;"><?php echo nl2br($row['stc_product_name']);?> <span><i class="fa fa-edit"></i></span></a>
                              </h6>
                            </td>
                            <td class="text-center">
                              <?php echo $row['stc_cust_super_requisition_items_type'];?>
                            </td>
                            <td class="unit">
                              <?php echo $row['stc_cust_super_requisition_list_items_unit'];?>
                            </td>
                            <td class="qty">
                                <?php echo number_format($row['stc_cust_super_requisition_list_items_approved_qty'], 2);?>
                            </td>
                            <td class="qty">
                                <?php echo number_format($preqty, 2).'/'.$row['stc_product_unit'];?>
                            </td>
                            <td class="qty">
                              <input type="hidden" value="<?php echo $row['stc_cust_super_requisition_list_items_approved_qty'];?>" id="stc-hreq-qty-val-<?php echo $row['stc_cust_super_requisition_list_id'];?>">
                              <input type="number" class="form-control" placeholder="Enter Quantity" value="<?php echo $row['stc_cust_super_requisition_list_items_approved_qty'];?>" id="stc-req-qty-val-<?php echo $row['stc_cust_super_requisition_list_id'];?>">
                            </td>
                            <td class="qty">
                                <?php echo number_format($row['stc_cust_super_requisition_list_purchaser_qty'], 2);?>
                            </td>
                            <td class="qty">
                                <?php echo number_format($rate, 2);?>
                            </td>
                            <td class="qty">
                                <?php echo number_format($amount, 2);?>
                            </td>
                            <td class="qty text-center">
                              <?php 
                                if($row['stc_cust_super_requisition_list_purchaser_qty']==0){
                              ?>
                                  <a href="#" class="form-control btn btn-primary stc-requist-qty-save" stc-brigpd-id="<?php echo $row['stc_product_id'];?>" id="<?php echo $row['stc_cust_super_requisition_list_id'];?>">Save</a>
                              <?php 
                                }else{
                              ?>
                                  # 
                              <?php
                                }
                              ?>
                            </td>
                          </tr>
                          <?php
                          $total+=$amount + $gst;
                      }
                  ?>
                  <tr>
                    <td class="text-right" colspan="9"><b>Total :</b></td>
                    <td class="text-right"><b><?php echo number_format($total, 2); ?></b></td>
                    <td class="text-left">GST Included</td>
                  </tr>
                <?php 
                    }
                  }else{
                ?>
                  <tr>
                    <td colspan="5" align="center">
                      No Requisition Found
                    </td>
                  </tr>
                <?php
                  }
                ?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
          }
        }
      }
    ?>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "../stc_symbiote/footer.php";?>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function(){
        // call merchant
        function stc_call_merchant(){
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "post",
            data      : {
              stc_call_merchant:1
            },
            success   : function(response_merchant){
              // console.log(response_merchant);
              $('.stc-call-merchant').html(response_merchant);
            }
          });
        }

        // call modal post
        $('body').delegate('.stc-call-merchant-post', 'click', function(e){
          e.preventDefault();
          var req_id=$(this).attr("id");
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "POST",
            data    : {
              stc_call_post_merchant:1,
              req_id:req_id
            },
            success : function(response_view){
              $('.stc-show-post-merhcant').html(response_view);
              $('.stc-res-merchant-post').modal("show");
              $('.stc-req-id').val(req_id);
            }
          });
        });

        // update merchant on hit
        $('body').delegate('.stc-call-merchant-pre', 'click', function(e){
          e.preventDefault();
          var prod_id=$(this).attr("id");
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "POST",
            data    : {
              stc_call_pre_merchant:1,
              prod_id:prod_id
            },
            success : function(response_view){
              // console.log(response_view);
              $('.stc-call-merchant-selected').html(response_view);
              $('.stc-res-merchant-pre').modal("show");
              $('.stc-product-id').val(prod_id);
            }
          });
        });

        // call rate
        $('body').delegate('.stc-call-merchant-selected', 'change', function(e){
          e.preventDefault();
          var merchant_id=$(this).val();
          var prod_id=$('.stc-product-id').val();
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "POST",
            data    : {
              stc_call_pre_merchant_rate:1,
              merchant_id:merchant_id,
              prod_id:prod_id
            },
            success : function(response_view){
              // console.log(response_view);
              $('.stc-call-merchant-rate').html(response_view);
            }
          });
        });

        // change merchant
        $('body').delegate('.stc-requisition-merchant-change-hit', 'click', function(e){
          e.preventDefault();
          var req_id=$('.stc-req-id').val();
          var merchant_id=$('.stc-call-merchant-selected').val();
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "POST",
            data    : {
              stc_req_change_merchant:1,
              req_id:req_id,
              merchant_id:merchant_id
            },
            success : function(response_view){
              // console.log(response_view);
              var response=response_view.trim();
              if(response=="Merchant updated succefully."){
                alert(response_view);
                $('.stc-res-merchant-pre').modal("hide");
              }else{
                alert(response_view);
              }
            }
          });
        });

        // save requisition qnty
        $('body').delegate('.stc-requist-qty-save', 'click', function(e){
          e.preventDefault();
          var req_id=$(this).attr("id");
          var pd_id=$(this).attr("stc-brigpd-id");
          var appr_qty=$('#stc-req-qty-val-'+req_id).val();
          var hqty=$('#stc-hreq-qty-val-'+req_id).val();
          var items_id=$('.stc-call-product-onprocess-req'+req_id)
          if(appr_qty>hqty){
            alert("Invalid Quantity.");
          }else{
            $(this).hide(500);
            $.ajax({
              url     : "kattegat/ragnar_order.php",
              method  : "POST",
              data    : {
                stc_reqqnty_appro:1,
                req_id:req_id,
                appr_qty:appr_qty,
                pd_id:pd_id
              },
              success : function(response_view){
                // console.log(response_view);
                alert(response_view);
              }
            });
          }
        });
      });
    </script>
  </body>
</html>

<div class="modal fade bd-example-modal-lg stc-res-merchant-post" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Item to Purchase From</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <label align="right">Merchant Name :</label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <span><b class="stc-show-post-merhcant"></b></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bd-example-modal-lg stc-res-merchant-pre" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Item to Purchase Before</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                Merchant Name :
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" class="stc-product-id">
                <select class="form-control btn btn-secondary stc-call-merchant-selected">                  
                </select>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                <span class="stc-call-merchant-rate"></span>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <input type="hidden" class="stc-req-id">
                <button type="submit" class="form-control btn btn-primary stc-requisition-merchant-change-hit">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>