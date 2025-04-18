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
    <title>Daily Attendance - STC</title>
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
                        <h3>My Daily Attendance</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Daily Attendance</span>
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
                                                    <h5 class="card-title">View Daily Attendance</h5>
                                                    <form class="needs-validation" novalidate>
                                                        <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-7 days", strtotime($date)));
                                                        ?>   
                                                        <div class="form-row">
                                                            <div class="col-md-4 mb-3">                                                                
                                                                <?php if(($_SESSION['stc_agent_sub_category']=="Site Incharge") || ($_SESSION['stc_agent_sub_category']=="Supervisor")){ ?>
                                                                <a href="javascript:void(0)" class="form-control btn bg-primary text-white mb-3" data-toggle="modal" data-target=".bd-create-summepermitenrollment-modal">Proceed to E-Permit Enrollment</a>
                                                                <?php } ?>
                                                                <a href="javascript:void(0)" class="form-control btn bg-success text-white mb-3" data-toggle="modal" data-target=".bd-create-epermitenrollment-modal">Add Daily Attendance</a>
                                                            </div>
                                                        </div>
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
                                                                <label for="validationCustom01">Shift</label>
                                                                <select class="btn btn-success form-control stc-sup-req-shift text-left "><option value="NA">Please select Shift.</option><option>A</option><option>B</option><option>C</option><option selected="">E (General)</option></select>
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
                    url     : "nemesis/stc_epermitenroll.php",
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
                    url     : "nemesis/stc_epermitenroll.php",
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
                var shift=$('.stc-sup-req-shift').val();
                show_epermitenroll(begdate, enddate, shift);
            });

            var totalpentry=0;
            var totalnonenrollment=0;
            // show requistion cart items
            show_epermitenroll('', '', '');
            function show_epermitenroll(begdate, enddate, shift){
                $.ajax({
                    url     : "nemesis/stc_epermitenroll.php",
                    method  : "POST",
                    data    : {show_epermitenroll:1, begdate:begdate, enddate:enddate, shift:shift},
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
                                    $('.stc-totalepermitenrollment-result').html(response['optimusprime']);
                                    $('.totalpenrollment').html(response['totalpenrollment']);
                                    $('.epermit').html(response['epermit']);
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

            $('body').delegate('.epremit-deletebtn', 'click', function(e){
                e.preventDefault();
                if(confirm("Are you sure?")){
                    var id=$(this).attr('id');
                    $.ajax({
                        url     : "nemesis/stc_epermitenroll.php",
                        method  : "POST",
                        data    : {stc_epermit_delete:1, id:id},
                        dataType : "JSON",
                        success : function(response){
                            alert(response);
                        }
                    });
                }
            });

            // save permit enrollment
            $('body').delegate('.stc-permitenr-save', 'click', function(e){
                e.preventDefault();
                var location=$('.stc-permitenr-location').val();
                var deptselect = $('.stc-permitenr-dept').val();
                var selectedOption = $('.stc-permitenr-dept').find('option:selected');
                var dept = selectedOption.data('id');
                var name=$('.stc-permitenr-name').val();
                var phno=$('.stc-permitenr-phno').val();
                var email=$('.stc-permitenr-email').val();
                var uid=$('.stc-permitenr-uid').val();
                var gpno=$('.stc-permitenr-gpno').val();
                var shift=$('.stc-permitenr-shift').val();
                var phonePattern = /^[0-9]{10}$/;
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var uidClean = uid.replace(/\s+/g, '');
                var validation = 1;
                $('.validation-message').remove();
                if (location == 'Select') {
                    $('.stc-permitenr-location').after('<p class="validation-message" style="color:red;">Please select Location.</p>');
                    validation = 0;
                }
                if (dept == 0 || dept == '' || dept == undefined) {
                    $('.stc-permitenr-dept').after('<p class="validation-message" style="color:red;">Please select Department.</p>');
                    validation = 0;
                }
                if (name == '') {
                    $('.stc-permitenr-name').after('<p class="validation-message" style="color:red;">Please enter valid Name.</p>');
                    validation = 0;
                }
                if (!phonePattern.test(phno)) {
                    $('.stc-permitenr-phno').after('<p class="validation-message" style="color:red;">Please enter a valid phone number.</p>');
                    validation = 0;
                }
                if (!emailPattern.test(email)) {
                    $('.stc-permitenr-email').after('<p class="validation-message" style="color:red;">Please enter a valid email address.</p>');
                    validation = 0;
                }
                if (uidClean.length !== 12 || !/^\d+$/.test(uidClean)) {
                    $('.stc-permitenr-uid').after('<p class="validation-message" style="color:red;">Aadhar No not valid. It must be exactly 12 digits.</p>');
                    validation = 0;
                }
                if (gpno == '') {
                    $('.stc-permitenr-gpno').after('<p class="validation-message" style="color:red;">Please enter gate pass.</p>');
                    validation = 0;
                }
                if (shift == 'NA') {
                    $('.stc-permitenr-shift').after('<p class="validation-message" style="color:red;">Please select shift.</p>');
                    validation = 0;
                }
                if(validation==1){
                    $('.stc-permitenr-save').prop('disabled', true);
                    $.ajax({
                        url : "nemesis/stc_epermitenroll.php",
                        method : "POST",
                        data : {
                            save_permitenr:1,
                            location:location,
                            dept:dept,
                            name:name,
                            phno:phno, 
                            email:email, 
                            uid:uid,
                            gpno:gpno,
                            shift:shift
                        },
                        dataType : "JSON",
                        success : function(response){
                            if(response.trim()=="Success"){
                                alert("Daily Attendance Saved Successfully.");
                                show_epermitenroll('', '');
                                $('.stc-permitenr-name').val('');
                                $('.stc-permitenr-phno').val('');
                                $('.stc-permitenr-email').val('');
                                $('.stc-permitenr-uid').val('');
                                $('.stc-permitenr-gpno').val('');
                                $('.stc-permitenr-shift').val('NA');
                                $('.stc-permitenr-save').prop('disabled', false);
                            }else if(response.trim()=="Duplicate"){
                                alert("Duplicate record found within the last 8 hours");
                                $('.stc-permitenr-save').prop('disabled', false);
                            }else if(response.trim()=="failed"){
                                alert("Daily Attendance Not Saved.");
                                $('.stc-permitenr-save').prop('disabled', false);
                            }else if(response.trim()=="login"){
                                widnow.location.reload();
                            }else{
                                $('.stc-permitenr-save').prop('disabled', false);
                            }
                        }
                    });   
                }else{
                    alert("Please complete all details.");
                }
            });

            $('body').delegate('.stc-epermitenocheckbox', 'click', function(e){
                if($(this).prop('checked')==true){
                    $('.stc-totalpermitenr-remarks').closest('.col-md-12').show();
                }else{
                    $('.stc-totalpermitenr-remarks').closest('.col-md-12').hide();
                }
            });

            $('body').delegate('.stc-totalpermitenr-save', 'click', function(e){
                var totalpermitenr=$('.stc-totalpermitenr').val();
                var location=$('.stc-permitenr-location1').val();
                var deptselect = $('.stc-permitenr-dept').val();
                if(deptselect!='NA' ){
                    var selectedOption = $('.stc-permitenr-dept1').find('option:selected');
                    var dept = selectedOption.data('id');
                    var remarks=$('.stc-totalpermitenr-remarks').val();
                    var epermitno=$('.stc-epermiteno').val();
                    if(location=='Select' || dept==0 || totalpermitenr=='' || totalpermitenr==0){
                        alert("Please Select all fields.");
                    }else{
                        $.ajax({
                            url : "nemesis/stc_epermitenroll.php",
                            method : "POST",
                            data : {
                                save_totalpermitenr:1,
                                totalpermitenr:totalpermitenr,
                                location:location,
                                dept:dept,
                                epermitno:epermitno,
                                remarks:remarks
                            },
                            dataType : "JSON",
                            success : function(response){
                                if(response.trim()=="Success"){
                                    alert("Total E-Permit Enrollment Saved Successfully.");
                                    $('.stc-totalpermitenr').val('');
                                    $('.stc-epermiteno').val('');
                                    $('.stc-totalpermitenr-remarks').val('');
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
                    }
                }else{
                    alert("Please Select Departments.");
                }
            });

            $('body').delegate('.save-multiple', 'click', function(e){
                e.preventDefault();
                var user = $(this).attr('user_id');
                var location = $(this).closest('tr').find('.multilocation option:selected').text();

                var dept=$(this).closest('tr').find('.multidept').val();
                var name=$(this).closest('tr').find('td:eq(2)').html();
                var gpno=$(this).closest('tr').find('.multigpno').val();
                var shift=$(this).closest('tr').find('.stc-permitenr-shift').val();
                if(shift!='NA' && dept!='NA' && gpno!=''){
                    if(confirm("Are you sure?")){
                        $.ajax({
                            url : "nemesis/stc_epermitenroll.php",
                            method : "POST",
                            data : {
                                save_permitenr_multi:1,
                                location:location,
                                dept:dept,
                                name:name,
                                gpno:gpno,
                                user:user,
                                shift:shift
                            },
                            dataType : "JSON",
                            success : function(response){
                                if(response.trim()=="Success"){
                                    alert("Daily Attendance Saved Successfully.");
                                    $(this).closest('tr').remove();
                                }else if(response.trim()=="Duplicate"){
                                    alert("Duplicate record found within the last 8 hours");
                                    $('.save-multiple').prop('disabled', false);
                                }else if(response.trim()=="failed"){
                                    alert("Daily Attendance Not Saved.");
                                    $('.save-multiple').prop('disabled', false);
                                }else if(response.trim()=="empty"){
                                    alert("Please enter all fields.");
                                    $('.save-multiple').prop('disabled', false);
                                }else if(response.trim()=="login"){
                                    widnow.location.reload();
                                }
                            }
                        }); 
                    }
                }else{
                    alert("Please complete all fields.");
                }
            });

            $('body').delegate('.save-multiple2', 'click', function(e){
                e.preventDefault();
                var location=$('.stc-permitenr-location').val();
                var deptselect = $('.stc-permitenr-dept').val();
                var selectedOption = $('.stc-permitenr-dept').find('option:selected');
                var dept = selectedOption.data('id');
                var user = $(this).attr('user_id');
                var name=$('.stc-permitenr-name').val();
                var gpno=$('.stc-permitenr-gpno').val();
                var shift=$('.stc-permitenr-shift').val();
                if(shift!='NA' && dept!='NA' && gpno!=''){
                    if(confirm("Are you sure?")){
                        $.ajax({
                            url : "nemesis/stc_epermitenroll.php",
                            method : "POST",
                            data : {
                                save_permitenr_multi:1,
                                location:location,
                                dept:dept,
                                name:name,
                                gpno:gpno,
                                user:user,
                                shift:shift
                            },
                            dataType : "JSON",
                            success : function(response){
                                if(response.trim()=="Success"){
                                    alert("Daily Attendance Saved Successfully.");
                                    $('.stc-permitenr-name').val('');
                                    $('.stc-permitenr-gpno').val('');
                                    $('.stc-permitenr-shift').val('');
                                }else if(response.trim()=="Duplicate"){
                                    alert("Duplicate record found within the last 8 hours");
                                    $('.save-multiple2').prop('disabled', false);
                                }else if(response.trim()=="failed"){
                                    alert("Daily Attendance Not Saved.");
                                    $('.save-multiple2').prop('disabled', false);
                                }else if(response.trim()=="empty"){
                                    alert("Please enter all fields.");
                                    $('.save-multiple2').prop('disabled', false);
                                }else if(response.trim()=="login"){
                                    widnow.location.reload();
                                }
                            }
                        }); 
                    }
                }else{
                    alert("Please complete all fields.");
                }
            });

            $('body').delegate('.stc-epermitenrollment-result-table th', 'click', function(e){
                var table = $(this).parents('table').eq(0);
                var rows = table.find('tbody > tr:eq(0)').toArray().sort(comparer($(this).index()));
                this.asc = !this.asc;
                if (!this.asc){ rows = rows.reverse(); }
                for (var i = 0; i < rows.length; i++){ table.append(rows[i]); }
            });
            
            // Function to compare values for sorting
            function comparer(index) {
                return function(a, b) {
                    var valA = getCellValue(a, index);
                    var valB = getCellValue(b, index);
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
                };
            }
            
            // Function to get cell value for comparison
            function getCellValue(row, index){ 
                return $(row).children('td').eq(index).text();
            }

            $('body').delegate('.search-emp', 'keyup', function(e){
                var search = $(this).val().toLowerCase();
        
                $('.search-emp-table tbody tr').each(function() {
                    var row = $(this);
                    var rowText = row.text().toLowerCase();
                    
                    if (rowText.indexOf(search) !== -1) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });

            $('body').delegate('.multidept', 'change', function(e){
                var dept_id=$(this).val();
                if(dept_id!='NA'){
                    $(this).closest('tr').find('.multilocation').val(dept_id).change();
                }
            });
            
        });
        
    </script>
</body>
</html>

<div class="modal fade bd-create-epermitenrollment-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Daily Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){?>
                <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                    <li class="nav-item">
                        <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#existingemp">
                            <span>Existing</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link " id="tab-2" data-toggle="tab" href="#newemp">
                            <span>New</span>
                        </a>
                    </li>
                </ul>
                <?php }?>
                <div class="tab-content">
                    <?php if($_SESSION['stc_agent_sub_category']!='Service Group'){ ?>
                    <div class="tab-pane tabs-animation" id="newemp" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Location : </h5><br>
                                        <select class="btn btn-success form-control text-left stc-permitenr-location" id="stc-agent-sup-std-sublocation">
                                        </select> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Department : </h5><br>
                                        <select class="btn btn-success form-control stc-agent-sup-std-sub-location text-left stc-permitenr-dept" id="stc-agent-sup-std-dept"><option>Please select location first!!!</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php if(($_SESSION['stc_agent_sub_category']=='Supervisor') || ($_SESSION['stc_agent_sub_category']=='Site Incharge')){?>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Name : </h5><br>
                                        <input type="text" class="form-control stc-name stc-permitenr-name" placeholder="Enter Name"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Phone Number : </h5><br>
                                        <input type="text" class="form-control stc-permitenr-phno" placeholder="Enter Phone Number"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Email : </h5><br>
                                        <input type="text" class="form-control stc-permitenr-email" placeholder="Enter Email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Aadhar Card : </h5><br>
                                        <input type="text" class="form-control stc-permitenr-uid" placeholder="Enter Aadhar Card"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>G.P No : </h5><br>
                                        <input type="text" class="form-control stc-permitenr-gpno" placeholder="Enter G.P No"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-6"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5>Shift : </h5><br>
                                        <select class="btn btn-success form-control stc-permitenr-shift text-left " id="stc-shift">
                                            <option value="NA">Please select Shift.</option>
                                            <option>A</option>
                                            <option>B</option>
                                            <option>C</option>
                                            <option>E (General)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-12"> 
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" class="btn btn-success stc-permitenr-save form-control">Save</a>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
                    <div class="tab-pane tabs-animation fade active" id="existingemp" role="tabpanel">
                        <?php if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Service Group' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){?>
                        <div class="row">
                            <div class="col-md-12 col-xl-12"> 
                                <input type="text" class="form-control search-emp" placeholder="Search here..">
                            </div>
                            <div class="col-md-12 col-xl-12"> 
                                <table class="table table-bordered table-responsive search-emp-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">UId</th>
                                            <th class="text-center">GatePassNo</th>
                                            <th class="text-center">Shift</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            include_once("../MCU/db.php");
                                            $projectsQuery = "SELECT stc_cust_pro_attend_supervise_pro_id FROM stc_cust_pro_attend_supervise WHERE stc_cust_pro_attend_supervise_super_id = '".$_SESSION['stc_agent_sub_id']."'";
                                            $projectsResult = mysqli_query($con, $projectsQuery);
                                            $projects = [];
                                            while ($row = mysqli_fetch_assoc($projectsResult)) {
                                                $projects[] = $row['stc_cust_pro_attend_supervise_pro_id'];
                                            }
                                            $commsep_projects=implode(',', $projects);
                                            $UsersQuery = "SELECT DISTINCT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_uid`, `stc_cust_pro_supervisor_contact`, `stc_cust_pro_supervisor_category` FROM stc_cust_pro_attend_supervise INNER JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_cust_pro_attend_supervise_super_id` WHERE stc_cust_pro_attend_supervise_pro_id IN (".$commsep_projects.")";
                                            $UsersResult = mysqli_query($con, $UsersQuery);
                                            if(mysqli_num_rows($UsersResult)>0){
                                                $usercounter=0;
                                                foreach($UsersResult as $row){
                                                    $getgpnoquery=mysqli_query($con, "SELECT `gpno`, `shift` FROM `stc_epermit_enrollment` WHERE `emp_id`='".$row['stc_cust_pro_supervisor_id']."' ORDER BY `id` DESC LIMIT 0,1");
                                                    $gpno='';
                                                    $shift='';
                                                    if(mysqli_num_rows($getgpnoquery)>0){
                                                        $result=mysqli_fetch_assoc($getgpnoquery);
                                                        $gpno=$result['gpno'];
                                                        $shift=$result['shift'];
                                                    }
                                                    $getquery=mysqli_query($con, "SELECT DISTINCT `stc_status_down_list_department_loc_id`, `stc_status_down_list_department_id`, `stc_status_down_list_department_location`, `stc_status_down_list_department_dept`, `stc_cust_pro_attend_supervise_status` FROM `stc_cust_pro_attend_supervise` LEFT JOIN `stc_status_down_list_department` ON `stc_cust_pro_attend_supervise_pro_id`=`stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='".$_SESSION['stc_agent_sub_id']."' AND `stc_status_down_list_department_dept`<>'' ORDER BY `stc_status_down_list_department_dept` ASC");
                                                
                                                    $location='<select class="form-control btn btn-success multilocation text-left" disabled>';
                                                    $department='<select class="form-control btn btn-success multidept text-left">';
                                                    $counter=0;
                                                    $locations='';
                                                    $departments='';
                                                    $loopexit=0;
                                                    foreach($getquery as $getrow){
                                                        $counter++;  
                                                        $locations.='<option value="'.$getrow['stc_status_down_list_department_id'].'">'.$getrow['stc_status_down_list_department_location'].'</option>';
                                                        $departments.='<option value="'.$getrow['stc_status_down_list_department_id'].'">'.$getrow['stc_status_down_list_department_dept'].'</option>';
                                                    }
                                                    if($counter>1){
                                                        $location.='<option value="NA" selected>Select</option>';
                                                        $department.='<option value="NA" selected>Select</option>';
                                                    }else if($counter==0){
                                                        $location.='<option value="NA">Location not found.</option>';
                                                        $department.='<option value="NA">Department not found.</option>';
                                                    }
                                                    $location.=$locations;
                                                    $department.=$departments;
                                                    $location.='</select>';
                                                    $department.='</select>';
                                                    $usercounter++;
                                                    if($row['stc_cust_pro_supervisor_category']!="Service Group"){
                                                        $shift_select="<option value='NA'>Please select Shift.</option>";
                                                        $shift_array=array("A", "B", "C", "E (General)");
                                                        foreach($shift_array as $shift_row){
                                                            if($shift==$shift_row){
                                                                $shift_select.="<option selected>".$shift_row."</option>";
                                                            }else{
                                                                $shift_select.="<option>".$shift_row."</option>";
                                                            }
                                                        }
                                                        echo "<tr><td>".$location."</td><td>".$department."</td><td>".$row['stc_cust_pro_supervisor_fullname']."</td><td>".$row['stc_cust_pro_supervisor_contact']."</td><td>".$row['stc_cust_pro_supervisor_uid']."</td><td><input type='text' value='".$gpno."' class='form-control multigpno' placeholder='Enter G.P No' ></td><td><select class='btn btn-success form-control stc-permitenr-shift text-left ' id='stc-shift'>".$shift_select."</select></td><td><a href='javascript:void(0)' class='btn btn-primary save-multiple' user_id='".$row['stc_cust_pro_supervisor_id']."'>Add</a></td></tr>";
                                                    }
                                                }
                                                echo '<tr><td>Showing '.$usercounter.' employees</td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-create-summepermitenrollment-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Proceed to E-Permit Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-xl-6"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Location : </h5><br>
                                <select class="btn btn-success form-control text-left stc-permitenr-location1" id="stc-agent-sup-std-sublocation1">
                                    <option>TATA Steel - Jamshedpur</option>
                                    <option>TATA Steel - KPO</option>
                                    <option>MTMH</option>
                                    <option>CRM BARA</option>
                                    <option>MANIPAL</option>
                                    <option>P&M MALL</option>
                                    <option>TATA CUMMINS</option>
                                    <option>XLRI</option>
                                    <option>RAIPUR AIIMS</option>
                                    <option>NML</option>
                                    <option>RSP</option>
                                    <option>IGH HOSPITAL</option>
                                    <option>NEELACHAL ISPAT</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Department : </h5><br>
                                <select class="btn btn-success form-control stc-agent-sup-std-sub-location text-left stc-permitenr-dept1" id="stc-agent-sup-std-dept1"><option>Please select location first!!!</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>E-Permit No : </h5><br>
                                <input type="text" class="form-control stc-epermiteno" placeholder="Enter E-Permit No"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Total E-Permit Enrollment : </h5><br>
                                <input type="number" class="form-control stc-totalpermitenr" placeholder="Enter Total E-Permit Enrollment"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>For Non-Enrollement : </h5><br>
                                <input type="checkbox" class="form-control stc-epermitenocheckbox"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12" style="display: none;"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Remarks : (For Non-Enrollement)</h5><br>
                                <textarea class="form-control stc-totalpermitenr-remarks" placeholder="Enter remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <a href="javascript:void(0)" class="btn btn-success stc-totalpermitenr-save">Save</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <table class='table table-bordered table-hover'>
                                    <thead>
                                        <th>Label</th>
                                        <th>Quantity</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Total Plant Entry</td>
                                            <td><span class="text-success totalpentry"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Total E-Permit Enrollment</td>
                                            <td><span class="text-success totalpenrollment"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Total Non-Enrollement</td>
                                            <td><span class="text-success totalnonpenrollment"></span></td>
                                        </tr>
                                        <tr>
                                            <td>E-Permit No</td>
                                            <td><span class="text-success epermit"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Remarks</td>
                                            <td><span class="text-danger totalenrollremarks"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body stc-totalepermitenrollment-result">
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