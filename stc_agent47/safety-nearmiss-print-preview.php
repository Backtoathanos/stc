
<?php 
if(isset($_GET['nearmiss_no'])){
    $num = $_GET['nearmiss_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetynearmiss` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetynearmiss_createdby`
      WHERE `stc_safetynearmiss_id`='".$_GET['nearmiss_no']."'
      ORDER BY DATE(`stc_safetynearmiss_date`) DESC
    ");
    $get_stc_safety=mysqli_fetch_assoc($checksafetyqry);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>NEAR MISS REPORT</title>
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

      .med p, .med li {
        font-size : 20px;
      }

      .footer1-box{
        border : 1px solid black;
      }
      .with-50{
        width :50%;
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
          top:160px; 
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

        .header-box1{
            border-top : 1px solid black; padding-top: 10px; padding-bottom: 40px;margin-bottom: 40px;
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
          <div class="row header-box1">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <p style="font-size:20px;" ><b>INR-02</b></p>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
              <p style="font-size:20px;padding-left:60px;" ><b>NEAR MISS REPORT</b></p>
            </div>
          </div>
        </div>
      </div>

      <!-- main area -->
      <div class="row med">
        <div class="container-fluid">
          <div class="row" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>(Definition of Near Miss: Near miss is an accident or a situation with a clear potential for an undesirable outcome, even though no actual negative consequence happened. In case of a near miss, time & distance play a critical role)</p>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12">
                      <p>1. Organization unit of occurrence :- <?php echo $get_stc_safety['stc_safetynearmiss_orgunitoccur']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 doi">
                      <p>2. Incident date :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetynearmiss_incidenet_date'])); ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 doi">
                      <p>3. Incident time :- <?php echo date('H:i a', strtotime($get_stc_safety['stc_safetynearmiss_time'])); ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn">
                      <p>4. Incident Location :- <?php echo $get_stc_safety['stc_safetynearmiss_location']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn">
                      <p>5. Place of incident :- <?php echo $get_stc_safety['stc_safetynearmiss_placeofincident']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn">
                      <p>6. Possible consequence :- <?php echo $get_stc_safety['stc_safetynearmiss_possibleconsq']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn" style="height :100px;">
                      <p>7. Brief Incident description :- <?php echo $get_stc_safety['stc_safetynearmiss_incidenetdesc']; ?></p>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 dn" style="height :100px;">
                      <p>8. Primary cause :- <?php echo $get_stc_safety['stc_safetynearmiss_primarycause']; ?></p>
                  </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                <ol type="A">
                    <li>Name of the employee/ contact partner: <?php echo $get_stc_safety['stc_safetynearmiss_empname']; ?></li>
                    <li>P No./ G P No. <?php echo $get_stc_safety['stc_safetynearmiss_gpno']; ?></li>
                    <li>Department / Vendor: <?php echo $get_stc_safety['stc_safetynearmiss_vendor']; ?></li>
                </ol>
                </div>
              </div>
              <div class="row footer1-box">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <p style="text-align : center;">To be filled by Line Manager/ Safety Professional</p>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12" style="height :200px;">
                    <p>Action Taken :- <?php echo $get_stc_safety['stc_safetynearmiss_actiontaken']; ?></p>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 with-50">
                    <p>Date :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetynearmiss_currdate'])); ?></p>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 with-50">
                    <p>Name & Signature :- <?php echo $get_stc_safety['stc_safetynearmiss_namesign']; ?></p>
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
