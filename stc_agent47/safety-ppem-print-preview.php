<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_agent_id'])){
    header('location:index.html');
}

?>
<?php 
if(isset($_GET['ppem_no'])){
    $num = $_GET['ppem_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetyppem` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetyppem_createdby`
      WHERE `stc_safetyppem_id`='".$_GET['ppem_no']."'
      ORDER BY DATE(`stc_safetyppem_date`) DESC
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

      #stc-ppem-table,#stc-ppem-table tr, #stc-ppem-table tr td, #stc-ppem-table tr th {
        border: 1px solid black;
      }
      #stc-ppem-table tr td{
        height : 10px;
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
            <img style="width: 100px;position: absolute;top: -50px;left: 600px;" src="images/globallogo.jpg">
          </a>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <p style="position: relative;top: 50px;margin-bottom: 30px;" align="center" class="header-title"><span style="color: #fe7f26;"><b>GLOBAL AC SYSTEM JSR PVT LTD.</b></span> PERSONAL PROTECTIVE EQUIPMENT MAPPING AT SITE <b>DOC No:- GASPL/</b> PPE/MAP-01</p>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <div class="row" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <table border="1" cellspacing="0" cellpadding="0" id="stc-ppem-table">
                      <tr>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:200px;">ACTIVITY</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Helmet</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Shoe</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">HV Jacket</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Safety Goggles</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">FR Jacket</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Ear Plug</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Cotton Gloves</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Nose mask</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Face Shield</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Apron</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Face Shield</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Cutting Goggles</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Leather Gloves</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Leg Guard</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Hand Sleeve</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Safety Harness</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Rubber Gloves</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Lubrication Gloves</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Ear Muff</th>
                          <th style="color:black;border:1px solid black;writing-mode: vertical-lr;transform: rotate(180deg);width:40px;">Sin Guard</th>
                      </tr>
                      <tr>
                        <td>PPE'S</td>
                        <td><img src="assets/images/safety_img/safety_helmet.png"></td>
                        <td><img src="assets/images/safety_img/safety_shoe.png"></td>
                        <td><img src="assets/images/safety_img/safety_hvjacket.png"></td>
                        <td><img src="assets/images/safety_img/safety_safetygoggles.png"></td>
                        <td><img src="assets/images/safety_img/safety_frJacket.png"></td>
                        <td><img src="assets/images/safety_img/safety_earplug.png"></td>
                        <td><img src="assets/images/safety_img/safety_cottonGloves.png"></td>
                        <td><img src="assets/images/safety_img/safety_nosemask.png"></td>
                        <td><img src="assets/images/safety_img/safety_faceshield.png"></td>
                        <td><img src="assets/images/safety_img/safety_apron.png"></td>
                        <td><img src="assets/images/safety_img/safety_faceshield.png"></td>
                        <td><img src="assets/images/safety_img/safety_cuttinggoggles.png"></td>
                        <td><img src="assets/images/safety_img/safety_leathergloves.png"></td>
                        <td><img src="assets/images/safety_img/safety_legguard.png"></td>
                        <td><img src="assets/images/safety_img/safety_handsleeve.png"></td>
                        <td><img src="assets/images/safety_img/safety_safetyharness.png"></td>
                        <td><img src="assets/images/safety_img/safety_rubbergloves.png"></td>
                        <td><img src="assets/images/safety_img/safety_liubricationgloves.png"></td>
                        <td><img src="assets/images/safety_img/safety_earmuff.png"></td>
                        <td><img src="assets/images/safety_img/safety_singuard.png"></td>
                      </tr>
                      <?php
                        $ppem_id=$get_stc_safety['stc_safetyppem_id'];
                        $checksafetyqry=mysqli_query($con, "
                            SELECT * FROM `stc_safetyppem_ppes` WHERE `stc_safetyppem_ppes_ppem_id`=$ppem_id

                        ");
                        $sl=8;
                        foreach($checksafetyqry as $checksafetyrow){
                            $sl--;
                            if($sl==0){
                                break;
                            }
                            $helmet = ($checksafetyrow['stc_safetyppem_ppes_helmet']==1) ? '✔' : 'X';
                            $shoes = ($checksafetyrow['stc_safetyppem_ppes_shoes']==1) ? '✔' : 'X';
                            $hvjacket = ($checksafetyrow['stc_safetyppem_ppes_hvjacket']==1) ? '✔' : 'X';
                            $safetygoggles = ($checksafetyrow['stc_safetyppem_ppes_safetygoggles']==1) ? '✔' : 'X';
                            $frjacket = ($checksafetyrow['stc_safetyppem_ppes_frjacket']==1) ? '✔' : 'X';
                            $earplug = ($checksafetyrow['stc_safetyppem_ppes_earplug']==1) ? '✔' : 'X';
                            $cottongloves = ($checksafetyrow['stc_safetyppem_ppes_cottongloves']==1) ? '✔' : 'X';
                            $nosemask = ($checksafetyrow['stc_safetyppem_ppes_nosemask']==1) ? '✔' : 'X';
                            $faceshieldcovid19 = ($checksafetyrow['stc_safetyppem_ppes_faceshieldcovid19']==1) ? '✔' : 'X';
                            $apron = ($checksafetyrow['stc_safetyppem_ppes_apron']==1) ? '✔' : 'X';
                            $faceshield = ($checksafetyrow['stc_safetyppem_ppes_faceshield']==1) ? '✔' : 'X';
                            $cuttinggoogles = ($checksafetyrow['stc_safetyppem_ppes_cuttinggoogles']==1) ? '✔' : 'X';
                            $leathergloves = ($checksafetyrow['stc_safetyppem_ppes_leathergloves']==1) ? '✔' : 'X';
                            $legguard = ($checksafetyrow['stc_safetyppem_ppes_legguard']==1) ? '✔' : 'X';
                            $handsleeve = ($checksafetyrow['stc_safetyppem_ppes_handsleeve']==1) ? '✔' : 'X';
                            $safetyharness = ($checksafetyrow['stc_safetyppem_ppes_safetyharness']==1) ? '✔' : 'X';
                            $rubbergloves = ($checksafetyrow['stc_safetyppem_ppes_rubbergloves']==1) ? '✔' : 'X';
                            $lubricationgloves = ($checksafetyrow['stc_safetyppem_ppes_lubricationgloves']==1) ? '✔' : 'X';
                            $earmuff = ($checksafetyrow['stc_safetyppem_ppes_earmuff']==1) ? '✔' : 'X';
                            $singuard = ($checksafetyrow['stc_safetyppem_ppes_singuard']==1) ? '✔' : 'X';
                            echo '
                                <tr>
                                    <td>'.$checksafetyrow['stc_safetyppem_ppes_siteentry'].'</td>
                                    <td class="text-center">'.$helmet.'</td>
                                    <td class="text-center">'.$shoes.'</td>
                                    <td class="text-center">'.$hvjacket.'</td>
                                    <td class="text-center">'.$safetygoggles.'</td>
                                    <td class="text-center">'.$frjacket.'</td>
                                    <td class="text-center">'.$earplug.'</td>
                                    <td class="text-center">'.$cottongloves.'</td>
                                    <td class="text-center">'.$nosemask.'</td>
                                    <td class="text-center">'.$faceshieldcovid19.'</td>
                                    <td class="text-center">'.$apron.'</td>
                                    <td class="text-center">'.$faceshield.'</td>
                                    <td class="text-center">'.$cuttinggoogles.'</td>
                                    <td class="text-center">'.$leathergloves.'</td>
                                    <td class="text-center">'.$legguard.'</td>
                                    <td class="text-center">'.$handsleeve.'</td>
                                    <td class="text-center">'.$safetyharness.'</td>
                                    <td class="text-center">'.$rubbergloves.'</td>
                                    <td class="text-center">'.$lubricationgloves.'</td>
                                    <td class="text-center">'.$earmuff.'</td>
                                    <td class="text-center">'.$singuard.'</td>
                                </tr>
                            ';
                        }
                      
                      ?>
                    </table>
                </div>
              </div>
              <div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <p>Site Name :- <?php echo $get_stc_safety['stc_safetyppem_site_name']; ?></p>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <p>Date :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetyppem_date'])); ?></p>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <p>Supervisor/Safety Supervisor :- <?php echo $get_stc_safety['stc_safetyppem_supervisor_name']; ?></p><br>
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
