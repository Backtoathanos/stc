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
    <title>My Order - STC</title>
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
                        <h3>E-Permit Enrollment</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View E-Permit Enrollment</span>
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
                                                    <h5 class="card-title">View E-Permit Enrollment</h5>
                                                    <form class="needs-validation" novalidate>
                                                        <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-7 days", strtotime($date)));
                                                        ?>
                                                        <div class="form-row">
                                                            <div class="col-md-4 mb-3" style="display: none;">
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12"> 
                                            <div class="main-card mb-3 card">
                                                <div class="card-body stc-epermitenrollment-result" style="overflow-x: auto;">
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

            var locationarr = [];
            var departmentarr = [];
            // call site name vaia supervisor
            call_location();
            function call_location(){
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_location:1},
                    dataType  : "JSON",
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-sublocation').html(response);
                        $('#stc-agent-sup-std-sublocation1').html(response);
                    }
                });
            }

            $('body').delegate('#stc-agent-sup-std-sublocation', 'change', function(e){
                e.preventDefault();
                load_dept('', '');
            });

            $('body').delegate('#stc-agent-sup-std-sublocation1', 'change', function(e){
                e.preventDefault();
                load_dept1('', '');
            });

            function load_dept(operation, value){
                var loca_id = $('#stc-agent-sup-std-sublocation').val();
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_department:1,loca_id:loca_id},
                    dataType  : "JSON",
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-dept').html(response);
                    }
                });
            }

            function load_dept1(operation, value){
                var loca_id = $('#stc-agent-sup-std-sublocation1').val();
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_department:1,loca_id:loca_id},
                    dataType  : "JSON",
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-dept1').html(response);
                    }
                });
            }
            
            $('body').delegate('.stc-sup-req-search', 'click', function(e){
                var begdate=$('#stc-sup-req-beg-date').val();
                var enddate=$('#stc-sup-req-end-date').val();
                show_epermitenroll(begdate, enddate);
            });

            var totalpentry=0;
            var totalnonenrollment=0;
            // show requistion cart items
            show_epermitenroll('', '');
            function show_epermitenroll(begdate, enddate){
                $.ajax({
                    url     : "nemesis/stc_epermitenroll.php",
                    method  : "POST",
                    data    : {show_epermitenroll:1, begdate:begdate, enddate:enddate},
                    dataType : "JSON",
                    success : function(response){
                        // console.log(response);
                        $('.stc-epermitenrollment-result').html(response['optimusprime']);
                        $('.totalpentry').html(response['totalpentry']);
                        show_totalepermitenroll(response['totalpentry']);

                        function show_totalepermitenroll(totalpentry){
                            $.ajax({
                                url     : "nemesis/stc_epermitenroll.php",
                                method  : "POST",
                                data    : {show_totalepermitenroll:1},
                                dataType : "JSON",
                                success : function(response){
                                    // console.log(response);
                                    $('.stc-totalepermitenrollment-result').html(response['optimusprime']);
                                    $('.totalpenrollment').html(response['totalpenrollment']);
                                    $('.totalenrollremarks').html(response['remarks']);
                                    var penrollment=response['totalpenrollment']==undefined ? 0 : response['totalpenrollment'];
                                    totalnonenrollment=totalpentry-penrollment;
                                    if(totalnonenrollment<totalpentry){
                                        $('.totalnonpenrollment').html(totalnonenrollment);
                                        $('.totalnonpenrollment').removeClass("text-success");
                                        $('.totalnonpenrollment').addClass("text-danger");
                                    }
                                    
                                }
                            });
                        }
                        
                    }
                });
            }

            // save permit enrollment
            $('body').delegate('.stc-permitenr-save', 'click', function(e){
                e.preventDefault();
                var location=$('.stc-permitenr-location').val();
                var selectedOption = $('.stc-permitenr-dept').find('option:selected');
                var dept = selectedOption.data('id');
                var name=$('.stc-permitenr-name').val();
                var gpno=$('.stc-permitenr-gpno').val();
                var shift=$('.stc-permitenr-shift').val();
                if(location=='Select' || dept!='NA' || dept!=0){
                    alert("Please Select all fields.");
                }else{
                    $('.stc-permitenr-save').prop('disabled', true);
                    $.ajax({
                        url : "nemesis/stc_epermitenroll.php",
                        method : "POST",
                        data : {
                            save_permitenr:1,
                            location:location,
                            dept:dept,
                            name:name,
                            gpno:gpno,
                            shift:shift
                        },
                        dataType : "JSON",
                        success : function(response){
                            if(response.trim()=="Success"){
                                alert("E-Permit Enrollment Saved Successfully.");
                                show_epermitenroll('', '');
                                $('.stc-permitenr-name').val('');
                                $('.stc-permitenr-gpno').val('');
                                $('.stc-permitenr-shift').val('NA');
                                $('.stc-permitenr-save').prop('disabled', false);
                            }else if(response.trim()=="failed"){
                                alert("E-Permit Enrollment Not Saved.");
                            }else if(response.trim()=="empty"){
                                alert("Please enter all fields.");
                            }else if(response.trim()=="login"){
                                widnow.location.reload();
                            }
                        }
                    });                    
                }
            });

            $('body').delegate('.stc-totalpermitenr-save', 'click', function(e){
                var totalpermitenr=$('.stc-totalpermitenr').val();
                var location=$('.stc-permitenr-location1').val();
                var selectedOption = $('.stc-permitenr-dept1').find('option:selected');
                var dept = selectedOption.data('id');
                var remarks=$('.stc-totalpermitenr-remarks').val();
                $.ajax({
                    url : "nemesis/stc_epermitenroll.php",
                    method : "POST",
                    data : {
                        save_totalpermitenr:1,
                        totalpermitenr:totalpermitenr,
                        location:location,
                        dept:dept,
                        remarks:remarks
                    },
                    dataType : "JSON",
                    success : function(response){
                        if(response.trim()=="Success"){
                            alert("Total E-Permit Enrollment Saved Successfully.");
                            $('.stc-totalpermitenr').val('');
                            $('.stc-sup-req-search').click();
                        }else if(response.trim()=="failed"){
                            alert("Total E-Permit Enrollment Not Saved.");
                        }else if(response.trim()=="empty"){
                            alert("Please enter all fields.");
                        }else if(response.trim()=="login"){
                            widnow.location.reload();
                        }
                    }
                });
            });
            
        });
    </script>
</body>
</html>