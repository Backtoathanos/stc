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
    <title>Silent Invoice - STC</title>
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
                    <li class="breadcrumb-item active"><a href="#" class="active">Silent Invoice</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto animated zoomInRight">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> With GST</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Create New Silent Invoice</a></label>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <div class="row animated slideInUp">
        <!-- Create silent invoice -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-sale-product-form"> 
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
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-invoice-silent-invoice-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 show_silent_challan_for_invo">Loading...
              </div>
              <div class="col-4">
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
                <a type="submit" class="btn btn-primary btn-block text-uppercase stcsavesilentinvo">Save</a>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view silent invoice -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Silent Invoice/ Retail Invoice</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col">Rows <br>Like 0 to 10/ 11 to 20</th>
                          <th scope="col" width="40%">By Challan Number, Customer Name, Customer Site, Status</th>
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
                              <span class="silent_grand_total"></span>
                            </h5>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-silent-invoice-form">
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
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
          load_challan_do_invo();
          show_silent_invo_cart();
        });

        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
            load_challan_do_invo();
            show_silent_invo_cart();
        });

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });

        load_this_invoices(begdateon, enddateon);
        function load_this_invoices(begdateon, enddateon){
          var loadchallanbegdate=begdateon;
          var loadchallanenddate=enddateon;
          $.ajax({
            url      : 'asgard/givesilentchallan.php',
            method   : 'post',
            data     : {
              load_invoices:1,
              loadchallanbegdate:loadchallanbegdate,
              loadchallanenddate:loadchallanenddate
            },
            dataType : 'JSON',
            success  : function(invoices){
              // console.log(invoices);
              $('.stc-view-silent-invoice-form').html(invoices['bills_all']);
              $('.silent_grand_total').html(invoices['value_total']);
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
            url      : 'asgard/givesilentchallan.php',
            method:"POST",
            data:{
              stcfilterinvoice:query,
              directchallanbegdate:directchallanbegdate,
              directchallanenddate:directchallanenddate,
              begno:begno,
              endno:endno
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-view-silent-invoice-form').html(data['bills_all']);
              $('.silent_grand_total').html(data['value_total']);
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


        load_challan_do_invo();
        // load challan on chnage cust
        function load_challan_do_invo(){
          // alert(js_cust_id);
          $.ajax({
            url : "asgard/givesilentchallan.php",
            method : "post",
            data : {
              call_po_on_choose_customer:1
            },
            // dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('.stc-call-view-invoice-silent-invoice-row').html(data);
            }
          });
        }

        // load invoices session
        show_silent_invo_cart();
        function show_silent_invo_cart() {
          $.ajax({  
            url : "asgard/givesilentchallan.php", 
            method:"POST",
            data:{  
                comon_invo_sess:1  
            },  
            // dataType: 'JSON',
            success:function(data){
              // console.log(data);    
              // $('.show_silent_challan_for_invo').html(data['invoTable']); 
              $('.show_silent_challan_for_invo').html(data);                    
            }  
          });
        }

        // add to invoices session
        $(document).on('click', '.add_to_silent_invo_cart', function(e){  
          e.preventDefault();
          var challan_id = $(this).attr("id");
          $.ajax({  
            url : "asgard/givesilentchallan.php",
            method:"POST",  
            data:{
              invo_challan_id:challan_id,
              add_invo_sess_action:1
            },  
            // dataType: `JSON`,
            success:function(data){ 
             // console.log(data);
             alert(data);
              show_silent_invo_cart();
            }  
          });  
        }); 

        // delete from invoices session
        $('body').delegate('.stcdelsilentinvobtn','click',function(e){
          e.preventDefault();
          var product_id = $(this).attr("id");
            if(confirm("Are you sure you want to remove this product?")){   
              $.ajax({  
                url : "asgard/givesilentchallan.php",
                method:"POST",
                data:{  
                    product_id:product_id,
                    stcdelinvolinei:1  
                },  
                success:function(data){  
                  show_silent_invo_cart();
                  alert(data);                        
                }  
              });  
            }         
        });

        // save invoice
        $(document).on('click', '.stcsavesilentinvo', function(e){
          e.preventDefault();
          var stcinvodate=$('.stcinvodate').val();    
          var stcinvonotes=$('#stcinvonotes').val();
            $.ajax({  
                url : "asgard/givesilentchallan.php",
                method:"POST",  
                data:{
                stcinvodate:stcinvodate,
                stcinvonotes:stcinvonotes,
                save_invo_action:1
              },  
              // dataType: `JSON`,
              success:function(data){ 
                // console.log(data);
                // window.location.reload(500);
                  alert(data);
                  // load_invoices();
                  show_silent_invo_cart();
                  $('.stc-add').fadeOut(1000);
                  $('.stc-view').toggle(500);
                  $('.stc-add-sale-product-form')[0].reset();
              }  
            });
        });
      });
    </script>
  </body>
</html>