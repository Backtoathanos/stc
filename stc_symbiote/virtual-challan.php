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
    <title>Virtual Challan - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
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

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />

  <style>
    .bootstrap-tagsinput {
     width: 100%;
     color: blue;
    }
    .bootstrap-tagsinput .tag {
       color: black;
     }

    .search-box,.close-icon,.search-wrapper {
      position: relative;
      padding: 10px;
    }
    .search-wrapper {
      width: 500px;
      margin: auto;
      margin-top: 50px;
    }
    .search-box {
      width: 98%;
      border: 1px solid #ccc;
      outline: 0;
      border-radius: 15px;
    }
    .search-box:focus {
      box-shadow: 0 0 15px 5px #b0e0ee;
      border: 2px solid #bebede;
    }
    .close-icon {
      border:1px solid transparent;
      background-color: transparent;
      display: inline-block;
      vertical-align: middle;
      outline: 0;
      cursor: pointer;
    }
    .close-icon:after {
      content: "X";
      display: block;
      width: 15px;
      height: 15px;
      position: absolute;
      background-color: #FA9595;
      z-index:1;
      right: 35px;
      top: 0;
      bottom: 0;
      margin: auto;
      padding: 0px;
      border-radius: 50%;
      text-align: center;
      color: white;
      font-weight: normal;
      font-size: 12px;
      box-shadow: 0 0 2px #E50F0F;
      cursor: pointer;
    }
    .search-box:not(:valid) ~ .close-icon {
      display: none;
    }
  </style>
