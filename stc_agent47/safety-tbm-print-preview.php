<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_agent_id'])){
    header('location:index.html');
}

?>
<?php 
if(isset($_GET['tbm_no'])){
    $num = $_GET['tbm_no'];
    $str_length = 5;

    // hardcoded left padding if number < $str_length

    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `stc_safetytbm` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
      WHERE `stc_safetytbm_id`='".$_GET['tbm_no']."'
      ORDER BY DATE(`stc_safetytbm_date`) DESC
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
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="../stc_symbiote/css/templatemo-style.css">

    <link rel="stylesheet" href="../stc_symbiote/css/awsomeminho.css">

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
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                  <h4>GASPL/OHS/TBT-01</h4>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                  <h2 >टूल बॉक्स मीटिंग</h2>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <h4>दिनांक :- <span style="text-decoration: underline;"><?php echo date('d-m-Y', strtotime($get_stc_safety['stc_safetytbm_date']));?></span></h4>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <h4>समय: <span style="text-decoration: underline;"><?php echo date('h:i A', strtotime($get_stc_safety['stc_safetytbm_time']));?></span></h4> 
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <h4>स्थान: <span style="text-decoration: underline;"><?php echo $get_stc_safety['stc_safetytbm_place'];?></span></h4>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="1" cellspacing="0" cellpadding="0" id="stc-table-count">
                <thead>
                  <tr>
                      <th class="text-center">Sl no.</th>
                      <th class="text-center">Work permit no.</th>
                      <th class="text-center">Shift</th>
                      <th class="text-center">Time</th>
                      <th class="text-center">Supervisor /Engineer name</th>
                      <th class="text-center">Signature</th>
                  </tr>
                </thead>
                <tbody class="stc-safety-gentry-show-table">
                  <tr>
                    <?php 
                      $checksafetyqry=mysqli_query($con, "SELECT * FROM `stc_safetytbm_gateentry` WHERE `stc_safetytbm_gateentry_tbmid`='".$get_stc_safety['stc_safetytbm_id']."'");
                      $slgatentry=1;
                      foreach($checksafetyqry as $checksafetyrow){
                        echo '<tr><td class="text-center">'.$slgatentry.'</td><td>'.$checksafetyrow['stc_safetytbm_gateentry_workpermitno'].'</td><td>'.$checksafetyrow['stc_safetytbm_gateentry_shift'].'</td><td>'.$checksafetyrow['stc_safetytbm_gateentry_time'].'</td><td>'.$checksafetyrow['stc_safetytbm_gateentry_supeng_name'].'</td></tr>';
                        $slgatentry++;
                      }
                      $emptyslslgatentry=$slgatentry;
                      if((mysqli_num_rows($checksafetyqry)==1) || (mysqli_num_rows($checksafetyqry)==2) || (mysqli_num_rows($checksafetyqry)==3)){
                        for($i=0;$i<3;$i++){
                          echo '<tr><td class="text-center">'.$emptyslslgatentry.'</td><td></td><td></td><td></td><td></td></tr>';
                          $emptyslslgatentry++;
                        }
                      }
                    ?>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <h4>बैठक की कॅरिय सूचि : <span style="text-decoration: underline;"><?php echo $get_stc_safety['stc_safetytbm_agendaofmeet'];?></span></h4>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>विषय की सूचि : ( जिस विषय की चर्चा नहीं हुई है उसे काट ( X ) दें !</h4>
              <h4>1. पिछले मीटिंग के विषय का रिव्यु करें तथा उसकी चर्चा करें : <span><?php echo $get_stc_safety['stc_safetytbm_ptone'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>2. कर्मचारी से बीतें दिन के नियर मिस घटना या दुर्घटना के बारे में पूछें तथा नोट करें : <span><?php echo $get_stc_safety['stc_safetytbm_pttwo'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>3. कर्मचारी को ग्रीन स्ट्रिप , रेड स्ट्रिप , ऑरेंज स्ट्रिप और सेफ्टी अलर्ट सकयुरलर की जानकारी दें :
              उन्हें खतरे तथा कार्यानुसार सुरक्षित स्थिति के बारे में बतायें : <span><?php echo $get_stc_safety['stc_safetytbm_ptthree'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>4. SOP जो उस दिन के कार्य से सम्बंधित हो उसके बारे में जानकारी दे तथा नोट करें : <span><?php echo $get_stc_safety['stc_safetytbm_ptfour'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>5. कर्मचारी को उनके व्यक्तिगत जिम्मेदारियां की याद दिलाये : उचित पीपीई , हाउसकीपिंग , टूल्स एंड
              टाकल्स , बिजली उपकरण की स्थिति, ६ दिशा के खतरे, विशेष ज़रूरत जैसे वर्क परमिट , मधपान निषेद
              ,सुरक्छित वयवहार ,टीम वर्क की भावना , कोई खतरनाक वास्तु इत्यादि : <span><?php echo $get_stc_safety['stc_safetytbm_ptfive'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12" style="height: 120px;">
              <h4>6. सुरक्षा लिखित सन्देश कर्मचारी के साथ साझा करें : <span><?php echo $get_stc_safety['stc_safetytbm_ptsix'];?></span></h4><br>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <h4>7. कर्मचारी तथा सुपरवाइजर दुवारा उठाये गए विचार को नोट करे तथा जिम्मेदारियां तय करें</h4><br>
              <table border="1" cellspacing="0" cellpadding="0" >
                  <thead>
                      <tr>
                          <th class="text-center">Sl no.</th>
                          <th class="text-center">Item</th>
                          <th class="text-center">Responsibility</th>
                          <th class="text-center">Target Date</th>
                      </tr>
                  </thead>
                  <tbody class="stc-tbtm-res-show-table">
                      <?php 
                        $checksafetyqry=mysqli_query($con, "SELECT * FROM `stc_safetytbm_responsibilities` WHERE `stc_safetytbm_responsibilities_tbmid`='".$get_stc_safety['stc_safetytbm_id']."'");
                        $slgatres=1;
                        foreach($checksafetyqry as $checksafetyrow2){
                          echo '<tr><td class="text-center">'.$slgatres.'</td><td>'.$checksafetyrow2['stc_safetytbm_responsibilities_item'].'</td><td>'.$checksafetyrow2['stc_safetytbm_responsibilities_responsibilities'].'</td><td>'.$checksafetyrow2['stc_safetytbm_responsibilities_targetdate'].'</td></tr>';
                          $slgatres++;
                        }
                        $emptyslslgatres=$slgatres;
                        if((mysqli_num_rows($checksafetyqry)==1) || (mysqli_num_rows($checksafetyqry)==2) || (mysqli_num_rows($checksafetyqry)==3) || (mysqli_num_rows($checksafetyqry)==4) || (mysqli_num_rows($checksafetyqry)==5) || (mysqli_num_rows($checksafetyqry)==6) || (mysqli_num_rows($checksafetyqry)==7) || (mysqli_num_rows($checksafetyqry)==8)){
                          for($i=0;$i<6;$i++){
                            echo '<tr><td class="text-center">'.$emptyslslgatres.'</td><td></td><td></td><td></td></tr>';
                            $emptyslslgatres++;
                          }
                        }
                      ?>
                  </tbody>
              </table>
            </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <h4>सुपरवाइजर / इंजीनियर का हस्ताक्षर</h4>
            </div><br><br><br><br><br><br><br><br><br><br><br><br>
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-4">
                <h4>GASPL/OHS/TBT-01</h4>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4">
                <h2 >टूल बॉक्स मीटिंग</h2>
              </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <h2>Daily PPE and Fitness Checklist</h2>
              <h4>The required Personal Protective Equipment (PPE) was, in possession of worn, and inspected by, employees before use-(List employees attending safety meeting, daily checks PPE’s and tick mark (✓ or X)</h4>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="1" cellspacing="0" cellpadding="0">
                  <thead>
                      <tr>
                          <th class="text-center">Sl no.</th>
                          <th class="text-center">Employee’s Name</th>
                          <th class="text-center">Helmet</th>
                          <th class="text-center">Safety Goggle</th>
                          <th class="text-center">Nose Mask</th>
                          <th class="text-center">Hand Gloves</th>
                          <th class="text-center">FR-Jacket/Trouser</th>
                          <th class="text-center">Safety Shoes</th>
                          <th class="text-center">Earplug</th>
                          <th class="text-center">Leg Guard</th>
                          <th class="text-center">Physically fit for duty</th>
                      </tr>
                  </thead>
                  <tbody class="stc-tbtm-ppe-checklist-show-table">
                      <?php 
                        $checksafetyqry=mysqli_query($con, "SELECT * FROM `stc_safetytbm_dailyfitppe_checklist` WHERE `stc_safetytbm_checklist_tbmid`='".$get_stc_safety['stc_safetytbm_id']."'");
                        $slcheck=1;                          
                        foreach($checksafetyqry as $checksafetyrow3){
                          $hardhat='X';
                          $SafetyGoggle='X';
                          $NoseMask='X';
                          $HandGloves='X';
                          $FR_Jacket_Trouser='X';
                          $SafetyShoes='X';
                          $earplug='X';
                          $legguard='X';
                          $PhysicallyfitforDuty='X';
                          if($checksafetyrow3['stc_safetytbm_checklist_hardhat']==1){
                              $hardhat='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_SafetyGoggle']==1){
                              $SafetyGoggle='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_NoseMask']==1){
                              $NoseMask='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_HandGloves']==1){
                              $HandGloves='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_FR_Jacket_Trouser']==1){
                              $FR_Jacket_Trouser='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_SafetyShoes']==1){
                              $SafetyShoes='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_earplug']==1){
                              $earplug='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_legguard']==1){
                              $legguard='✔';
                          }if($checksafetyrow3['stc_safetytbm_checklist_PhysicallyfitforDuty']==1){
                              $PhysicallyfitforDuty='✔';
                          }
                          echo '<tr><td class="text-center">'.$slcheck.'</td><td>'.$checksafetyrow3['stc_safetytbm_checklist_empname'].'</td><td class="text-center">'.$hardhat.'</td><td class="text-center">'.$SafetyGoggle.'</td><td class="text-center">'.$NoseMask.'</td><td class="text-center">'.$HandGloves.'</td><td class="text-center">'.$FR_Jacket_Trouser.'</td><td class="text-center">'.$SafetyShoes.'</td><td class="text-center">'.$earplug.'</td><td class="text-center">'.$legguard.'</td><td class="text-center">'.$PhysicallyfitforDuty.'</td></tr>';
                          $slcheck++;
                        }
                        $emptyslslcheck=$slcheck;
                        if((mysqli_num_rows($checksafetyqry)==1) || (mysqli_num_rows($checksafetyqry)==2) || (mysqli_num_rows($checksafetyqry)==3) || (mysqli_num_rows($checksafetyqry)==4) || (mysqli_num_rows($checksafetyqry)==5) || (mysqli_num_rows($checksafetyqry)==6) || (mysqli_num_rows($checksafetyqry)==7) || (mysqli_num_rows($checksafetyqry)==8) || (mysqli_num_rows($checksafetyqry)==9)){
                          for($i=0;$i<10;$i++){
                            echo '<tr><td class="text-center">'.$emptyslslcheck.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                            $emptyslslcheck++;
                          }
                        }
                      ?>
                  </tbody>
              </table>
            </div>
            <div class="row" style="border:2px solid black; padding:15px;width: 99%;position: relative;left: 20px;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <h4>Any Suggestions for SIO/IO: <span><?php echo $get_stc_safety['stc_safetytbm_remarks'];?></span></h4><br><br>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <h4>Name : <span><?php echo $get_stc_safety['stc_safetytbm_entry_name'];?></span></h4>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <h4>Designation : <span><?php echo $get_stc_safety['stc_safetytbm_designation'];?></span></h4>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <h4>GP/P No. : <span><?php echo $get_stc_safety['stc_safetytbm_gatepass_no'];?></span></h4>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <h4>Signature : <span></span></h4>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <?php 
                  $checksafetyqry=mysqli_query($con, "SELECT * FROM `stc_safetytbm_img` WHERE `stc_safetytbm_img_tbmid`='".$get_stc_safety['stc_safetytbm_id']."' AND `stc_safetytbm_img_location`<>'' LIMIT 0,1");
                  foreach($checksafetyqry as $checksafetyrow4){
                    echo '<img style="position:relative; top:10px; width:10cm;height:350px" src="../stc_sub_agent47/safety_img/'.$checksafetyrow4['stc_safetytbm_img_location'].'">';
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <?php include "../stc_symbiote/footer.php";?>
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
