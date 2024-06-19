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
    <title>Tools Track - STC</title>
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
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h3>My Tools Track</h3>
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
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                  <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" id="itt-toolssearchInput" class="form-control" placeholder="Type to search...">
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-stripped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">SL NO</th>
                                                        <th class="text-center">UNIQUE ID</th>
                                                        <th class="text-center">ITEM DESCRIPTION</th>
                                                        <th class="text-center">MACHINE SR.NO</th>
                                                        <th class="text-center">MAKE</th>
                                                        <th class="text-center">TYPE</th>
                                                        <th class="text-center">PURCHASE DETAILS</th>
                                                        <th class="text-center">WARRANTY</th>
                                                        <th class="text-center">TAX INVOICE NO.</th>
                                                        <th class="text-center">TAX INVOICE DATE.</th>
                                                        <th class="text-center">REMAKRS</th>
                                                        <th class="text-center">CREATED DATE</th>
                                                        <th class="text-center">CREATED BY</th>
                                                        <th class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tools-tracker-show"></tbody>
                                            </table>
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
            
            
        
            call_tools_tracker('');
            $('body').delegate('#itt-toolssearchInput', 'focusout', function(e){
            var search=$(this).val();
            call_tools_tracker(search);
            });
            // call tools tracker
            function call_tools_tracker(search){
                $.ajax({
                    url : "nemesis/stc_product.php",
                    method : "POST",
                    data : {
                        call_tools_tracker:1,
                        search:search
                    },
                    dataType : "JSON",
                    success : function(response){
                    var data='';
                    // Check if response is valid
                    if (response.length > 0) {
                        // Loop through the JSON data
                        var slno=0;
                        for (var i = 0; i < response.length; i++) {
                            slno++;
                            data+='<tr><td>' + slno + '</td><td>' + response[i].unique_id + '</td><td>' + response[i].itemdescription + '</td><td>' + response[i].machinesrno + '</td><td>' + response[i].make + '</td><td>' + response[i].tooltype + '</td><td>' + response[i].purchase_details + '</td><td>' + response[i].warranty + '</td><td>' + response[i].taxinvono + '</td><td>' + response[i].taxinvodate + '</td><td>' + response[i].remarks + '</td><td>' + response[i].created_date + '</td><td>' + response[i].stc_user_name + '</td><td><a href="javascript:void(0)" class="btn btn-primary itt-toolstracking" id="' + response[i].id + '" data-toggle="modal" data-target=".bd-toolstrackertracker-modal-lg"><i class="fa fa-plus"></i></a><a href="javascript:void(0)" class="btn btn-primary itt-toolstrackingshow" id="' + response[i].id + '" data-toggle="modal" data-target=".bd-toolstrackertrackershow-modal-lg"><i class="fa fa-shipping-fast"></i></a></td></tr>';
                        }
                    } else {
                        data="<td>No data found.</td>";
                    }
                    $('.tools-tracker-show').html(data);
                    }
                });
            }

            // for edit tools tracker
            $('body').delegate('.itt-edit-toolsdetails', 'click', function(e){
            $('.itt-save').removeAttr("operation");
            $('.itt-save').attr("operation", "yes");
            var ittid=$(this).attr('id');
            var unique=$(this).closest('tr').find('td:eq(1)').html();
            var itemdescription=$(this).closest('tr').find('td:eq(2)').html();
            var machineslno=$(this).closest('tr').find('td:eq(3)').html();
            var make=$(this).closest('tr').find('td:eq(4)').html();
            var type=$(this).closest('tr').find('td:eq(5)').html();
            var purdetails=$(this).closest('tr').find('td:eq(6)').html();
            var warranty=$(this).closest('tr').find('td:eq(7)').html();
            var tinnumber=$(this).closest('tr').find('td:eq(8)').html();
            var tindate=$(this).closest('tr').find('td:eq(9)').html();
            var remarks=$(this).closest('tr').find('td:eq(10)').html();
            $('#itt-id').remove();
            $('.itt-unique-id').before('<input type="hidden" id="itt-id" value="' + ittid + '">');
            $('.itt-unique-id').val(unique);
            $('.itt-itemdescription').val(itemdescription);
            $('.itt-machinesrno').val(machineslno);
            $('.itt-make').val(make);
            $('.itt-type').val(type);
            $('.itt-warranty').val(warranty);
            $('.itt-purdetails').val(purdetails);
            $('.itt-tinnumber').val(tinnumber);
            $('.itt-tindate').val(tindate);
            $('.itt-remarks').val(remarks);
            });

            
            $('body').delegate('.itt-create', 'click', function(e){
            $('.itt-save').removeAttr("operation");
            $('.itt-save').attr("operation", "no");
            resetFormFields();
            });

            // save dispatch
            $('body').delegate('.itt-save', 'click', function(e){
                e.preventDefault();
                var itt_id=0;
                var unique = $('.itt-unique-id').val();
                var itemdescription = $('.itt-itemdescription').val();
                var machineslno = $('.itt-machinesrno').val();
                var make = $('.itt-make').val();
                var type = $('.itt-type').val();
                var warranty = $('.itt-warranty').val();
                var purdetails = $('.itt-purdetails').val();
                var tinnumber = $('.itt-tinnumber').val();
                var tindate = $('.itt-tindate').val();
                var remarks = $('.itt-remarks').val();
                if(unique!='' && itemdescription!=''){              
                    var operation = $(this).attr('operation'); // Get the operation type
                    if(operation=="yes"){
                    itt_id=$('#itt-id').val();
                    }
                    var data = {
                        save_tool_tracker: 1,
                        unique: unique,
                        itemdescription: itemdescription,
                        machineslno: machineslno,
                        make: make,
                        type: type,
                        warranty: warranty,
                        purdetails: purdetails,
                        tinnumber: tinnumber,
                        tindate: tindate,
                        remarks: remarks,
                        operation: operation,
                        itt_id : itt_id
                    };
                    $.ajax({
                        url : "nemesis/stc_product.php",
                        method : "POST",
                        data : data,
                        success : function(response){
                            var obj_response=response.trim();
                            if(obj_response=="yes"){
                                alert("Record updated successfully!!!");
                                if(operation=="no"){
                                resetFormFields();
                                } 
                                call_tools_tracker();    
                            }else if(obj_response=="duplicate"){
                            alert("This tool is already in records.");
                            }else if(obj_response=="reload"){
                                window.location.reload();
                            }else if(obj_response=="empty"){
                            alert("Please fill complete details.");
                            }else if(obj_response=="no"){
                                alert("Something went wrong. Record not updated");
                            }
                        }
                    });
                }else{
                alert("Please check Type of PPE.");
                }
            });

            // for edit tools tracker
            $('body').delegate('.itt-toolstracking', 'click', function(e){
            $('#ittt-id').remove();
            var ittid=$(this).attr('id');
            $('.ittt-unique-idtracking').before('<input type="hidden" id="ittt-id" value="' + ittid + '">');
            var unique=$(this).closest('tr').find('td:eq(1)').html();
            var itemdescription=$(this).closest('tr').find('td:eq(2)').html();
            $('.ittt-unique-idtracking').val(unique);
            $('.ittt-itemdescriptiontracking').val(itemdescription);
            });

            // call tools tracker
            $('body').delegate('.itt-toolstrackingshow', 'click', function(e){
            var itt_id=$(this).attr('id');
                $.ajax({
                    url : "nemesis/stc_product.php",
                    method : "POST",
                    data : {
                    call_tool_trackertrack:1,
                    itt_id:itt_id
                    },
                    dataType : "JSON",
                    success : function(response){
                    var data='';
                    // Check if response is valid
                    if (response.length > 0) {
                        // Loop through the JSON data
                        var slno=0;
                        for (var i = 0; i < response.length; i++) {
                            slno++;
                            data+='<tr><td>' + response[i].unique_id + '</td><td>' + response[i].itemdescription + '</td><td>' + response[i].issuedby + '</td><td>' + response[i].issueddate + '</td><td>' + response[i].receivedby + '</td><td>' + response[i].location + '</td><td>' + response[i].handoverto + '</td></tr>';
                        }
                    } else {
                        data="<td>No data found.</td>";
                    }
                    $('.itt-showtrackingdetails').html(data);
                    }
                });
            });

            // save dispatch
            $('body').delegate('.ittt-save', 'click', function(e){
                e.preventDefault();
                var itt_id=$('#ittt-id').val();
                var issuedby = $('.ittt-issuedby').val();
                var location = $('.ittt-location').val();
                var date = $('.ittt-date').val();
                var receivedby = issuedby;
                var handoverto = '';
                if(issuedby!='' && location!=''){      
                    var data = {
                        save_tool_trackertrack: 1,
                        issuedby: issuedby,
                        location: location,
                        date: date,
                        receivedby: receivedby,
                        handoverto: handoverto,
                        itt_id : itt_id
                    };
                    $.ajax({
                        url : "nemesis/stc_product.php",
                        method : "POST",
                        data : data,
                        success : function(response){
                            var obj_response=response.trim();
                            if(obj_response=="yes"){
                                alert("Record updated successfully!!!");
                            }else if(obj_response=="duplicate"){
                            alert("This tool is already in records.");
                            }else if(obj_response=="reload"){
                                window.location.reload();
                            }else if(obj_response=="empty"){
                            alert("Please fill complete details.");
                            }else if(obj_response=="no"){
                                alert("Something went wrong. Record not updated");
                            }
                        }
                    });
                }else{
                alert("Please check Type of PPE.");
                }
            });
        });
    </script>