</head>

  <body>
   <?php include "header.php";?>
    <!-- modal section start-->

    <!-- Modal -->
    <div id="vcmodal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Update virtual challan</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <label>Current Challan GRN</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="text" class="btn btn-primary ccg-number" disabled>           
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <label>Change Challan GRN</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <select class="custom-select stc-vc-update-grn">
                  <?php
                    include_once("../MCU/db.php");                        
                    $qry=mysqli_query($con, "SELECT `stc_product_grn_id` FROM `stc_product_grn` ORDER BY `stc_product_grn_id` DESC");
                    foreach($qry as $row){
                        echo '
                          <option value="'.$row['stc_product_grn_id'].'">GRN/'.substr("0000{$row['stc_product_grn_id']}", -5).'</option>
                        ';
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="hidden" class="stc-vc-update">
                <button type="button" class="btn btn-success update-challan-hit">Update</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal section end -->
    <div class="container-fluid tm-mt-big tm-mb-big ">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Virtual Challan</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto animate__fadeInTopLeft">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Create New Virtual Challan</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row animated zoomInUp">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="container mt-5">
              <div class="row tm-content-row">
                <div class="col-sm-12 col-xl-3 tm-block-col">
                  <div class="form-group mb-3">
                    <label
                      for="name" 
                      class="text-uppercase"
                      >Choose GRN Number
                    </label>
                  </div>
                </div>
                <div class="col-sm-12 col-xl-6">
                  <div class="form-group mb-3">
                    <select class="custom-select stc-vc-grn">
                      <?php
                        include_once("../MCU/db.php");                        
                        $qry=mysqli_query($con, "SELECT `stc_product_grn_id` FROM `stc_product_grn` ORDER BY `stc_product_grn_id` DESC");
                        foreach($qry as $row){
                          $checkqry=mysqli_query($con, "
                              SELECT `stc_sale_product_id` 
                              FROM `stc_sale_product` 
                              WHERE `stc_sale_product_refr_grn_no`='".$row['stc_product_grn_id']."'
                          ");
                          if(empty(mysqli_num_rows($checkqry))){
                            echo '
                              <option value="'.$row['stc_product_grn_id'].'">GRN/'.substr("0000{$row['stc_product_grn_id']}", -5).'</option>
                            ';
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-12 col-xl-3 tm-block-col">
                  <div class="form-group mb-3">
                      <a href="#" class="btn btn-secondary btn-block text-uppercase stc-vc-grn-hit">
                       Set <i class="fas fa-thumbs-up"></i>
                      </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row stc-sale-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-sale-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Challan Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Virtual Challan Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Challan Date
                  </label>
                  <input
                    id="expire_date"
                    name="expire_date"
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Customer Name
                  </label>
                  <select
                    id="virtualchallancustomer"
                    class="custom-select stc-select-customer"
                    name="stccustomer"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Number
                  </label>
                  <input
                    name="virtualchallancustordernumber"
                    type="text"
                    class="form-control validate virtualchallancustordernumber"
                    placeholder="Customer Order Number"
                    style="background-color: #186abb;"
                    value="VERBAL"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Date
                  </label>
                  <input
                    id="datepicke1r"
                    name="virtualchallancustorderdate"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Customer Order Date"
                    class="form-control validate virtualchallancustorderdate"
                    style="background-color: #186abb;"
                    value=""
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Way Bill Number
                  </label>
                  <input
                    name="virtualchallanwaybillno"
                    type="text"
                    class="form-control validate virtualchallanwaybillno"
                    placeholder="Way Bill No"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >LR Number
                  </label>
                  <input
                    name="virtualchallanlrno"
                    type="text"
                    class="form-control validate virtualchallanlrno"
                    placeholder="LR No"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Date of Supply
                  </label>
                  <input
                    name="virtualchallandatesupply"
                    type="text"
                    class="form-control validate virtualchallandatesupply"
                    placeholder="Date of Supply"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Place of Supply
                  </label>
                  <input
                    name="virtualchallanplacesupply"
                    type="text"
                    class="form-control validate virtualchallanplacesupply"
                    placeholder="Place of Supply"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Refrence
                  </label>
                  <input
                    name="virtualchallanordrefrence"
                    type="text"
                    class="form-control validate virtualchallanordrefrence"
                    placeholder="Customer Refrence"
                    style="background-color: #186abb;"
                    value="VERBAL"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Site Name
                  </label>
                  <input
                    name="virtualchallancustsitename"
                    type="text"
                    class="form-control validate virtualchallancustsitename"
                    placeholder="Site Name"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Person
                  </label>
                  <input
                    name="virtualchallancustcontperson"
                    type="text"
                    class="form-control validate virtualchallancustcontperson"
                    placeholder="Contact Person"
                    style="background-color: #186abb;"
                    value="RAZIULLAH"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Number
                  </label>
                  <input
                    name="virtualchallancustcontnumber"
                    type="text"
                    class="form-control validate virtualchallancustcontnumber"
                    placeholder="Contact Number"
                    style="background-color: #186abb;"
                    value="9204364798"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Address
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcvcshipaddress"
                    placeholder="Shipping Address"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Condition
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcvctandc"
                    placeholder="Terms & Condition"
                    required
                  ></textarea>
                </div>
              </div>
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12 thishypo">
                <div class="row stc-call-view-sale-product-row">
                </div>
              </div>
              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 stc-vc-line-items-table">
              </div>
              <div class="col-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Notes
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcvcnotes"
                    placeholder="Notes"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavevirtualchallan">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view Challan -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Challan</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col" width="70%" colspan="2">By Challan Number, Customer Name, Customer Site, Status</th>
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
                            <p><a href="#" id="challandatefilt">
                                <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                              </a>
                            </p>
                          </td>
                          <td>
                            <input
                              id="tags"
                              data-role="tagsinput"
                              type="text"
                              placeholder="Challan Number/ Customer/Status"
                              class="form-control validate stcfilterbyponumber"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="challansearch" class="btn btn-primary" id="challansearch">Search</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-virtual-challan-form">
                    <table class="table table-hover ">
                      <tr><td>Loading....</td></tr>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-Merchant-row">
              
            </div>
          </div>
        </div> 
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('.close-icon').on('click', function(e){
          e.preventDefault();
            $('.thishypo').fadeOut(500);
        });
        
        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
        });

        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.thishypo').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.thishypo').toggle(500);
            $('.downward').fadeOut(500);
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
        var todaydate = new Date();
        var day = todaydate.getDate();
        var month = todaydate.getMonth() - 3;
        var nmonth = todaydate.getMonth();
        var year = todaydate.getFullYear();
        var begdateon = year + "/" + month + "/" + day;
        var enddateon = year + "/" + nmonth + "/" + day;

        function load_data(query, challanbegdate, challanenddate) {
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method  :"POST",
            data    :{
              stcfilterchallan:query,
              challanbegdate:challanbegdate,
              challanenddate:challanenddate
            },
            dataType:"json",
            success :function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-view-virtual-challan-form').html(data);
            }
          });
        }

        $('#challansearch').click(function(){
          var query = $('#tags').val();
          var challanbegdate=$('.begdate').val();
          var challanenddate=$('.enddate').val();
          load_data(query, challanbegdate, challanenddate);
        });

        $('body').delegate('.stc-vc-grn-hit', 'click', function(e){
          e.preventDefault();
          var grn_no=$('.stc-vc-grn').val();
          // alert(grn_no);
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method  : "post",
            data    : {
              act_on_vc_hit:1,
              grn_no:grn_no
            },
            success : function(responses){
              // console.log(responses);
              alert(responses);
              get_records();
            }
          });
        });

        get_records();
        function get_records(){
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method  : "post",
            data    : {
              get_items_cart_from_grn:1
            },
            success : function(responses){
              // console.log(responses);
              $('.stc-vc-line-items-table').html(responses);
            }
          });
        }

        $('body').delegate('.stcdelvcbtn', 'click', function(e){
          e.preventDefault();
          var del_vc_id=$(this).attr("id");
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method  : "post",
            data    : {
              del_grn_id_vc:1,
              del_vc_id:del_vc_id
            },
            success : function(responses){
              // console.log(responses);
              alert(responses);
              get_records();
            }
          });
        });

        $(document).on('click', '.stcsavevirtualchallan', function(e){
          e.preventDefault();
          var customer_id         = $('#virtualchallancustomer').val();
          var order_invodate      = $('.virtualchallancustorderdate').val();
          var order_invonumber    = $('.virtualchallancustordernumber').val();
          var order_waybillno     = $('.virtualchallanwaybillno').val();
          var order_lrno          = $('.virtualchallanlrno').val();
          var order_supplydate    = $('.virtualchallandatesupply').val();
          var order_supplyplace   = $('.virtualchallanplacesupply').val();
          var order_refrence      = $('.virtualchallanordrefrence').val();
          var order_sitename      = $('.virtualchallancustsitename').val();
          var order_contperson    = $('.virtualchallancustcontperson').val();
          var order_contnumber    = $('.virtualchallancustcontnumber').val();
          var order_shipaddress   = $('#stcvcshipaddress').val();
          var order_stcfc         = $('.stcfc').val();
          var order_stcpf         = $('.stcpf').val();
          var order_notes         = $('#stcvcnotes').val();
          var order_tandc         = $('#stcvctandc').val();   
          var order_grnno         = $('.stc-vc-grn').val();    
          $.ajax({  
            url:"asgard/run_virtual_challan.php",
            method:"POST",  
            data:{
              customer_id:customer_id,
              sale_custorderdate:order_invodate,
              sale_custordernumber:order_invonumber,
              sale_waybillno:order_waybillno,
              sale_lrno:order_lrno,
              sale_supplydate:order_supplydate,
              sale_supplyplace:order_supplyplace,
              sale_refrence:order_refrence,
              sale_sitename:order_sitename,
              sale_contperson:order_contperson,
              sale_contnumber:order_contnumber,
              sale_shipaddress:order_shipaddress,
              sale_stcfc:order_stcfc,
              sale_stcpf:order_stcpf,
              order_notes:order_notes,
              order_tandc:order_tandc,
              order_grnno:order_grnno,
              save_vc_action:1
            },
            // dataType: `JSON`,
            success:function(data){
             console.log(data);
              alert(data);
              // $('.stc-add-sale-product-form')[0].reset();
              window.location.reload();
              // $('.stc-add').fadeOut(500);
              // $('.stc-view').toggle(1000);
            }
          });
        });

        stc_call_virtual_challan(begdateon, enddateon);
        function stc_call_virtual_challan(begdateon, enddateon){
          var begdate=begdateon;
          var enddate=enddateon; 
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method:'POST',
            data:{
              load_virtual:1,
              begdateon:begdateon,
              enddateon:enddateon
            },
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
              $('.stc-view-virtual-challan-form').html(data);
            }
          });     
        }

        $(document).on('click', '#challandatefilt', function(e){
          e.preventDefault();
          var begdate=$('.begdate').val();
          var enddate=$('.enddate').val();
          stc_call_virtual_challan(begdate, enddate);
        });

        var vc_id;
        var vc_grn;
        var vc_new_grn;
        $('body').on('click', '.vcmodal', function(e){
          e.preventDefault();
          vc_id=$(this).attr("id");
          vc_grn=$(this).attr("grn");
          vc_new_grn=$(".stc-vc-update-grn").val();
          $('.ccg-number').val(vc_grn);
          $('#vcmodal').modal('show');
        });

        $('body').on('click', '.update-challan-hit', function(e){
          e.preventDefault();
          $.ajax({
            url     : "asgard/run_virtual_challan.php",
            method  : "POST",
            data    : {
              update_vc:1,
              vc_id:vc_id,
              vc_grn:vc_grn,
              vc_new_grn:vc_new_grn
            },
            success : function(update){
              // console.log(update);
              alert(update);
            }
          });
        });
      });
    </script>
  </body>
</html>