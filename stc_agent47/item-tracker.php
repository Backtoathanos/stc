<?php  
session_start(); 
if(isset($_SESSION["stc_agent_id"])){ 
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
    <title>Item Tracker - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
    <style>        
        .close-tag-beg{
            display: none;
        }
        .fade:not(.show) {
          opacity: 10;
        }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php include_once("ui-setting.php");?>        
        <div class="app-main">
                <?php include_once("sidebar-nav.php");?>                   
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-display1 icon-gradient bg-premium-dark">
                                        </i>
                                    </div>
                                    <div>Item Tracker
                                        <div class="page-title-subheading">You can look at all the detailed information about items here.<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#ppetracker">
                                    <span>PPE Tracker </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#itemtracker">
                                    <span>Item Tracker </span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="ppetracker" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <a href="javascript:void(0)" class="btn btn-primary form-control" data-toggle="modal" data-target=".bd-ppetracker-modal-lg">Add PPE Tracker</a>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" id="searchInput" class="form-control" placeholder="Type to search...">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ID</th>
                                                                    <th class="text-center">Employee Name</th>
                                                                    <th class="text-center">Type of PPE</th>
                                                                    <th class="text-center">Location</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th class="text-center">Unit</th>
                                                                    <th class="text-center">Date of Issue</th>
                                                                    <th class="text-center">Duration</th>
                                                                    <th class="text-center">Next Issue Date</th>
                                                                    <th class="text-center">Remarks</th>
                                                                    <!-- <th class="text-center">Action</th> -->
                                                                </tr>
                                                            </thead>
                                                            <tbody class="item-tracker-show"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="itemtracker" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <h1>Coming soon</h1>
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
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
                // var pd_title=$('.agent-pro-search').val();
                // window.location.href="stc-product.php?pd_name="+pd_title;
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
        $(document).ready(function(e){
            item_tracker_call();
            function item_tracker_call(){
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        call_item_tracker:1
                    },
                    success : function(response){
                        $('.item-tracker-show').html(response);
                    }
                });
            }

            $('#searchInput').on('input', function () {
                // Get the search value
                var searchTerm = $(this).val().toLowerCase();

                // Filter the table rows based on the search value
                $('.item-tracker-show tr').each(function () {
                    // Get the text content of each cell in the row
                    var rowText = $(this).text().toLowerCase();

                    // Show or hide the row based on whether it matches the search term
                    $(this).toggle(rowText.includes(searchTerm));
                });
            });

            // save dispatch
            $('body').delegate('.it-save', 'click', function(e){
                e.preventDefault();
                var user_id = $('.it-emp-name').val();
                var ppe_type = $('.it-ppe-type').val();
                var qty = $('.it-qty').val();
                var unit = $('.it-unit').val();
                var issue_date = $('.it-issue-date').val();
                var validity = $('.it-validity').val();
                var remarks = $('.it-remarks').val();
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        save_item_tracker:1,
                        user_id:user_id,
                        ppe_type:ppe_type,
                        qty:qty,
                        unit:unit,
                        issue_date:issue_date,
                        validity:validity,
                        remarks:remarks
                    },
                    success : function(response){
                        var obj_response=response.trim();
                        if(obj_response=="yes"){
                            alert("Record updated successfully!!!");
                            $('.it-save').parent().parent().parent().find('input').val('');
                            $('.it-save').parent().parent().parent().find('textarea').val('');
                            $('.it-save').parent().parent().parent().find('select').val('NA');
                            item_tracker_call();
                        }else if(obj_response=="reload"){
                            window.location.reload();
                        }else if(obj_response=="no"){
                            alert("Something went wrong. Record not updated");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

<div class="modal fade bd-ppetracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Procurement Tracker</h5>
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
                                    <div class="col-md-6">
                                        <h5>Employee Name</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control it-emp-name" placeholder="Enter employee name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Type of PPE</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <select class="form-control it-ppe-type">
                                                <option value="NA">Select</option>
                                                <option>Safety Shoes</option>
                                                <option>Safety Jacket</option>
                                                <option>Safety Belt</option>
                                                <option>Safety Helmet</option>
                                                <option>Hand Gloves</option>
                                                <option>Leg Guard</option>
                                                <option>Safety Goggles</option>
                                                <option>Ear Plug</option>
                                                <option>Nose Mask</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Quantity</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="number" class="form-control it-qty" placeholder="Enter quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Unit</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <select class="form-control it-unit">
                                                <option value="NA">Select</option>
                                                <option>Nos</option>
                                                <option>Pair</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Date of Issue</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="date" class="form-control it-issue-date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Validity</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="number" class="form-control it-validity" placeholder="Enter validity in months">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>Remarks</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <textarea class="form-control it-remarks" placeholder="Enter remarks"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card mb-3 widget-content">
                                            <button class="form-control btn btn-success it-save">Save</button>
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