</body>
</html>
<!-- Tools details tracking -->
<div class="modal fade bd-toolstrackertracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row formcontrol">
                                    <div class="col-md-4">
                                        <h5>Unique Id</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control ittt-unique-idtracking" placeholder="Enter unique id" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>Item Description</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <textarea class="form-control ittt-itemdescriptiontracking" placeholder="Enter Item Description" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Handover to</h5><br>
                                        <div class="card mb-3 widget-content">
                                          <input type="text" class="form-control ittt-issuedby" placeholder="Enter Handover to">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Location</h5><br>
                                        <div class="card mb-3 widget-content">
                                          <input type="text" class="form-control ittt-location" placeholder="Enter Location">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Date</h5><br>
                                        <div class="card mb-3 widget-content">
                                          <input type="date" class="form-control ittt-date" value="<?php echo Date('Y-m-d');?>" placeholder="Enter Date">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card mb-3 widget-content">
                                            <button class="form-control btn btn-success ittt-save">Save</button>
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

<!-- Tools details tracking show -->
<div class="modal fade bd-toolstrackertrackershow-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row form control">
                                    <div class="col-md-12">
                                        <div class="card mb-3 widget-content">
                                            <table class="table table-bordered">
                                              <thead>
                                                <tr>
                                                  <th class="text-center">UNIQUE ID</th>
                                                  <th class="text-center">ITEM DESCRIPTION</th>
                                                  <th class="text-center">ISSUED BY</th>
                                                  <th class="text-center">DATE</th>
                                                  <th class="text-center">RECEIVED BY</th>
                                                  <th class="text-center">LOCATION</th>
                                                  <th class="text-center">HANDOVER TO</th>
                                                </tr>
                                              </thead>
                                              <tbody class="itt-showtrackingdetails"></tbody>
                                            </table>
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