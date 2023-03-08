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
    <title>Sale Product - STC</title>
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
  </head>

  <body>
   <?php include "header.php";?>
      

    <div class="container-fluid tm-mt-big tm-mb-big">

      <div class="modal fade" id="invo-modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" style="width: 1350px;left: -270px;">
            <div class="modal-header">
              <h4 class="modal-title">Edit Invoice Challan</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 invo-details-out">
                                    
                </div>                
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>        
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active">Sale Product</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase additional-sale-order-create-request" value=""> <b>+</b> Create Additional Sale Order</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase new-sale-order-create-request" value=""> <b>+</b> Create New Sale Order</a></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase additional-sale-order-show-request" value=""><b><i class="fa fa-eye"></i></b> Show Additional Sale Order</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase new-sale-order-show-request" value=""> <b><i class="fa fa-eye"></i></b> Show New Sale Order</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row animated slideInUp">
        <!-- Create sale order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto new-sale-order-create-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="additional-sale-order-create-response-sale-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Sale Order Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Sale Order Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Sale Order Date
                  </label>
                  <input
                    id="datepicke1r"
                    name="stcinvodate"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Challan Date"
                    class="form-control validate stcinvodate"
                    value=""
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
                    id="stc_customer_sale_product_invoice"
                    class="custom-select stc-select-customer"
                  >
                  </select>
                </div>
              </div> 
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Customer Site/Reference
                  </label>
                  <select
                    id="stc_customer_sale_site_refr"
                    class="custom-select stc_customer_sale_site_refr"
                  ><option value='0' selected>Please select customer first</option>
                  </select>
                </div>
              </div> 
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-invoice-sale-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 invoflase">a
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
                    id="stcinvonotes"
                    placeholder="Notes"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <a type="submit" class="btn btn-primary btn-block text-uppercase stcsaveinvo">Save</a>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view sale Orders -->
        <div style="display: block;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto new-sale-order-show-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Orders/ Tax Invoice</h2>
              </div>
            </div>
            <div class="row new-sale-order-create-response-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="new-sale-order-create-response-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col">Rows <br>Like 0 to 10/ 11 to 20</th>
                          <th scope="col" width="50%">By Challan Number, Customer Name, Customer Site, Status</th>
                          <th scope="col">Search</th>
                          <th scope="col">Total</th>
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
                            <p><a href="#" id="saleproddatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                            </a></p>
                          </td>
                          <td>
                            <input type="number" placeholder="Begining Value" value="0" class="form-control begdigit"><br>
                            <input type="number" placeholder="Ending Value" value="10" class="form-control enddigit">
                          </td>
                          <td>
                            <input
                              id="stcfilterbypdnamedc"
                              type="text"
                              placeholder="Product Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="challansearch" class="btn btn-primary" id="challansearch">Search</button>
                          </td>
                          <td>
                            <h5>
                              <span class="grand_total"></span>
                            </h5>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row new-sale-order-create-response-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="new-sale-order-create-response-Sale-order-invoice-form">
                    <table class="table table-hover ">
                      <tr><td>Loading....</td></tr>
                    </table>
                </form>
              </div>
            </div>
          </div>
        </div> 

        <!-- additional sale order create-->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto additional-sale-order-create-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="additional-sale-order-create-response-sale-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Sale Order Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Sale Order Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Sale Order Date
                  </label>
                  <input
                    id="datepicke1r"
                    name="stcaddinvodate"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Challan Date"
                    class="form-control validate stcaddinvodate"
                    value=""
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
                    id="stc_customer_addiional_sale_order_invo"
                    class="custom-select stc-select-customer stc_customer_sale_product_add_invoice"
                  >
                  </select>
                </div>
              </div> 
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-add-invoice-sale-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 additionalinvoflase">
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
                    id="stcaddinvonotes"
                    placeholder="Notes"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <a type="submit" class="btn btn-primary btn-block text-uppercase stcsaveaddinvo">Save</a>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- additional sale order view-->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto additional-sale-order-show-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Additional Sale Orders/Additional Tax Invoice</h2>
              </div>
            </div>
            <div class="row new-sale-order-create-response-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="new-sale-order-create-response-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col">Rows <br>Like 0 to 10/ 11 to 20</th>
                          <th scope="col" width="50%">By Challan Number, Customer Name, Customer Site, Status</th>
                          <th scope="col">Search</th>
                          <th scope="col">Total</th>
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
                            <p><a href="#" id="saleproddatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                            </a></p>
                          </td>
                          <td>
                            <input type="number" placeholder="Begining Value" value="0" class="form-control begdigit"><br>
                            <input type="number" placeholder="Ending Value" value="10" class="form-control enddigit">
                          </td>
                          <td>
                            <input
                              id="stcfilterbypdnamedc"
                              type="text"
                              placeholder="Product Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="challansearch" class="btn btn-primary" id="challansearch">Search</button>
                          </td>
                          <td>
                            <h5>
                              <span class="grand_totaladd_invo"></span>
                            </h5>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row new-sale-order-create-response-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-additional-sale-order-response-call-form">
                    <table class="table table-hover ">
                      <tr><td>Loading....</td></tr>
                    </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.additional-sale-order-create-request').on('click', function(e){
          e.preventDefault();
          $('.additional-sale-order-create-response').toggle(1000);
          $('.new-sale-order-create-response').fadeOut(500);
          $('.additional-sale-order-show-response').fadeOut(500);
          $('.new-sale-order-show-response').fadeOut(500);

        });

        $('.new-sale-order-create-request').on('click', function(e){
          e.preventDefault();
          $('.new-sale-order-create-response').toggle(1000);
           $('.additional-sale-order-create-response').fadeOut(500);
           $('.new-sale-order-show-response').fadeOut(500);
           $('.additional-sale-order-show-response').fadeOut(500);
        });
        
        $('.additional-sale-order-show-request').on('click', function(e){
          e.preventDefault();
          $('.additional-sale-order-show-response').toggle(1000);
           $('.additional-sale-order-create-response').fadeOut(500);
           $('.new-sale-order-create-response').fadeOut(500);
           $('.new-sale-order-show-response').fadeOut(500);
        });

        $('.new-sale-order-show-request').on('click', function(e){
          e.preventDefault();
          $('.new-sale-order-show-response').toggle(1000);
           $('.additional-sale-order-create-response').fadeOut(500);
           $('.new-sale-order-create-response').fadeOut(500);
           $('.additional-sale-order-show-response').fadeOut(500);
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        load_this_invoices(begdateon, enddateon);
        function load_this_invoices(begdateon, enddateon){
          var loadchallanbegdate=begdateon;
          var loadchallanenddate=enddateon;
          $.ajax({
            url      : 'asgard/set_invoices.php',
            method   : 'post',
            data     : {
              load_invoices:1,
              loadchallanbegdate:loadchallanbegdate,
              loadchallanenddate:loadchallanenddate
            },
            dataType : 'JSON',
            success  : function(invoices){
              // console.log(invoices);
              $('.new-sale-order-create-response-Sale-order-invoice-form').html(invoices['bills_all']);
              $('.grand_total').html(invoices['value_total']);
            }
          });
        }

        $(document).on('click', '#saleproddatefilt', function(e){
          e.preventDefault();
          var begdate=$('.begdate').val();
          var enddate=$('.enddate').val();
          load_this_invoices(begdate, enddate);
        });
      });

      $(document).ready(function(){
       
        // load_data();
        function load_data(query, directchallanbegdate, directchallanenddate, begno, endno) {
          $.ajax({
            url:"asgard/set_invoices.php",
            method:"POST",
            data:{
              stcfilterchallan:query,
              directchallanbegdate:directchallanbegdate,
              directchallanenddate:directchallanenddate,
              begno:begno,
              endno:endno
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.new-sale-order-create-response-Sale-order-invoice-form').html(data['bills_all']);
              $('.grand_total').html(data['value_total']);
            }
          });
        }

        $('#challansearch').click(function(){
          var query = $('#stcfilterbypdnamedc').val();
          var directchallanbegdate=$('.begdate').val();
          var directchallanenddate=$('.enddate').val();
          var begno=$('.begdigit').val();
          var endno=$('.enddigit').val();
          if(query=='' || directchallanbegdate=='' || directchallanenddate==''){
            alert("Date select kar!!!");
          }else{
            load_data(query, directchallanbegdate, directchallanenddate, begno, endno);
          }
        });

        $('body').delegate('.invo-change-btn-hit', 'click', function(e){
          e.preventDefault();
          var invo_challan_id=$(this).attr("id");
          var invo_change_id=$('.invo-change-btn-vaue'+invo_challan_id).val();
          var invo_id=$('#chall-id'+invo_challan_id).val();
          $.ajax({
            url:"asgard/set_invoices.php",
            method:"POST",
            data : {
              invo_reset_hit:1,
              invo_id:invo_id,
              invo_change_id:invo_change_id,
              invo_challan_id:invo_challan_id
            },
            success : function(response){
              console.log(response);
              alert(response);
            }
          });
        });

        $('body').delegate('.stc_edit_invo', 'click', function(e){
          e.preventDefault();
          var invoid=$(this).attr("id");
          $.ajax({
            url      : 'asgard/set_invoices.php',
            method   : 'post',
            data     : {
              loadpertinvo:1,
              invoid:invoid
            },
            // dataType : 'JSON',
            success  : function(invoices){
              console.log(invoices);
              $('.invo-details-out').html(invoices);
            }
          })
          $("#invo-modal").modal("show");
        });
      });
    </script>
  </body>
</html>