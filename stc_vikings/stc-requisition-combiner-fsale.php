<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Requisitions - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
      .fade:not(.show) {
        opacity: 10;
      }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                <div class="app-main__inner"> 
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                <span>Requisitions</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                            <div class="row">
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 stc-req-view">
                                <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="overflow-x: auto; white-space: nowrap;">
                                  <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h2 class="tm-block-title d-inline-block">Requisition</h2>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row stc-view-product-row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <form action="" class="stc-view-product-form">
                                            <table class="table table-hover table-bordered trow">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Search</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td>
                                                    <input type="text" class="form-control" id="searchInputforTable" placeholder="Search here..">
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row stc-view-requisition-row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <form action="" class="stc-view-ag-requisition-form">
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
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
      });
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-req-add').toggle(1000);
          $('.stc-req-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('#stc_req_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-req-view').toggle(1000);
            $('.stc-req-add').fadeOut(500);
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

        $(window).resize(function() {
          if ($(window).width() < 480) {
            $('.trow').addClass('table-responsive');
          }
        }) 
      });
    </script>
    <script>      
      $(document).ready(function(){
        $("#searchInputforTable").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".stc-view-ag-requisition-form table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // call customer for sale
        stc_call_customer();
        function stc_call_customer(){
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "post",
            data      : {friday_customer:1},
            dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
              $('#expire_date').val(data['time']);
              $('.stc-select-customer').html(data['customer']);
            }
          });
        }

        $.urlParam = function(name){
          var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
          return results[1] || 0;
        }
        var url_param=$.urlParam('requi_id');

        stc_call_ag_order(url_param);
        function stc_call_ag_order(url_param){
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "post",
            data      : {
              callrequisitionlist:1,
              url_param:url_param
            },
            dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
              $('.stc-view-ag-requisition-form').html(data);
            }
          });
        }
        

            $('body').delegate('.stc-sup-requisition-viewlog-modal-btn', 'click',function(e){
                var data=$(this).parent().html();
                $('.items-log-display').html(data);
                $('.items-log-display').find('div').show();
                $('.items-log-display').find('a').remove();
            });

        var jsreqbegdate='';
        var jsreqenddate='';
        var jsreqcustomerid='';
        var jsreqnumber='';
        var jsreqagentname='';
        var jsreqstatus='';
        // filter challan
        // by date
        $('body').delegate('#purchaseproddatefilt', 'click', function(e){
          e.preventDefault();
          jsreqbegdate=$('.reqbegdate').val();
          jsreqenddate=$('.reqenddate').val();
          jsreqcustomerid=$("#stc-requisition-customer-in").val();
          jsreqnumber=$("#stc-requisition-number-finder").val();
          jsreqagentname=$("#stc-requisition-agents-finder").val();
          jsreqstatus=$(".stc-requisition-status-in").val();
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param);
        });

        // by customer id
        $("#stc-requisition-customer-in").change(function(e){
          e.preventDefault();
          jsreqbegdate=$('.reqbegdate').val();
          jsreqenddate=$('.reqenddate').val();
          jsreqcustomerid=$("#stc-requisition-customer-in").val();
          jsreqnumber=$("#stc-requisition-number-finder").val();
          jsreqagentname=$("#stc-requisition-agents-finder").val();
          jsreqstatus=$(".stc-requisition-status-in").val();
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param);
        });

        // by requisition number
        $("#stc-requisition-number-finder").on('keyup', function(e){
          e.preventDefault();
          jsreqbegdate=$('.reqbegdate').val();
          jsreqenddate=$('.reqenddate').val();
          jsreqcustomerid=$("#stc-requisition-customer-in").val();
          jsreqnumber=$("#stc-requisition-number-finder").val();
          jsreqagentname=$("#stc-requisition-agents-finder").val();
          jsreqstatus=$(".stc-requisition-status-in").val();
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param);
        });

        // by agent name
        $("#stc-requisition-agents-finder").on('keyup', function(e){
          e.preventDefault();
          jsreqbegdate=$('.reqbegdate').val();
          jsreqenddate=$('.reqenddate').val();
          jsreqcustomerid=$("#stc-requisition-customer-in").val();
          jsreqnumber=$("#stc-requisition-number-finder").val();
          jsreqagentname=$("#stc-requisition-agents-finder").val();
          jsreqstatus=$(".stc-requisition-status-in").val();
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param);
        });

        // by status
        $(".stc-requisition-status-in").change(function(e){
          e.preventDefault();
          jsreqbegdate=$('.reqbegdate').val();
          jsreqenddate=$('.reqenddate').val();
          jsreqcustomerid=$("#stc-requisition-customer-in").val();
          jsreqnumber=$("#stc-requisition-number-finder").val();
          jsreqagentname=$("#stc-requisition-agents-finder").val();
          jsreqstatus=$(".stc-requisition-status-in").val();
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param);
        });

        // filter function
        function stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqagentname, jsreqstatus, url_param){
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "post",
            data    : {
              stcreqperticularaction:1,
              phpreqbegdate:jsreqbegdate, 
              phpreqenddate:jsreqenddate, 
              phpreqcustomerid:jsreqcustomerid, 
              phpreqnumber:jsreqnumber,
              phpreqagents:jsreqagentname, 
              phpreqstatus:jsreqstatus,
              url_param:url_param
            },
            // dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('.stc-view-ag-requisition-form').html(data);
            }
          });
        }

        function call_pert_requisition(stc_order_id){
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : 'POST',
            data      : {
              call_requist_sub:1,
              stc_order_id:stc_order_id
            },
            dataType  : 'JSON',
            success:function(req){
              // console.log(req);
              $('#order-req-nos').val('STC/A/'+req['order_number']);
              $('#order-req-nos').after('<input type="hidden" value="'+stc_order_id+'" id="call-requisition-det">');
              $('#ag-challan-req').val(req['real_order_number']);
              $('#ag-req-date').val(req['order_date']);
              $('#cust_name_req').val(req['customer_name']);
              $('#ag-req-info').val('STC/AT/'+req['agentname']);
              $('#req-site-name').val(req['sitename']);
              $('#ag-req-refrence').val(req['sitename']);
              $('#site-req-address').val(req['siteaddress']);
              $('.ag-requis-items').html(req['line_items']);
              $('#stc-req-proj-id').val(req['project_id']);
              $('#stc-req-super-id').val(req['supervisor_id']);
              $('#stc-req-requis-id').val(req['requis_id']);
            }
          });
        }
        // show orders
        $('body').delegate('.stc_view_requist', 'click', function(e){
          e.preventDefault();
          $('.stc-req-view').fadeOut(500);
          $('.stc-req-add').toggle(500);
          var stc_order_id = $(this).attr("id");
          call_pert_requisition(stc_order_id);
        });

        var repid=0;
        var repitemid=0;
        $('body').delegate('.req-product-Modal', 'click', function(e){
          e.preventDefault();
          repid=$(this).attr("id");
          repitemid=$(this).attr("list-id");
          $('.res-product-Modal').modal("show");
          $('#stc-req-list-id-rep').val(repid);
          $('#stc-req-list-item-id-rep').val(repitemid);
        });
        
        $('body').delegate('.req-recieving-Modal', 'click', function(e){
          e.preventDefault();
          repid=$(this).attr("id");
          repitemid=$(this).attr("list-id");         
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : 'POST',
            data      : {
              call_requistdispatch_sub:1,
              repid:repid,
              repitemid:repitemid
            },
            dataType  : 'JSON',
            success:function(req){
              // console.log(req);
              $('.show-requisitiondispatched-items').html(req);
            }
          });
          $('.recieving-modal').modal("show");
        });

        $('body').delegate('.updaterecadhoc', 'click', function(e){
          var repid=$(this).attr("id");
          var adhoc_id=$(this).prev().val();
          repitemid=repitemid;
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : 'POST',
            data      : {
              update_adhoc_id_rec:1,
              adhoc_id:adhoc_id,
              repid:repid
            },
            dataType  : 'JSON',
            success:function(req){
              // console.log(req);
              if(req=="Success"){
                alert("Updated successfully!! Please reload modal to see changes.");
              }else{
                alert(req);
              }
            }
          });
        });

        var jsfiltercat;
        var jsfiltersubcat;
        var jsfiltername;
        // filter function
        // filter by cat
        $("#filterbycat").change(function(e){
          e.preventDefault();
          $('.stc-call-view-product-row').html("Loading...");
          jsfiltercat = $(this).val();
          jsfiltersubcat = $('#filterbysubcat').val();
          jsfiltername = $('#searchbystcname').val();
          stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat ,jsfiltername);
        });

        // filter by sub cat
        $("#filterbysubcat").change(function(e){
          e.preventDefault();
          $('.stc-call-view-product-row').html("Loading...");
          jsfiltercat = $('#filterbycat').val();
          jsfiltersubcat = $(this).val();
          jsfiltername = $('#searchbystcname').val();
          stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat ,jsfiltername);
        });

        // filter by name
        $("#searchbystcname").on('keyup', function(e){
          e.preventDefault();
          jsfiltercat = $('#filterbycat').val();
          jsfiltersubcat = $('#filterbysubcat').val();
          jsfiltername = $(this).val();
          var responset='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a></div>';
          var countlen=jsfiltername.length;
          if((+jsfiltername!='') && countlen>=3){
            stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat ,jsfiltername);
          }else{
            $('.stc-call-view-product-row').html(responset);
          }
        });

        // filter function
        function stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat ,jsfiltername){
          $.ajax({
            url     : "kattegat/ragnar_order.php",
            method  : "post",
            data    : {
              stceditsaleaction:1,
              phpfiltercatout:jsfiltercat,
              phpfiltersubcatout:jsfiltersubcat,
              phpfilternameout:jsfiltername
            },
            // dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('.stc-req-product-show').html(data);
            }
          });
        }

        // show requisition cart
        show_requisition_cart();
        function show_requisition_cart(){
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "POST",
            data      : {
              js_show_requi_cart:1
            },
            // dataType  : "JSON",
            success   : function(requition_pro){
              // console.log(requition_pro);
              $('.stc-req-product-sess-show').html(requition_pro);
            }
          });
        }

        // add to requisiton cart items
        $('body').delegate('.add_to_requist_cart', 'click', function(e){
          e.preventDefault();
          var product_id=$(this).attr("id");
          var list_id=repid;
          var product_item_tab_id=repitemid;
          var product_disquantity=$('#stcdisqty'+product_id).val();
          var product_quantity=$('#stcreqqty'+product_id).val();
          var product_price=$('#stcpdreqprice'+product_id).val();
          var js_requi_sess="addrequistcart";
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "POST",
            data      : {
              js_requi_sess:js_requi_sess,
              product_id:product_id,
              list_id:list_id,
              product_item_tab_id:product_item_tab_id,
              product_disquantity:product_disquantity,
              product_quantity:product_quantity,
              product_price:product_price
            },
            // dataType  : "JSON",
            success   : function(product_sesso){
              alert(product_sesso);
              show_requisition_cart();
            }
          });
        });

        // delete item from reuiition cart
        $('body').delegate('.stcdelrequibtn', 'click', function(e){
          e.preventDefault();
          var product_id=$(this).attr("id");
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : "POST",
            data      : {
              js_req_delitems:1,
              product_id:product_id
            },
            // dataType  : "JSON",
            success   : function(product_sesso){
              alert(product_sesso);
              show_requisition_cart();
            }
          });
        });

        // save requisiton and transport to the challan
        $('body').delegate('.save-ag-req-challan', 'click', function(e){
          e.preventDefault();
          var stc_ch_id=$('#ag-challan-req').val();
          var stc_ch_notes=$('#ag-req-notes').val();
          var stc_ch_tandc=$('#ag-req-tandc').val();
          var stc_ch_waybilno=$('#ag-req-way-bill-no').val();
          var stc_ch_lrno=$('#ag-req-lr-no').val();
          var proj_id=$('#stc-req-proj-id').val();
          var super_id=$('#stc-req-super-id').val();
          var requise_id=$('#stc-req-requis-id').val();
          $.ajax({
              url     : "kattegat/ragnar_order.php",
              method  :'POST',
              data    : {
                go_to_req_challan:1,
                stc_ch_id:stc_ch_id,
                stc_ch_notes:stc_ch_notes,
                stc_ch_tandc:stc_ch_tandc,
                stc_ch_waybilno:stc_ch_waybilno,
                stc_ch_lrno:stc_ch_lrno,
                proj_id:proj_id, 
                super_id:super_id,
                requise_id:requise_id
              },
              success:function(response){
                // console.log(response);
                alert(response);
                show_requisition_cart();
                $('.stc-req-add').fadeOut(1000);
                $('.stc-req-view').toggle(500);
              }
          });
        });

        // change status of requisition
        $('body').delegate('.set-req-to-cleaned', 'click', function(e){
          e.preventDefault();
          var stc_req_no=$('#ag-challan-req').val();
          $.ajax({
            url       : "kattegat/ragnar_order.php",
            method    : 'POST',
            data      : {
              req_set_to_clean:1,
              stc_req_no:stc_req_no
            },
            dataType  : 'JSON',
            success:function(response){
              // console.log(response);
              alert(response);
              $('.stc-req-add').fadeOut(1000);
              $('.stc-req-view').toggle(500);
            }
          });
        });

        var repid2=0;
        var repitemid2=0;
        $('body').delegate('.req-product-Modal-cash-close', 'click', function(e){
          e.preventDefault();
          repid2=$(this).attr("id");
          repitemid2=$(this).attr("list-id");
          orderqty=$(this).attr("orderqty");
          $('#stcdispatchedqty').val('');
          $('.res-product-Modal-cash-close').modal("show");
          $('#stc-req-list-id-rep2').val(repid2);
          $('#stc-req-list-item-id-rep2').val(repitemid2);
          $('#stc-req-list-item-id-orderqty').remove();
          $('#stc-req-list-item-id-rep2').after('<input type="hidden" id="stc-req-list-item-id-orderqty" value="'+orderqty+'">');
        });

        // save dispatch quantity
        $('body').delegate('.stcdispatchedbtn', 'click', function(e){
          e.preventDefault();
          var req_id=$('#stc-req-list-id-rep2').val();
          var req_item_id=$('#stc-req-list-item-id-rep2').val();
          var dispatch_qnty=parseFloat($('#stcdispatchedqty').val(), 10);
          var orderqty=parseFloat($('#stc-req-list-item-id-orderqty').val(), 10);
          var poadhocitem=$('#poadhocitem').val();
          var balanced_qty=parseInt($('#poadhocitem option:selected').attr('qty'));
          var balanced_qty2=$('.stcbalancedqty').val();
          var validated=0;
          if($('#searchInput').val()==''){
            validated=1;
          }else{
            if((dispatch_qnty>orderqty)){
              validated=0;
              alert("Invalid quantity.");
            }else if((dispatch_qnty>balanced_qty2)){
              validated=0;
              alert("Invalid quantity.");
            }else{
              validated=1;
            }
          }
          if(validated==1){
            // if(orderqty!="0.00"){
              $.ajax({
                url       : "kattegat/ragnar_order.php",
                method    :'POST',
                data      : {
                  stc_dispatch_hit:1,
                  stc_req_id:req_id,
                  stc_req_item_id:req_item_id,
                  stc_dispatch_qty:dispatch_qnty,
                  poadhocitem:poadhocitem
                },
                success   : function(response_dis){
                  var response=response_dis.trim();
                  if(response=="Item dispatched successfully."){
                    alert(response_dis);
                      stc_call_ag_order(url_param);
                    // call_pert_requisition(req_id);
                    $('.res-product-Modal-cash-close').modal("hide");
                  }else{
                    alert(response_dis);
                  }
                }
              });
            // }else{
            //   alert("Item not found.");
            // }
          }
        });
        
        $('body').delegate('.stc_add_togld', 'click', function(e){
          e.preventDefault();
          var req_id=$(this).attr('id');
          if(confirm("Are you sure want transfer to gld?")){
            $(this).closest('td').html('GLD');
            $.ajax({
              url       : "kattegat/ragnar_order.php",
              method    :'POST',
              data      : {
                stc_gld_hit:1,
                stc_req_id:req_id
              },
              success   : function(response_dis){
                var response=response_dis.trim();
                if(response=="success"){
                  alert("Item added to GLD.");
                }else{
                  alert(response_dis);
                  // window.location.reload();
                }
              }
            });
          }
        });
        
        $('body').delegate('.removeitemsfromdispatch', 'click', function(e){
          e.preventDefault();
          var req_id=$(this).attr('id');
          if(confirm("Are you sure want remove this item?")){
            $('.removeit').removeAttr('class');
            $(this).closest('tr').attr('class', 'removeit');
            $.ajax({
              url       : "kattegat/ragnar_order.php",
              method    :'POST',
              data      : {
                stc_remove_dispatched:1,
                stc_req_id:req_id
              },
              success   : function(response_dis){
                var response=response_dis.trim();
                if(response=="success"){
                  stc_call_ag_order(url_param);
                  $('.removeit').remove();
                  alert("Item Removed successfully.");
                }else{
                  alert(response_dis);
                  // window.location.reload();
                }
              }
            });
          }
        });

        $('body').delegate('.poadhocitem', 'change', function(e){
          e.preventDefault();
          var qty=$('#poadhocitem option:selected').attr('qty');
          var unit=$('#poadhocitem option:selected').attr('unit');
          var rack=$('#poadhocitem option:selected').attr('rack');
          $('.stcbalancedqty').val(qty);
          $('.stcbalancedqtyrack').val(rack);
          $('.stcbalancedqtyunit').val(unit);
        });
        
        
        // Cache the select element for better performance
        var $select = $('#poadhocitem');

        // Cache the input element
        var $searchInput = $('#searchInput');

        // Get all options
        var $options = $select.find('option');

        // Handle input keyup event
        $('body').delegate('#searchInput', 'keyup', function(e){
            var filter = $(this).val().toUpperCase();
            // Clear the select options
            $select.empty();

            // Loop through all options
            $options.each(function() {
                var text = $(this).text().toUpperCase();

                // Show/hide options based on the search filter
                if (text.indexOf(filter) > -1) {
                    $select.append($(this).clone()); // Append matching options
                }
            });
            $('.poadhocitem').change();
        });
      });
    </script>
    <!-- Status Change Modal -->
    <div class="modal fade" id="statusRemarkModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Remarks for Pending</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <textarea class="form-control" id="statusRemarkInput" placeholder="Enter remarks"></textarea>
            <input type="hidden" id="statusChangeId">
            <input type="hidden" id="statusChangeTo">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveStatusRemark">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function(){
        var preq_id = 0;
        // Delegate click for status change button
        $('body').on('click', '.btn-change-status', function() {
          preq_id = $(this).attr('id');
          var status = $(this).attr('status');
          $('#statusChangeId').val(preq_id);
          $('#statusRemarkInput').val('');
        });
        // Save status and remarks
        $('#saveStatusRemark').on('click', function() {
          var remarks = $('#statusRemarkInput').val();
          preq_id=$('#statusChangeId').val();
          if(remarks.trim() === '') {
            alert('Please enter remarks for pending status.');
            return;
          }
          changeStatus(preq_id, 9, remarks);
        });
        function changeStatus(preq_id, status, remarks) {
          $.ajax({
            url: 'kattegat/ragnar_order.php',
            method: 'POST',
            data: { update_requisition_status: 1, id: preq_id, status: status, remarks: remarks },
            dataType  : 'JSON',
            success: function(response) {
              // console.log(response);
              if(response.success==true){
                alert(response.message);
              }else{
                alert(response.message);
              }
              // Optionally reload the table or update the row
            }
          });
        }
      });
    </script>
