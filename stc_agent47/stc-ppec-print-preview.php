<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_agent_id'])){
    header('location:index.html');
}

?>
<?php 
if(isset($_GET['ppec_no'])){
    $num = $_GET['ppec_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetyppec` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetyppec_createdby`
      WHERE `stc_safetyppec_id`='".$_GET['ppec_no']."'
      ORDER BY DATE(`stc_safetyppec_date`) DESC
    ");
    $get_stc_safety=mysqli_fetch_assoc($checksafetyqry);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Checklist of Personal Protective Equipments</title>
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
            border-bottom: 2px solid;
            margin-bottom: 55px;
          }

          .invoice .med{
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

          .subheading1{
            float: left;
            width: 10%;
          }
          .subheading2{
            float: left;
            width: 20%;
          }
          .subheading3{
            float: left;
            width: 30%;
          }
          .subheading4{
            float: left;
            width: 40%;
          }
          .subheading5{
            float: left;
            width: 50%;
          }
          .subheading6{
            float: left;
            width: 60%;
          }
          .subheading7{
            float: left;
            width: 70%;
          }
          .subheading8{
            float: left;
            width: 80%;
          }
          .subheading9{
            float: left;
            width: 90%;
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
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
          <a target="_blank" id="logo_print_pre" href="#">
            <img style="width: 150px;position: absolute;top: -60px;left: 5px;" src="images/globallogo.jpg">
          </a>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
          <h2 style="position: relative;top: 10px;margin-bottom: 55px;" align="center">GLOBAL AC SYSTEM JSR PVT LTD.</h2>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <div class="row" style="margin-top: 10px;">
            <div class="col-sm-12 col-xl-12 col-lg-12 col-md-12">
              <span class="subheading3"><h4>AUD-12</h4></span>
              <span class="subheading7"><p >CHECKLIST OF PERSONAL PROTECTIVE EQUIPMENTS (PPE)</p></span>
            </div>
            <div class="col-sm-12 col-xl-12 col-lg-12 col-md-12">
              <span class="subheading3"><p >W.O. NO: <?php echo $get_stc_safety['stc_safetyppec_wono']?></p></span>
              <span class="subheading4"><p >JOB SITE NAME: <?php echo $get_stc_safety['stc_safetyppec_sitename']?></p></span>
              <span class="subheading3"><p >CHECK :- OK/ NOT OK/ NOT APPLICABLE:</p></span>
            </div>
              <!-- <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                  <h4>AUD-12</h4>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8">
                  <p >CHECKLIST OF PERSONAL PROTECTIVE EQUIPMENTS (PPE)</p>
                </div>
              </div> -->
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="1" cellspacing="0" cellpadding="0" id="stc-table-count">
                <thead>
                  <tr>
                      <th style="color:black;border:1px solid black" class="text-center" colspan="22">PERSONAL PROTECTIVE EQUIPMENTS (PPE)</th>
                  </tr>
                  <tr>
                      <th style="color:black;border:1px solid black" class="text-center">NAME OF WORKMEN</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Helmet</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Shoe</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">HV Jacket</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Safety Goggles</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">FR Jacket</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Ear Plug</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Cotton Gloves</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Nose Mask</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Face Shield</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Apron</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Face Shield</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Cutting Goggles</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Leather Gloves</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Leg Guard</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Hand Sleeve</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Safety Harness</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Rubber Gloves</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Lubricants Gloves</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Ear Muffs</th>
                      <th class="text-center" style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);">Sin Guard</th>
                      <th style="color:black;border:1px solid black" class="text-center">Remarks</th>
                  </tr>
                </thead>
                <tbody class="stc-safety-gentry-show-table">
                  <tr>
                    <?php 
                      $checksafetyqry=mysqli_query($con, "SELECT * FROM `stc_safetyppec_ppes` WHERE `stc_safetyppec_ppes_ppec_id`='".$get_stc_safety['stc_safetyppec_id']."'");
                      $slgatentry=1;
                      foreach($checksafetyqry as $checksafetyrow){
                        $_helmet=($checksafetyrow['stc_safetyppec_ppes_helmet'] == '1') ? '✔' : 'X';
                        $_shoes=($checksafetyrow['stc_safetyppec_ppes_shoes'] == '1') ? '✔' : 'X';
                        $_hvjacket=($checksafetyrow['stc_safetyppec_ppes_hvjacket'] == '1') ? '✔' : 'X';
                        $_safetygoggles=($checksafetyrow['stc_safetyppec_ppes_safetygoggles'] == '1') ? '✔' : 'X';
                        $_frjacket=($checksafetyrow['stc_safetyppec_ppes_frjacket'] == '1') ? '✔' : 'X';
                        $_earplug=($checksafetyrow['stc_safetyppec_ppes_earplug'] == '1') ? '✔' : 'X';
                        $_cottongloves=($checksafetyrow['stc_safetyppec_ppes_cottongloves'] == '1') ? '✔' : 'X';
                        $_nosemask=($checksafetyrow['stc_safetyppec_ppes_nosemask'] == '1') ? '✔' : 'X';
                        $_faceshieldcovid19=($checksafetyrow['stc_safetyppec_ppes_faceshieldcovid19'] == '1') ? '✔' : 'X';
                        $_apron=($checksafetyrow['stc_safetyppec_ppes_apron'] == '1') ? '✔' : 'X';
                        $_faceshield=($checksafetyrow['stc_safetyppec_ppes_faceshield'] == '1') ? '✔' : 'X';
                        $_cuttinggoogles=($checksafetyrow['stc_safetyppec_ppes_cuttinggoogles'] == '1') ? '✔' : 'X';
                        $_leathergloves=($checksafetyrow['stc_safetyppec_ppes_leathergloves'] == '1') ? '✔' : 'X';
                        $_legguard=($checksafetyrow['stc_safetyppec_ppes_legguard'] == '1') ? '✔' : 'X';
                        $_handsleeve=($checksafetyrow['stc_safetyppec_ppes_handsleeve'] == '1') ? '✔' : 'X';
                        $_safetyharness=($checksafetyrow['stc_safetyppec_ppes_safetyharness'] == '1') ? '✔' : 'X';
                        $_rubbergloves=($checksafetyrow['stc_safetyppec_ppes_rubbergloves'] == '1') ? '✔' : 'X';
                        $_lubricationgloves=($checksafetyrow['stc_safetyppec_ppes_lubricationgloves'] == '1') ? '✔' : 'X';
                        $_earmuff=($checksafetyrow['stc_safetyppec_ppes_earmuff'] == '1') ? '✔' : 'X';
                        $_singuard=($checksafetyrow['stc_safetyppec_ppes_singuard'] == '1') ? '✔' : 'X';
                        echo '<tr>
                                  <td style="border:1px solid black" class="text-left">'.$checksafetyrow['stc_safetyppec_ppes_workmen'].'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_helmet.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_shoes.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_hvjacket.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_safetygoggles.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_frjacket.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_earplug.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_cottongloves.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_nosemask.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_faceshieldcovid19.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_apron.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_faceshield.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_cuttinggoogles.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_leathergloves.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_legguard.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_handsleeve.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_safetyharness.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_rubbergloves.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_lubricationgloves.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_earmuff.'</td>
                                  <td style="border:1px solid black" class="text-center">'.$_singuard.'</td>
                                  <td style="border:1px solid black" class="text-center"></td>
                              </tr>
                        ';
                        $slgatentry++;
                      }
                    ?>
                  </tr>
                  <?php
                      $emptyslslgatentry=$slgatentry;
                      if($slgatentry<9){
                        for($i=1;$i<(9-$slgatentry);$i++){
                          $emptyslslgatentry++;
                          echo '<tr><td style="border:1px solid black" class="text-left">_</td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td><td style="border:1px solid black" class="text-center"></td></tr>';
                        }
                      }
                    ?>
                </tbody>
              </table>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <p class="subheading5">Site Supervisor : <span><?php echo $get_stc_safety['stc_safetyppec_sitesupervisor'];?></span></p>
              <p class="subheading5 text-right">Safety Supervisor/ Officer : <span><?php echo $get_stc_safety['stc_safetyppec_safetysupervisor'];?></span></p>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <span>© Global Ac System JSR Pvt Ltd. ALL RIGHTS RESERVED Issue 01/Rev. 00 Jan 1, 2018_NA</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "https://stcassociate.com/stc_symbiote/footer.php";?>
    <script>
      $(document).ready(function(){
        $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data){
              var css = '@page { size: landscape; }',
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');

                style.type = 'text/css';
                style.media = 'print';

                if (style.styleSheet){
                  style.styleSheet.cssText = css;
                } else {
                  style.appendChild(document.createTextNode(css));
                }

                head.appendChild(style);
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
