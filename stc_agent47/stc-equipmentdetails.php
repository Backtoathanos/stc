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
    <title>Equipment Details - STC</title>
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
                        <h3>My Equipment Details</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Equipment Details</span>
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
                                                        <th class="text-center">LOCATION</th>
                                                        <th class="text-center">DEPARTMENT</th>
                                                        <th class="text-center">AREA</th>
                                                        <th class="text-center">EQUIPMENT NAME</th>
                                                        <th class="text-center">EQUIPMENT NO</th>
                                                        <th class="text-center">MODEL NO</th>
                                                        <th class="text-center">CAPACITY</th>
                                                        <th class="text-center">CREATED DATE</th>
                                                        <th class="text-center">CREATED BY</th>
                                                        <th class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="equipement-details-show"></tbody>
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
            
            $('body').delegate('#itt-toolssearchInput', 'keyup', function(e){
                var search=$(this).val();
                if(search.length>3){
                    call_equipementdetails(search);
                }
            });

            call_equipementdetails('');
            // call tools tracker
            function call_equipementdetails(search){
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        call_equipementdetails:1,
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
                            data+='<tr><td>' + slno + '</td><td>' + response[i].stc_status_down_list_department_location + '</td><td>' + response[i].stc_status_down_list_department_dept + '</td><td>' + response[i].area + '</td><td>' + response[i].equipment_name + '</td><td>' + response[i].equipment_no + '</td><td>' + response[i].model_no + '</td><td>' + response[i].capacity + '</td><td class="text-center">' + response[i].created_date + '</td><td class="text-center">' + response[i].stc_cust_pro_supervisor_fullname + '</td><td class="text-center"><a href="javascript:void(0)" class="btn btn-primary ed-editequipment" id="' + response[i].id + '" data-toggle="modal" data-target=".bd-editequipmentdetails-modal-lg"><i class="fa fa-eye"></i></a</td></tr>';
                        }
                    } else {
                        data="<td>No data found.</td>";
                    }
                    $('.equipement-details-show').html(data);
                    }
                });
            }
            
            // to delete 
            $('body').delegate('.ed-delete', 'click', function(e){
                e.preventDefault();
                var id = $(this).attr('id');
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                        url     :    "nemesis/stc_product.php",
                        type    : 'POST',
                        data    : { delete_equipmentdetails: 1, id: id },
                        dataType    : 'JSON',
                        success : function(response) {
                            var result = $.trim(response);
                            if (result === 'success') {
                                alert('Record deleted successfully!');
                                call_equipementdetails();
                            } else {
                                alert('Failed to delete the record. Please try again.');
                            }
                        }
                    });
                }
            });

            
            var fields = ['ahu_filter_qty', 'ahu_filter_size', 'ahu_filter_type', 'ahu_make_name', 'ahu_v_belt_qty', 'ahu_v_belt_size', 'bearing_size', 'blower_bearing_size', 'blower_flywheel_size', 'compressor_qty', 'control', 'coupling_size', 'coupling_type', 'current_rating_max', 'delta_t', 'delta_p', 'each_of_capacity', 'equipment_serial_no', 'fan_blade_qty', 'fan_blade_size', 'filter_qty', 'filter_size', 'header_size', 'inlet_pressure', 'inlet_temp', 'make_name', 'max_fuse_rating', 'max_load', 'min_fuse_rating', 'min_load', 'motor_bearing_size', 'motor_capacity', 'motor_current_rating', 'motor_make_name', 'motor_pulley_size', 'motor_rpm', 'motor_voltage_rating', 'outlet_pressure', 'outlet_temp', 'power_factor', 'pump_head', 'pump_make_name', 'refrigerant_type', 'tyre_size', 'v_belt_qty', 'v_belt_size'];
            var labels = {'ahu_filter_qty': 'AHU Filter Quantity', 'ahu_filter_size': 'AHU Filter Size', 'ahu_filter_type': 'AHU Filter Type', 'ahu_make_name': 'AHU Make Name', 'ahu_v_belt_qty': 'AHU V-Belt Quantity', 'ahu_v_belt_size': 'AHU V-Belt Size', 'bearing_size': 'Bearing Size', 'blower_bearing_size': 'Blower Bearing Size', 'blower_flywheel_size': 'Blower Flywheel Size', 'compressor_qty': 'Compressor Quantity', 'control': 'Control', 'coupling_size': 'Coupling Size', 'coupling_type': 'Coupling Type', 'current_rating_max': 'Current Rating (Max)', 'delta_t': 'Delta T', 'delta_p': 'Delta P', 'each_of_capacity': 'Each of Capacity', 'equipment_serial_no': 'Equipment Serial No.', 'fan_blade_qty': 'Fan Blade Quantity', 'fan_blade_size': 'Fan Blade Size', 'filter_qty': 'Filter Quantity', 'filter_size': 'Filter Size', 'header_size': 'Header Size', 'inlet_pressure': 'Inlet Pressure', 'inlet_temp': 'Inlet Temperature', 'make_name': 'Make Name', 'max_fuse_rating': 'Max. Fuse Rating', 'max_load': 'Max. Load', 'min_fuse_rating': 'Min. Fuse Rating', 'min_load': 'Min. Load', 'motor_bearing_size': 'Motor Bearing Size', 'motor_capacity': 'Motor Capacity', 'motor_current_rating': 'Motor Current Rating', 'motor_make_name': 'Motor Make Name', 'motor_pulley_size': 'Motor Pulley Size', 'motor_rpm': 'Motor RPM', 'motor_voltage_rating': 'Motor Voltage Rating', 'outlet_pressure': 'Outlet Pressure', 'outlet_temp': 'Outlet Temperature', 'power_factor': 'Power Factor', 'pump_head': 'Pump Head', 'pump_make_name': 'Pump Make Name', 'refrigerant_type': 'Refrigerant Type', 'tyre_size': 'Tyre Size', 'v_belt_qty': 'V-Belt Quantity', 'v_belt_size': 'V-Belt Size'};
            
            // to edit modal show
            $('body').delegate('.ed-editequipment', 'click', function(e){
                var id=$(this).attr('id');
                var equipmenttype=$(this).closest('tr').find('td:eq(4)').html();
                $('.hide-col').hide();
                if(equipmenttype=="Air Handling Unit" || equipmenttype=="VAM"){$('.AirHandlingUnit').show();}
                if(equipmenttype=="Chilled Water Pump" || equipmenttype=="Chiller Unit"){$('.ChilledWaterPump').show();}
                if(equipmenttype=="Condenser Water Pump"){$('.CondenserWaterPump').show();}
                if(equipmenttype=="Cooling Tower"){$('.CoolingTower').show();}
                if(equipmenttype=="Drinking Water Unit"){$('.drinkingWaterUnit').show();}
                if(equipmenttype=="Package Air Conditioning" || equipmenttype=="VRF" || equipmenttype=="FCU" || equipmenttype=="Split AC" || equipmenttype=="Window AC" || equipmenttype=="Ductable Unit" ){$('.PackageAirConditioning').show();}
                if(equipmenttype=="Primary Drinking Water pump"){$('.PrimaryDrinkingWaterPump').show();}
                if(equipmenttype=="Secondary Drinking Water pump"){$('.SecondaryDrinkingWaterPump').show();}
                if(equipmenttype=="Unit Input"){$('.unitInputs').show();}
                $('.ed-equipment-id').remove();
                $('#capacity').before('<input type="hidden" class="ed-equipment-id" value="' + id + '">');
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        get_equipementdetails:1,
                        id:id
                    },
                    dataType    : "JSON",
                    success     : function(response){
                        $('#capacity').val(response[0].capacity);
                        $.each(fields, function(index, field) {$('#' + field).val(response[0][field]);});
                    }
                });
            });

            // euipment status update
            function equipmentupdate(label, value){
                var id=$('.ed-equipment-id').val();
                $.ajax({
                    url : "nemesis/stc_product.php",
                    method  : "POST",
                    data    : {
                        update_equipementdetails:1,
                        id:id,
                        label:label,
                        value:value
                    },
                    success : function(response){
                        call_equipementdetails('');
                    }
                });
            }

            get_fields(fields, labels);
            function get_fields(fields, labels){
                var unitInputs = ['make_name','compressor_qty','each_of_capacity','max_load','min_load','inlet_temp','outlet_temp','delta_t','current_rating_max','min_fuse_rating','max_fuse_rating','inlet_pressure','outlet_pressure','delta_p'];
                var drinkingWaterUnit = ['make_name', 'compressor_qty', 'each_of_capacity', 'max_load', 'min_load', 'equipment_serial_no', 'refrigerant_type', 'control', 'current_rating_max'];
                var CoolingTower = ['motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'header_size', 'fan_blade_size', 'fan_blade_qty', 'equipment_serial_no', 'coupling_type'];
                var CondenserWaterPump = ['pump_make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'tyre_size', 'equipment_serial_no', 'motor_make_name'];
                var ChilledWaterPump = ['pump_make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'tyre_size', 'equipment_serial_no', 'motor_make_name'];
                var SecondaryDrinkingWaterPump = ['pump_make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'tyre_size', 'equipment_serial_no', 'bearing_size', 'coupling_size', 'coupling_type', 'power_factor', 'pump_head', 'motor_make_name'];
                var PrimaryDrinkingWaterPump = ['pump_make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'tyre_size', 'equipment_serial_no', 'bearing_size', 'coupling_size', 'coupling_type', 'power_factor', 'pump_head', 'motor_make_name'];
                var AirHandlingUnit = ['ahu_make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'ahu_filter_type', 'ahu_v_belt_size', 'equipment_serial_no', 'motor_bearing_size', 'motor_pulley_size', 'blower_bearing_size', 'blower_flywheel_size', 'ahu_filter_size', 'ahu_filter_qty', 'ahu_v_belt_qty'];
                var PackageAirConditioning = ['make_name', 'motor_voltage_rating', 'motor_current_rating', 'motor_capacity', 'motor_rpm', 'filter_type', 'v_belt_size', 'equipment_serial_no', 'motor_bearing_size', 'motor_pulley_size', 'blower_bearing_size', 'blower_flywheel_size', 'filter_size', 'filter_qty', 'v_belt_qty'];


                var data = '';
                $.each(fields, function(index, field) {
                    var classname="";
                    $.each(unitInputs, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' unitInputs';}});
                    $.each(drinkingWaterUnit, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' drinkingWaterUnit';}});
                    $.each(CoolingTower, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' CoolingTower';}});
                    $.each(CondenserWaterPump, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' CondenserWaterPump';}});
                    $.each(ChilledWaterPump, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' ChilledWaterPump';}});
                    $.each(SecondaryDrinkingWaterPump, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' SecondaryDrinkingWaterPump';}});
                    $.each(PrimaryDrinkingWaterPump, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' PrimaryDrinkingWaterPump';}});
                    $.each(AirHandlingUnit, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' AirHandlingUnit';}});
                    $.each(PackageAirConditioning, function(index1, field1) {if(labels[field]==labels[field1]){classname+=' PackageAirConditioning';}});
                    data += '<div class="col-md-4 col-sm-12 hide-col' + classname + '"><h5>' + labels[field] + '</h5><br><div class="card mb-3 widget-content"><input type="text" class="form-control eq-edit-textbox" label="' + field + '" id="' + field + '" disabled></div></div>';
                });
                $('#capacity').closest('.col-md-6').after(data);
            }
        });
    </script>
