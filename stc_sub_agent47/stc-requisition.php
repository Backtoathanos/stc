<?php  
session_start(); 
if(isset($_SESSION["stc_agent_sub_id"])){ 
}else{ 
    header("Location:index.html"); 
} 
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Requisition - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <style>
      .fade:not(.show) {
        opacity: 10;
      }
    </style>
</head>
<body>
    <!-- ****************************************** -->
    <div class="modal fade" id="stc-sup-requisition-rece-modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Reciever</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <p>Item Recieved</p>
                  <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        Enter Quantity Recieved 
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                       <input type="number" class="form-control stc-super-own-qnty-rec-text">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <button class="btn btn-success stc-super-own-qnty-rec-btn" href="#">Save</button>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ****************************************** -->
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h3>My Requisition</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Requisition</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12"> 
                                            <div class="main-card mb-3 card">
                                                <div class="card-body">
                                                <a href="javascript:void(0)" class="btn btn-primary form-control" data-toggle="modal" data-target=".bd-requisition-modal-lg">Add Requisition</a>
                                                    <h5 class="card-title">View Requisition</h5>
                                                    <form class="needs-validation" novalidate>
                                                        <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-7 days", strtotime($date)));
                                                        ?>   
                                                        <div class="form-row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustom01">From</label>
                                                                <input type="date" class="form-control" id="stc-sup-req-beg-date" value="<?php echo $effectiveDate;?>" required>
                                                                <div class="valid-feedback">
                                                                    Looks good!
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustom01">To</label>
                                                                <input type="date" class="form-control" id="stc-sup-req-end-date" value="<?php echo $newDate;?>" required>
                                                                <div class="valid-feedback">
                                                                    Looks good!
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustomUsername">Search</label>
                                                                <div class="input-group">
                                                                    <a class="btn btn-primary stc-sup-req-search"><i class="fa fa-search"></i> Search</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                </div>
                                                <div class="card-body stc-requisition-search-result" style="overflow-x: auto;">
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
            });

            $('body').delegate('.search-icon-2', 'click', function(e){
                var pd_title=$('.agent-pro-search').val();
                if(pd_title!=""){
                    window.location.href="stc-product.php?pd_name="+pd_title;
                }
            });    
        });
    </script>
    <script>
        $(document).ready(function(){
            var url = document.location.href;
            var qs = url.substring(url.indexOf('?') + 1).split('&');
            for(var i = 0, result = {}; i < qs.length; i++){
                qs[i] = qs[i].split('=');
                result[qs[i][0]] = decodeURIComponent(qs[i][1]);
            }
            if(result.status=="add"){
                $('.nav-link').removeClass("active");
                $('.tab-pane').removeClass("active");
                $('.nav-link:eq(1)').addClass("active");
                $('.tab-pane:eq(1)').addClass("active");
            }
            
            sdlno=result.sdl==undefined ? "0" : result.sdl;

            // search requistion by date
            $('body').delegate('.stc-sup-req-search', 'click', function(e){
                e.preventDefault();
                $('.stc-requisition-search-result').html("Loading...");
                var supreqfromdate=$('#stc-sup-req-beg-date').val();
                var supreqtodate=$('#stc-sup-req-end-date').val();
                $.ajax({
                    url     : "nemesis/stc_agcart.php",
                    method  : "POST",
                    data    : {
                            call_searched_requisition:1,
                            supreqfromdate:supreqfromdate,
                            supreqtodate:supreqtodate
                    },
                    success : function(reequisitionresult){
                        // console.log(response);
                        $('.stc-requisition-search-result').html('<input type="text" class="form-control stc-requisition-search" placeholder="Search here...">'+reequisitionresult);
                    }
                });
            });

            $('body').delegate('.stc-requisition-search', 'keyup', function(e){
                var searchText = $(this).val().toLowerCase(); // Convert to lowercase for case-insensitive comparison
                filterTable(searchText);
            });

            // Function to filter the table based on the input text
            function filterTable(searchText) {
                $('.stc-requisition-search-result > table > tbody tr').each(function () {
                    var rowText = $(this).text().toLowerCase();
                    // Toggle the visibility of the row based on whether it contains the search text
                    $(this).toggle(rowText.includes(searchText));
                });
            }

            // call listed items from requisition
            $('body').delegate('.ag-show-requisition-items-hit', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $('#togdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_agcart.php",
                    method : "POST",
                    data : {
                        get_requisition_pert:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        $('#togdiv'+odid).html(orders);
                    }
                });
            });

            // show requistion cart items
            show_Dailylist();
            function show_Dailylist(){
                $.ajax({
                    url     : "nemesis/stc_agcart.php",
                    method  : "POST",
                    data    : {show_Dailylist:1},
                    success : function(response){
                        // console.log(response);
                        $('.stc-sup-call-list-items').html(response);
                    }
                });
            }

            // add requisition items in a cart
            $('.stc-sup-form-create').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_agcart.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    success     : function(response){
                        // console.log(response);
                        alert(response);
                        show_Dailylist();
                    } 
                });
            });

            // remove requisition items from cart
            $('body').delegate('.removlistitems', 'click', function(e){
                e.preventDefault();
                var del_item=$(this).attr("id");
                $.ajax({
                    url     : "nemesis/stc_agcart.php",
                    method  : "POST",
                    data    : {
                            delete_Dailylist:1,
                            del_item:del_item
                    },
                    success : function(response){
                        // console.log(response);
                        alert(response);
                        show_Dailylist();
                    }                    
                });
            });

            // save requisition items from cart
            $('body').delegate('.stc-save-requisition', 'click', function(e){
                var sup_site=$('#load_cust_sup_site').val();
                $.ajax({
                    url     : "nemesis/stc_agcart.php",
                    method  : "POST",
                    data    : {
                            save_Dailylist:1,
                            sup_site:sup_site,
                            sdlno:sdlno
                    },
                    success : function(response){
                        // console.log(response);
                        alert(response);
                        show_Dailylist();
                    }                    
                });
            });

            // show model on click reciveing for recieving purposes
            var js_super_rec_value='';
            var js_super_rec_item_value='';
            var js_stc_super_rec_qnty_cqty='';
            $('body').delegate('.stc-sup-requisition-rece-modal-btn', 'click', function(e){
                e.preventDefault();
                js_super_rec_value=$(this).attr("stc-req-id");
                js_super_rec_item_value=$(this).attr("stc-req-item-id");
                js_stc_super_rec_qnty_cqty=$(this).attr("stc-req-item-checkqty");
                $('#stc-sup-requisition-rece-modal').modal('show');
            });
            
            // save recieving qunatity to table
            $('body').delegate('.stc-super-own-qnty-rec-btn', 'click',function(e){
                e.preventDefault();
                var js_stc_super_rec_qnty_text=$('.stc-super-own-qnty-rec-text').val();
                $.ajax({
                    url      : "nemesis/stc_agcart.php",
                    method   : "POST",
                    data     : {
                        stc_rec_qntyhit:1,
                        stc_super_rec_qnty_text:js_stc_super_rec_qnty_text,
                        super_rec_value:js_super_rec_value,
                        super_rec_item_value:js_super_rec_item_value,
                        js_stc_super_rec_qnty_cqty:js_stc_super_rec_qnty_cqty
                    },
                    success  : function(rec_response){
                        var rec_response=rec_response.trim()
                        // console.log(trimst.length);
                        if(rec_response=="yes"){
                            alert("Recieving Confirmed!!! Thankyou.");
                            $('#stc-sup-requisition-rece-modal').modal('hide');
                            $('.stc-sup-req-search').click();
                        }else{
                            alert(rec_response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
<div class="modal fade bd-requisition-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Requisition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-xl-12"> 
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h5 class="card-title">Create My Requisition</h5>
                                                <form class="stc-sup-form-create" novalidate>
                                                    <div class="form-row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="validationCustom01">Item Desc</label>
                                                            <input type="text" class="form-control" name="stc-sup-desc" placeholder="Enter item name" required>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="validationCustom02">Item Qty</label>
                                                            <input type="number" class="form-control" name="stc-sup-qty" placeholder="Enter Quantity" required>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                                <label for="validationCustomUsername">Item Unit</label>
                                                                <select class="form-control" name="stc-sup-unit">
                                                                    <option value="BAG">BAG</option>
                                                                    <option value="BOTTLE">BOTTLE</option>
                                                                    <option value="BOX">BOX</option>
                                                                    <option value="BUNDLE">BUNDLE</option>
                                                                    <option value="CASE">CASE</option>
                                                                    <option value="CBM">CBM</option>
                                                                    <option value="COIL">COIL</option>
                                                                    <option value="FEET">FEET</option>
                                                                    <option value="JAR">JAR</option>
                                                                    <option value="KGS">KGS</option>
                                                                    <option value="LOT">LOT</option>
                                                                    <option value="LTR">LTR</option>
                                                                    <option value="MTR">MTR</option>
                                                                    <option value="MTS">MTS</option>
                                                                    <option value="NOS" selected>NOS</option>
                                                                    <option value="PAIR">PAIR</option>
                                                                    <option value="PKT">PKT</option>
                                                                    <option value="ROLL">ROLL</option>
                                                                    <option value="SET">SET</option>
                                                                    <option value="SQFT">SQFT</option>
                                                                    <option value="SQMT">SQMT</option>
                                                                </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="validationCustomUsername">Item Type</label>
                                                            <select class="form-control" name="stc-sup-type">
                                                                <option value="Consumable">CONSUMABLE</option>
                                                                <option value="PPE">PPE</option>
                                                                <option value="Supply">SUPPPLY</option>
                                                                <option value="Tools & Tackles">TOOLS & TACKLES</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="validationCustom02">Priority</label>
                                                            <select class="form-control" name="stc-sup-priority">
                                                                <option value="1">Normal</option>
                                                                <option value="2">Urgent</option>
                                                            </select>
                                                            <div class="valid-feedback">
                                                                Looks good!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="stc-sup-hit">
                                                    <button class="btn btn-primary" type="submit">Add</button>
                                                </form>
                                                
                                                <script>
                                                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                                                    (function() {
                                                        'use strict';
                                                        window.addEventListener('load', function() {
                                                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                                            var forms = document.getElementsByClassName('needs-validation');
                                                            // Loop over them and prevent submission
                                                            var validation = Array.prototype.filter.call(forms, function(form) {
                                                                form.addEventListener('submit', function(event) {
                                                                    if (form.checkValidity() === false) {
                                                                        event.preventDefault();
                                                                        event.stopPropagation();
                                                                    }
                                                                    form.classList.add('was-validated');
                                                                }, false);
                                                            });
                                                        }, false);
                                                    })();
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12"> 
                                        <div class="card mb-3 widget-content bg-midnight-bloom">
                                            <select class="btn btn-success form-control load_cust_agents" id="load_cust_sup_site">
                                                <option>No Customers Found!!!</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12">
                                        <div class="main-card card stc-sup-call-list-items" style="overflow-x:auto;">
                                        </div>
                                    </div>
                                </div>
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