</body>
</html>

<!-- modal call beg -->
<div class="modal fade bd-example-modal-lg res-product-Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" id="stc-req-list-item-id-rep" value="0";>
                <input type="hidden" id="stc-req-list-id-rep" value="0";>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <table class="table table-hover ">
                  <thead>
                      <tr>
                          <th scope="col">By Category</th>
                          <th scope="col">By Name</th>
                          <th scope="col">By Sub Category</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="card-border mb-3 card card-body border-success">
                          <select
                            class="custom-select tm-select-accounts call_cat stcprosearchsame"
                            id="filterbycat"
                            name="stcpdcategory"
                          >
                          </select>
                        </div>
                      </td>
                      <td>
                        <div class="card-border mb-3 card card-body border-success">
                          <input
                            id="searchbystcname"
                            name="stcsearchpdname"
                            type="text"
                            placeholder="Product Name"
                            class="form-control validate stcprosearchsame"
                          />
                          <input type="hidden" name="search_alo_in">
                        </div>
                      </td>
                      <td>
                        <div class="card-border mb-3 card card-body border-success">
                          <select
                            class="custom-select tm-select-accounts call_sub_cat stcprosearchsame"
                            id="filterbysubcat"
                            name="stcpdsubcategory"
                          >
                          </select>
                        </div>
                      </td>
                      <td>
                        <a 
                          style="
                            font-size: 20px;
                            background: yellow;
                            border-radius: 50%;
                            padding: 5px;
                            margin: 0;
                          " 
                          href="#" 
                          class="upward"
                        ><i class="fas fa-arrow-up"></i>
                        </a>
                        <a 
                          style="
                            font-size: 20px;
                            background: yellow;
                            border-radius: 50%;
                            padding: 5px;
                            margin: 0;
                            display: none;
                          " 
                          href="#" 
                          class="downward"
                        ><i class="fas fa-arrow-down"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-12">
                <div class="row stc-req-product-show">                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-req-product-sess-show">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg res-product-Modal-cash-close" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Dispatch Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" id="stc-req-list-item-id-rep2" value="0";>
                <input type="hidden" id="stc-req-list-id-rep2" value="0";>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5 for="poadhocitem">
                    Item
                  </h5>
                  <input type="text" id="searchInput" class="form-control" placeholder="Search item">
                  <select
                    class="form-control poadhocitem"
                    id="poadhocitem"
                  >
                    <?php 
                      include_once("../MCU/db.php");
                      $sqlqry=mysqli_query($con, "
                        SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_itemdesc`, `stc_purchase_product_adhoc_qty`, `stc_purchase_product_adhoc_unit`, `stc_rack_name` 
                        FROM `stc_purchase_product_adhoc`
                        LEFT JOIN `stc_rack` ON `stc_purchase_product_adhoc_rackid`=`stc_rack_id`
                        WHERE `stc_purchase_product_adhoc_status`=1
                      ");
                      if(mysqli_num_rows($sqlqry)>0){
                        echo '<option value="NA">Select</option>';
                        foreach($sqlqry as $sqlrow){
                          $poadid=$sqlrow['stc_purchase_product_adhoc_id'];
                          $checsql=mysqli_query($con, "
                            SELECT SUM(`stc_cust_super_requisition_list_items_rec_recqty`) as recqty
                            FROM `stc_cust_super_requisition_list_items_rec`
                            WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$poadid."'
                          ");
                          $result=mysqli_num_rows($checsql)>0 ? mysqli_fetch_assoc($checsql) : 0;
                          $rec_qty=$result!=0 ? $result['recqty'] : 0;
                          
                          $deliveredgld=0;
                          $sql_qry=mysqli_query($con, "
                            SELECT `qty` FROM `gld_challan` WHERE `adhoc_id`='".$sqlrow['stc_purchase_product_adhoc_id']."'
                          ");
                          if(mysqli_num_rows($sql_qry)>0){
                            foreach($sql_qry as $sql_row){
                              $deliveredgld+=$sql_row['qty'];
                            }
                          }
                          $balanced_qty=$sqlrow['stc_purchase_product_adhoc_qty'] - ($rec_qty + $deliveredgld);
                          if($balanced_qty>0){
                            echo '<option value="'.$sqlrow['stc_purchase_product_adhoc_id'].'" qty="'.$balanced_qty.'" rack="'.$sqlrow['stc_rack_name'].'" unit="'.$sqlrow['stc_purchase_product_adhoc_unit'].'">'.$sqlrow['stc_purchase_product_adhoc_itemdesc'].'</option>';
                          }
                        }
                      }else{
                        echo '<option value="NA">No record found.</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Balanced Quantity
                  </h5>
                  <input
                    id="stcbalancedqty"
                    name="stcbalancedqty"
                    type="text"
                    placeholder="Balanced Quantity"
                    class="form-control validate stcbalancedqty"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Unit
                  </h5>
                  <input
                    id="stcbalancedqtyunit"
                    name="stcbalancedqtyunit"
                    type="text"
                    placeholder="Unit"
                    class="form-control validate stcbalancedqtyunit"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Rack
                  </h5>
                  <input
                    id="stcbalancedqtyrack"
                    name="stcbalancedqtyrack"
                    type="text"
                    placeholder="Rack"
                    class="form-control validate stcbalancedqtyrack"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Dispatched Quantity
                  </h5>
                  <input
                    id="stcdispatchedqty"
                    name="stcdispatchedqty"
                    type="text"
                    placeholder="Enter Dispatched Quantity"
                    class="form-control validate stcdispatchedqty"
                  />
                </div>
              </div>
              <div class="col-12">
                <div class="card-border mb-3 card card-body border-success">
                  <button type="submit" class="form-control btn btn-success stcdispatchedbtn">Save</button>
                </div>
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

<div class="modal fade  recieving-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Recieving Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" id="stc-req-list-item-id-rep2" value="0";>
                <input type="hidden" id="stc-req-list-id-rep2" value="0";>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5 for="poadhocitem">
                    Item
                  </h5>
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody class="show-requisitiondispatched-items"></tbody>
                    </table>
                </div>
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
<!-- modal call end -->
 
<div class="modal fade bd-log-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Items Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">View Items Log</h5>
                                <div class="items-log-display"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>