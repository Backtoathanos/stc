
<?php 
if(isset($_GET['vhl_no'])){
    $num = $_GET['vhl_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetyvehicle` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetyvehicle_createdby`
      WHERE `stc_safetyvehicle_id`='".$_GET['vhl_no']."'
      ORDER BY DATE(`stc_safetyvehicle_date`) DESC
    ");
    $get_stc_safety=mysqli_fetch_assoc($checksafetyqry);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tool Box Meeting</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="https://stcassociate.com/stc_symbiote/css/awsomeminho.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <style>
      .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }

      .invoice table td,.invoice table th {
          padding: 5px;
          background: ##7d6161;
          border-bottom: 1px solid #fff;
      }

      .invoice table th {
          white-space: nowrap;
          font-weight: 400;
          font-size: 16px;
      }

      .invoice table td h3 {
          margin: 0;
          font-weight: 400;
          color: #7d6161;
          font-size: 1.2em;
      }

      .invoice table .qty,.invoice table .total,.invoice table .unit {
          text-align: right;
          font-size: 1.2em;
      }

      .invoice table .no {
          color: #fff;
          font-size: 1.6em;
          background: #3989c6;
      }

      .invoice table .unit {
          background: #ddd;
      }

      .invoice table .total {
          /*background: #3989c6;*/
          /*color: #fff;*/
      }

      .invoice table tbody tr:last-child td {
          border: none;
      }

      .invoice table tfoot td {
          background: 0 0;
          border-bottom: none;
          white-space: nowrap;
          text-align: right;
          padding: 10px 20px;
          font-size: 1.2em;
          border-top: 1px solid #aaa;
      }

      .invoice table tfoot tr:first-child td {
          border-top: none;
      }

      .invoice table tfoot tr:last-child td {
          color: #3989c6;
          font-size: 1.4em;
          border-top: 1px solid #3989c6;
      }

      .invoice table tfoot tr td:first-child {
          border: none;
      }
      
      .tfootar{
        font-size: 20px;
        font-weight: bold;
      }

      .invoice{
        background-color: #FFF;
      }

      #stc-ttht-table,#stc-ttht-table tr td {
        border: 1px solid black;
      }
      #stc-ttht-table tr{
        height : 20px;
      }

      @media print {
        .invoice {
            margin-top : -5px;
            font-size: 15px!important;
            overflow: hidden!important;
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always;
        }

        /*.invoice>div:last-child {
            page-break-before: always;
        }*/

        .hidden-print footer { 
           visibility: hidden; 
        }

        .hidden-print { 
           visibility: hidden; 
        }

        .tm-footer{
          visibility: hidden; 
        }

        .block-foot{
          position: relative;
          bottom: 0px;
        }
        /*tfoot {
          page-break-inside: avoid;
        }*/
        .invoice .header{
          position: fixed;
          width:100%;
          margin-bottom: 55px;
        }

        .invoice .med{
          width :100%;
          position: absolute;
          top:150px; 
          bottom: 0px;
        }

        .block-foot{
          position: fixed;
          margin-bottom: -8px;
        }

        .table{
          height: 100%;
        }

        #logo_print_pre{
          margin-top: 6px;
          float: right;
          margin-right: 0px;
        }

        .header-title{
          font-size : 16px;
        }

        #stc-ttht-table{
          height: 100%;
        }
        .doi, .dn{
          width : 50%;
        }

        .head1-box{
          border-bottom : 1px solid black;
        }
        
        .head2-box{
          margin-top :15px;
          border : 1px solid black;
        }

        .footer1-box{
          border : 1px solid black;
          padding-top :15px;
        }

        
      }
    </style>
  </head>

  <body>
    <div class="text-right hidden-print">
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="row header">
        <!-- Create order -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <a target="_blank" id="logo_print_pre" href="#">
            <img style="width: 100px;position: absolute;top: -20px;left: 0px;" src="images/globallogo.jpg">
          </a>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <p style="position: relative;top: 20px;margin-bottom: 30px;font-size:30px;" align="center" class="header-title"><span style="color: #fe7f26;"><b>GLOBAL AC SYSTEM JSR PVT LTD.</b></span></p>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 head1-box">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <p style="font-size:20px;" ><b>DOC NO:- VHL-01</b></p>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
              <p style="font-size:20px;padding-left:60px;" ><b>VEHICLE INSPECTION</b></p>
            </div>
          </div>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <div class="row" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="row head2-box">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>Vehicle description :- <?php echo $get_stc_safety['stc_safetyvehicle_desc']; ?></p>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>Vehicle Registration number :- <?php echo $get_stc_safety['stc_safetyvehicle_reg_no']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 doi">
                      <p>Date of inspection :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetyvehicle_dateofinspection'])); ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn">
                      <p>Driver's name :- <?php echo $get_stc_safety['stc_safetyvehicle_driversname']; ?></p>
                  </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <table cellspacing="0" cellpadding="0" id="stc-ttht-table">
                      <tr>
                          <th style="text-align:center;color:black;border:1px solid black;width:80%;">What should I check before operating the vehicle</th>
                          <th style="text-align:center;color:black;border:1px solid black;">Yes</th>
                          <th style="text-align:center;color:black;border:1px solid black;">No</th>
                      </tr>
                      <?php
                        $oprvhl = array(
                          'stc_safetyvehicle_oil_level' => 'Oil level',
                          'stc_safetyvehicle_brakefluidlevel' => 'Brake fluid level',
                          'stc_safetyvehicle_waterlevel' => 'Water level',
                          'stc_safetyvehicle_windscreen' => 'Windscreen washer level',
                          'stc_safetyvehicle_adjustseat' => 'Adjust seat and controls',
                          'stc_safetyvehicle_seatbelts' => 'Seat belts – check for operation (all)',
                          'stc_safetyvehicle_parking_brake' => 'Parking brake – hold against slight acceleration',
                          'stc_safetyvehicle_footbrake' => 'Foot brake – holds, stops vehicle smoothly',
                          'stc_safetyvehicle_passengerbrake' => 'Passenger brake for Driving lessons',
                          'stc_safetyvehicle_clutchgearshift' => 'Clutch and gearshift – shifts smoothly without jumping or jerking',
                          'stc_safetyvehicle_mirrorsclean' => 'Mirrors clean and adjusted',
                          'stc_safetyvehicle_doorlock' => 'Doors and door locks operate correctly',
                          'stc_safetyvehicle_steering' => 'Steering – moves smoothly',
                          'stc_safetyvehicle_lightsclearance' => 'Lights – clearance, headlights, tail, license plate, brake, indicator turn signals & alarm.',
                          'stc_safetyvehicle_dashcontrolpanel' => 'Dash control panel – all lights and gauges are operational',
                          'stc_safetyvehicle_horn' => 'Horn',
                          'stc_safetyvehicle_alarm' => 'Vehicle reverse alarm',
                          'stc_safetyvehicle_hydraulicsystem' => 'Hydraulic systems – no evidence of leaks and systems operate smoothly',
                          'stc_safetyvehicle_sparetyre' => 'Check spare tyre',
                          'stc_safetyvehicle_towbar' => 'Check tow bar (where fitted)',
                          'stc_safetyvehicle_equipment' => 'Emergency equipment',
                          'stc_safetyvehicle_firstaidkit' => 'First aid kit'
                        );
                        foreach($oprvhl as $key=>$oprvhlrow){
                          $flag = '<td style="text-align:center;">✔</td><td style="text-align:center;"></td>';
                          $flag = ($get_stc_safety[$key]) ? $flag : '<td style="text-align:center;"></td><td style="text-align:center;">X</td>';
                          echo '
                            <tr>
                              <td>'.$oprvhlrow.'</td>
                              '.$flag.'
                            </tr>
                          ';
                        }
                      
                      ?>
                    </table>
                </div>
              </div>
              <div class="row footer1-box">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>Name of Person undertaking vehicle inspection :- <?php echo $get_stc_safety['stc_safetyvehicle_personundertaking']; ?></p>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>Signature :- <?php echo $get_stc_safety['stc_safetyvehicle_signature']; ?></p>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>Vehicle faults to be reported immediately :- <?php echo $get_stc_safety['stc_safetyvehicle_faultsreported']; ?></p>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <b>REMBERMBER – What should I do before vehicle operation?</b>
                  </div>
              </div>
              <div class="row footer1-box">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <ul>
                      <li> <b>Initially read, understand and follow the manufacturer’s operating manual. This will provide a wide range of information relative to the vehicle.</b></li>
                      <li> <b>Know how to operate the vehicle and use and related equipment or attachments safely </b></li>
                      <li> <b>Be familiar with the location and function of all controls </b></li>
                      <li> <b>Develop a routine method of inspecting the vehicle </b></li>
                      <li> <b>Before moving off, adjust the seat and mirrors and fasten seat belt/s </b></li>
                    </ul>
                  </div>
              </div>
              <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>© Global Ac System JSR Pvt Ltd. ALL RIGHTS RESERVED Issue 01/Rev. 00 Jan 1, 2018_NA</p>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <?php 
        $website=$_SERVER['SERVER_NAME'];
			  $website = $website=="localhost" ? '../' : 'https://stcassociate.com';
          include $website."/stc_symbiote/footer.php";
        ?>
    <script>
      $(document).ready(function(){
        $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data){
                window.print();
                return true;
            }            
        });
      });
    </script>
  </body>
</html>
<?php
}
?>
