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
    <title>Consumption - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h3>My Consumption</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Consumption</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#create-req">
                                <span>Create Consumption</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12"> 
                                            <div class="main-card mb-3 card">
                                                <div class="card-body">
                                                    <h5 class="card-title">View Requisition</h5>
                                                    <form class="needs-validation" novalidate>
                                                        <div class="form-row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustom01">From</label>
                                                                <input type="date" class="form-control" id="stc-sup-from-date" required>
                                                                <div class="valid-feedback">
                                                                    Looks good!
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustom01">To</label>
                                                                <input type="date" class="form-control" id="stc-sup-to-date" required>
                                                                <div class="valid-feedback">
                                                                    Looks good!
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="validationCustomUsername">Action</label>
                                                                <div class="input-group">
                                                                    <a class="btn btn-primary stc-sup-req-search"><i class="fa fa-search"></i> Search</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12"> 
                                            <div class="main-card mb-3 card">
                                                <div class="card-body stc-consumption-search-result">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="create-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Location/Site Name :</h5><br>
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <select class="btn btn-success form-control load_site_name_consump" id="load_site_sup_name_consump"><option>No Site Found!!!</option>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Department :</h5><br>
                                            <input type="text" class="form-control stc-consump-sub-location" placeholder="Enter Department">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Job Details :</h5><br>
                                            <textarea type="text" class="form-control stc-consump-job-details" placeholder="Enter Job Details"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <form class="stc-sup-form-create" novalidate>
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationCustom01">Items Desc</label>
                                                        <select class="form-control" name="stc-sup-desc" id="stc-sup-desc" required>
                                                            <option value='NA'>Please Select Sitename First</option>
                                                        </select>
                                                        <input type="hidden" class="form-control stc-sup-desc2" name="stc-sup-desc2">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label for="validationCustomUsername">Items unit</label>
                                                        <input type="hidden" class="form-control stc-sup-unit" name="stc-sup-unit">
                                                        <input type="text" class="form-control stc-sup-unit" disabled>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label for="validationCustomUsername">Recieved Quantity</label>
                                                        <input type="hidden" class="form-control stc-sup-rqty" name="stc-sup-rqty">
                                                        <input type="number" class="form-control stc-sup-rqty" disabled>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label for="validationCustom02">Cosumed Quantity</label>
                                                        <input type="number" class="form-control" name="stc-sup-qty" placeholder="Enter Quantity" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="stc-sup-hit">
                                                <button class="btn btn-primary" type="submit">Add</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12">
                                    <div class="main-card card stc-sup-call-list-items">
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
            // call site name vaia supervisor
            call_location();
            function call_location(){
                $.ajax({
                    url     : "nemesis/stc_consump.php",
                    method  : "POST",
                    data    : {call_location:1},
                    success : function(response){
                        // console.log(response);
                        $('.load_site_name_consump').html(response);
                    }
                });
            }

            // call consumption item list
            call_consumption_items_cart();
            function call_consumption_items_cart(){
                $.ajax({
                    url     : "nemesis/stc_consump.php",
                    method  : "POST",
                    data    : {call_consumption:1},
                    success : function(response){
                        // console.log(response);
                       $('.stc-sup-call-list-items').html(response);
                    }
                });
            }

            // asd
            $('body').delegate('#load_site_sup_name_consump', 'change', function(e){
                e.preventDefault();
                // $(this).css("disabled");
                var site_id=$(this).val();
                $.ajax({
                    url         : "nemesis/stc_consump.php",
                    method      : "POST",
                    data        : {
                        stc_call_item_using_site:1,
                        site_id:site_id
                    },
                    success     : function(response_site){
                        // console.log(response_site);
                        $('#stc-sup-desc').html(response_site);
                    }
                });
            });

            // call 
            $('body').delegate('#stc-sup-desc', 'change', function(e){
                e.preventDefault();
                var item_id=$(this).val();
                $.ajax({
                    url         : "nemesis/stc_consump.php",
                    method      : "POST",
                    data        : {
                        stc_call_recievd_items:1,
                        item_id:item_id
                    },
                    dataType    : "JSON",
                    success     : function(response){
                        // console.log(response);
                        $('.stc-sup-unit').val(response['item_unit']);
                        $('.stc-sup-rqty').val(response['item_qty']);
                        $('.stc-sup-desc2').val(response['item_desc']);
                    }
                });
            });

            // add consumption items in a cart
            $('.stc-sup-form-create').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_consump.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    success     : function(response){
                        // console.log(response);
                        var response_trim=response.trim();
                        if(response_trim=="List Item Quantity Increased!!!"){
                            alert(response);
                            call_consumption_items_cart();
                            $('.stc-sup-form-create')[0].reset();
                        } else if (response_trim=="List Created & Item Added to List!!!"){
                            alert(response);
                            call_consumption_items_cart();
                            $('.stc-sup-form-create')[0].reset();
                        } else if (response_trim=="Items Added to List!!!"){
                            alert(response);
                            call_consumption_items_cart();
                            $('.stc-sup-form-create')[0].reset();
                        }else{
                            alert(response);
                        }
                    } 
                });
            });

            // remove consumption items from cart
            $('body').delegate('.removlistitems', 'click', function(e){
                e.preventDefault();
                var del_item=$(this).attr("id");
                $.ajax({
                    url     : "nemesis/stc_consump.php",
                    method  : "POST",
                    data    : {
                            delete_Dailylist:1,
                            del_item:del_item
                    },
                    success : function(response){
                        // console.log(response);
                        alert(response);
                        call_consumption_items_cart();
                    }                    
                });
            });

            // save consumption items from cart
            $('body').delegate('.stc-save-consumption', 'click', function(e){
                var sup_site=$('.load_site_name_consump').val();
                var sup_location=$('.stc-consump-sub-location').val();
                var job_details=$('.stc-consump-job-details').val();
                $.ajax({
                    url     : "nemesis/stc_consump.php",
                    method  : "POST",
                    data    : {
                            save_Dailyconsumption:1,
                            sup_site:sup_site,
                            sup_location:sup_location,
                            job_details:job_details
                    },
                    success : function(response){
                        // console.log(response);
                        alert(response);
                        call_consumption_items_cart();
                    }                    
                });
            });

            // call requisition
            $('body').delegate('.stc-sup-req-search', 'click', function(e){
                e.preventDefault();
                var begdate=$('#stc-sup-from-date').val();
                var enddate=$('#stc-sup-to-date').val();
                $.ajax({
                    url     : "nemesis/stc_consump.php",
                    method  : "POST",
                    data    : {
                        stc_call_consumption:1,
                        begdate:begdate,
                        enddate:enddate
                    },
                    success : function(response){
                        $('.stc-consumption-search-result').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>