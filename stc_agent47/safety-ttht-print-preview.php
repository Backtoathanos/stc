
<?php 
if(isset($_GET['ttht_no'])){
    $num = $_GET['ttht_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetytoolslist` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetytoolslist_createdby`
      WHERE `stc_safetytoolslist_id`='".$_GET['ttht_no']."'
      ORDER BY DATE(`stc_safetytoolslist_date`) DESC
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

      #stc-ttht-table,#stc-ttht-table tr, #stc-ttht-table tr td, #stc-ttht-table tr th {
        border: 1px solid black;
      }
      #stc-ttht-table tr{
        height : 55px;
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
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <p style="font-size:20px;" ><b>HCK-01:</b></p>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
              <p style="font-size:20px;padding-left:60px;" ><b>HAND TOOLS LIST</b></p>
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
                  <div class="col-xl-4 col-lg-4 col-md-4">
                      <p>WORK PERMIT NO :- <?php echo $get_stc_safety['stc_safetytoolslist_wono']; ?></p>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4">
                      <p>JOB SITE NAME :- <?php echo $get_stc_safety['stc_safetytoolslist_sitename']; ?></p>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4">
                      <p>DATE :- <?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetytoolslist_date'])); ?></p>
                  </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <table border="1" cellspacing="0" cellpadding="0" id="stc-ttht-table">
                      <tr>
                          <th style="text-align:center;color:black;border:1px solid black;width:5%;">Item No.</th>
                          <th style="text-align:center;color:black;border:1px solid black;width:50%;">Tool Description</th>
                          <th style="text-align:center;color:black;border:1px solid black;">Quantity</th>
                          <th style="text-align:center;color:black;border:1px solid black;">In Use</th>
                          <th style="text-align:center;color:black;border:1px solid black;">Repair</th>
                          <th style="text-align:center;color:black;border:1px solid black;">Damaged</th>
                      </tr>
                      <?php
                        $ppem_id=$get_stc_safety['stc_safetytoolslist_id'];
                        $checksafetyqry=mysqli_query($con, "
                            SELECT * FROM `stc_safetytoolslistitem` WHERE `stc_safetytoolslistitem_tlid`=$ppem_id

                        ");
                        $rowcounter=17;
                        $sl=0;
                        foreach($checksafetyqry as $checksafetyrow){
                            $sl++;
                            $rowcounter--;
                            echo '
                                <tr>
                                  <td class="text-center">'.$sl.'</td>
                                  <td>'.$checksafetyrow['stc_safetytoolslistitem_tooldesc'].'</td>
                                  <td style="text-align:right">'.$checksafetyrow['stc_safetytoolslistitem_qty'].'</td>
                                  <td style="text-align:right">'.$checksafetyrow['stc_safetytoolslistitem_inuse'].'</td>
                                  <td style="text-align:right">'.$checksafetyrow['stc_safetytoolslistitem_repair'].'</td>
                                  <td style="text-align:right">'.$checksafetyrow['stc_safetytoolslistitem_damage'].'</td>
                                </tr>
                            ';
                        }
                        $sl=$sl;
                        for($i = 0; $i<$rowcounter; $i++){
                          $sl++;
                          echo '
                                <tr>
                                  <td class="text-center">'.$sl.'</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                            ';
                        }
                      
                      ?>
                    </table>
                </div>
              </div>
              <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-6"><br><br>
                      <p>SUPERVISOR & TECHNICIAN :- <?php echo $get_stc_safety['stc_safetytoolslist_supervisor']; ?></p><br><br>
                      <p>SIGNATURE</p>
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
          include "'.$website.'/stc_symbiote/footer.php";
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
