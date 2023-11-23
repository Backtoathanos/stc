
<?php 
if(isset($_GET['hotwork_no'])){
    $num = $_GET['hotwork_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetyhotwork` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetyhotwork_createdby`
      WHERE `stc_safetyhotwork_id`='".$_GET['hotwork_no']."'
      ORDER BY DATE(`stc_safetyhotwork_date`) DESC
    ");
    $get_stc_safety=mysqli_fetch_assoc($checksafetyqry);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Hot Work</title>
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
        }

        .footer1-box{
          border : 1px solid black;
          padding-top :15px;
        }
        .width-40{
            width :33.333%;
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
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
              <p style="font-size:20px;" ><b>AUD-08</b></p>
            </div>
            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
              <p style="font-size:20px;padding-left:0px;" ><b>INSPECTION REGISTER ELECTRIC WELDING AND FIRE PREVENTION EQUIPMENTS</b></p>
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
                  <div class="col-xl-4 col-lg-4 col-md-4 width-40">
                      <p>W.O. NO :- <?php echo $get_stc_safety['stc_safetyhotwork_wono']; ?></p>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 width-40">
                      <p>JOB SITE NAME :- <?php echo $get_stc_safety['stc_safetyhotwork_jobssitename']; ?></p>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 width-40">
                      <p>STARTING DATE :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetyhotwork_startingdate'])); ?></p>
                  </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <table cellspacing="0" cellpadding="0" id="stc-ttht-table">
                      <tr>
                          <th style="text-align:center;color:black;border:1px solid black;">SN</th>
                          <th style="text-align:center;color:black;border:1px solid black;width:80%;">EQUIPMENTS</th>
                          <th style="text-align:center;color:black;border:1px solid black;">INSPECTED BY</th>
                      </tr>
                      <tr><td>1</td><td>ELECTRIC WELDING MACHINE </td>                                                                                       <td></td></tr>
                      <tr><td>1.1</td><td>MAKE - <?php echo $get_stc_safety['stc_safetyhotwork_make'];?> SL NO. - <?php echo $get_stc_safety['stc_safetyhotwork_slno'];?></td>                                                                                                     <td></td></tr>
                      <tr><td>1.2</td><td>CONNECTIONS ARE PROPER AND EFFECTIVELY INSULATED</td>                                                                <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_capaei']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.3</td><td>ON /OFF SWITCH IN GOOD WORKING CONDITION?</td>                                                                       <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_oosigwc']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.4</td><td>VOLTMETER/AMMETER CONNECTED & WORKING PROPERLY?</td>                                                                 <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_vacawp']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.5</td><td>REGULATOR WORKING PROPERLY</td>                                                                                      <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_rwp']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.6</td><td>THREE / SINGLE PHASE TRANSFORMER</td>                                                                                <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_tspt']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.7</td><td>CABLE (FROM MACHINE TO EARTH CLAMP)</td>                                                                             <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_cfmtec']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.8</td><td>GOOD EARTHING AND GROUNDING. (DOUBLE EARTHING)</td>                                                                  <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_geagde']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.9</td><td>CABLE (FROM MACHINE TO ELECTRODE HOLDER)</td>                                                                        <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_cfmteh']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.10</td><td>ELECTRODE HOLDER</td>                                                                                                <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_eh']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.11</td><td>WELDING MACHIN COMPLETELY COVERD AND NO LOOSE CONNECTIONS.</td>                                                      <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_wmccanlc']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.12</td><td>WHEELS FREELY ROTATING</td>                                                                                          <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_wfr']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>1.13</td><td>COMPATIBLE FIRE EXTINGUSHER AVAILABLE FOR THE WELDING SET</td>                                                       <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_cfeaftws']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2</td><td>CABLE AND JOINTS</td>                                                                                                <td></td></tr>
                      <tr><td>2.1</td><td>SUPPLY CABLE’S LENGTH EXCEEDING 5M</td>                                                                              <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_scle']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.2</td><td>CONNECTION TAKEN THROUGH ELCB’s?</td>                                                                                <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_ctte']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.3</td><td>WELDING CABLE OF PROPER RATING AND IN PROPER CONDITION WITHOUT ANY DAMAGES?</td>                                     <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_wcopraipcwad']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.4</td><td>CONNECTING LUGS TIGHTENED PROPERLY?</td>                                                                             <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_cltp']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.5</td><td>WELL INSULATED AND NO EXPOSED PARTS?</td>                                                                            <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_wianep']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.6</td><td>SIZE OF CABLE USED ARE PROPORTIONAL TO VOLTAGE SUPPLY</td>                                                           <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_socuaptvs']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.7</td><td>RETURN EARTH CABLE OF SUFFICIENT LENGTH? ARE ELECTRICAL CONDUCTORS PROHIBITED FROM BEING USED TO COMPLETE WORK-</td> <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_recoslaecpfbutcw']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.8</td><td>ANY OVERLOADING, WHERE BY CABLES BECOME HOT?</td>                                                                    <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_aowbcbh']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.9</td><td>ANY CONTACT WITH OIL / SHARP EDGES OR WATER?</td>                                                                    <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_acwoseow']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>2.10</td><td>ANY CABLES PROPERLY PLACED TO PREVENT TRIPPING HAZARDS?</td>                                                         <td style="text-align: center;"><?php echo ($get_stc_safety['stc_safetyhotwork_acpptpth']==1) ? '✔' : 'X'; ?></td></tr>
                      <tr><td>3</td><td>SAND BUCKET (NEAR HOT WORK TO EXTINGUISH FIR)</td>
                    </table>
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