</body>
</html>
<!-- Tools details tracking -->
<div class="modal fade bd-toolstrackertracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Equipment Details</h5>
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
                                    <div class="col-md-6">
                                        <h5>Location</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <select class="form-control ed-location" disabled>
                                                <option value="NA">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Department</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <select class="form-control ed-department" disabled>
                                                <option value="NA">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Area</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control ed-area" placeholder="Enter area" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Equipment Name</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <select class="form-control ed-equipment-name" disabled>
                                                <option value="NA">Select</option>
                                                <option data-slno="G008" data-unit="AHU" value="Air Handling Unit" >Air Handling Unit</option>
                                                <option data-slno="G003" data-unit="CHWP" value="Chilled Water Pump" >Chilled Water Pump</option>
                                                <option data-slno="G002" data-unit="CNWP" value="Condenser Water Pump" >Condenser Water Pump</option>
                                                <option data-slno="G007" data-unit="CT" value="Cooling Tower" >Cooling Tower</option>
                                                <option data-slno="G009" data-unit="PAC" value="Package Air Conditioning" >Package Air Conditioning</option>
                                                <option data-slno="G005" data-unit="PDWP" value="Primary Drinking Water pump" >Primary Drinking Water pump</option>
                                                <option data-slno="G006" data-unit="SDWP" value="Secondary Drinking Water pump" >Secondary Drinking Water pump</option>
                                                <option data-slno="G001" data-unit="CU" value="Chiller Unit" >Chiller Unit</option>
                                                <option data-slno="G004" data-unit="SCWP">Secondary Chilled Water Pump</option>
                                                <option data-slno="G0010" data-unit="VRF">VRF</option>
                                                <option data-slno="G0012" data-unit="FCU">FCU</option>
                                                <option data-slno="G0013" data-unit="DU">Ductable Unit</option>
                                                <option data-slno="G0014" data-unit="SAC">Split AC</option>
                                                <option data-slno="G0015" data-unit="WAC">Window AC</option>
                                                <option data-slno="G0011" data-unit="VAM">VAM</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Equipment No</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control ed-equipment-no" placeholder="Enter equipment no" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Model No.</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control ed-model-no" placeholder="Enter model number" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Capacity</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control ed-capacity" placeholder="Enter capacity" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card mb-3 widget-content">
                                            <button class="form-control btn btn-success ed-save">Save</button>
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
<div class="modal fade bd-editequipmentdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Equipment Details</h5>
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
                                    <div class="col-md-6">
                                        <h5>Capacity</h5><br>
                                        <div class="card mb-3 widget-content">
                                            <input type="text" class="form-control eq-edit-textbox" label="capacity" id="capacity" disabled>
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