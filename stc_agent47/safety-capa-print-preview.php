<?php 
if(isset($_GET['capa_no'])){
  
    include "../MCU/db.php";
    $checksafetyqry=mysqli_query($con, "
      SELECT * FROM `capa` 
      LEFT JOIN `stc_cust_pro_supervisor`
      ON `stc_cust_pro_supervisor_id`=`created_by`
      WHERE `id`='".$_GET['capa_no']."'
    ");
    $get_stc_safety=mysqli_fetch_assoc($checksafetyqry);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collective And Preventive Action Report </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body{
        font-size: 12px;
      }
      table, th, td {
          border: 1px solid black;
      }
      th, td {
          padding: 5px;
          text-align: left;
      }
      table {
          border-collapse: collapse;
          width: 100%;
      }
      @media print {
        .container-fluid {
          position:relative;
          top:-30px;
        }
      }
    </style>
</head>
<body>

<div class="container-fluid my-4">
  <div class="row">
    <div class="col-sm-12">
      <div class="header">
        <div class="row" style="border-bottom:2px solid black;">
          <div class="col-3">
            <a target="_blank" id="logo_print_pre" href="#">
              <img style="width: 100px;position: relative;top: -10px;" src="images/globallogo.jpg">
            </a>
          </div>
          <div class="col-6">
            <h3 style="position: relative;top: 50px;margin-bottom: 30px;" align="center" class="header-title"><span style="color: #fe7f26;"><b>GLOBAL AC SYSTEM JSR PVT LTD.</b></h3>
          </div>
        </div>
        <div class="row">
          <div class="col-3"><h3>SCP-01</h3></div>
          <div class="col-6"><h3 style="font-size:25px;">CORRECTIVE AND PREVENTIVE ACTION REPORT</h3></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-10 col-md-10">
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th colspan="2" class="text-center">OFFICE / STORE / OTHERS <br> JOB SITE NAME</th>
                  <th class="text-center">PLACE</th>
                  <th class="text-center">BRANCH</th>
                  <th colspan="2" class="text-center">DATE</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td colspan="2" class="text-center"><?php echo $get_stc_safety['sitename']; ?></td>
                  <td class="text-center"><?php echo $get_stc_safety['place']; ?></td>
                  <td class="text-center"><?php echo $get_stc_safety['branch']; ?></td>
                  <td colspan="2" class="text-center"><?php echo date('d-m-Y', strtotime($get_stc_safety['capa_date'])); ?></td>
              </tr>
              <tr>
                  <th colspan="2" class="text-center">PERSON OBSERVED NON-CONFORMANCE</th>
                  <th colspan="4" class="text-center">NON-CONFORMANCE DETAILS</th>
              </tr>
              <tr>
                  <th class="text-center">NAME OF PERSON OBSERVED</th>
                  <th class="text-center">DESIGNATION</th>
                  <th class="text-center">N.C. LOCATION</th>
                  <th class="text-center">OBSERVATION DATE</th>
                  <th class="text-center">TGT DATE COMPLIANCE</th>
                  <th class="text-center">Severity</th>
              </tr>
              <tr>
                  <td><?php echo $get_stc_safety['person_observed']; ?></td>
                  <td><?php echo $get_stc_safety['designation_observed']; ?></td>
                  <td><?php echo $get_stc_safety['nclocation']; ?></td>
                  <td><?php echo date('d-m-Y', strtotime($get_stc_safety['observe_date'])); ?></td>
                  <td><?php echo date('d-m-Y', strtotime($get_stc_safety['tgtdate'])); ?></td>
                  <td><?php echo $get_stc_safety['severity']; ?></td>
              </tr>
              <tr>
                  <td colspan="4" rowspan="6">
                    <span style="font-weight:bold;">DESCRIPTION OF NON-CONFORMANCE OBSERVED:</span><br>
                    <?php echo $get_stc_safety['nonconformanceobserved']; ?>
                  </td>
              </tr>
              <tr>
              <td colspan="2" class="text-center">PERSON RESPONSIBLE</td>
              </tr>
              <tr>
                <td>POSITION</td>
                <td>NAME</td>
              </tr>
              <tr>
                  <td><?php echo $get_stc_safety['res_personname']; ?></td>
                  <td><?php echo $get_stc_safety['res_persondesignation']; ?></td>
              </tr>
              <tr>
                  <td><?php echo $get_stc_safety['res_personname2']; ?></td>
                  <td><?php echo $get_stc_safety['res_persondesignation2']; ?></td>
              </tr>
              <tr>
                  <td><?php echo $get_stc_safety['res_personname3']; ?></td>
                  <td><?php echo $get_stc_safety['res_persondesignation3']; ?></td>
              </tr>
              <tr>
                  <td colspan="6"><span style="font-weight:bold;">ROOT CAUSE ANALYSIS (BY SUPERVISOR/ENGINEER RESPONSIBLE FOR N.C. AND REVIEWED BY BRANCH/SUPERVISOR WITH SAFETY OFFICER)</span> <br> <?php echo $get_stc_safety['rootcause']; ?> </td>
              </tr>
              <tr>
                  <td colspan="6"><span style="font-weight:bold;">CORRECTIVE ACTION:-</span><?php echo $get_stc_safety['corrective']; ?></td>
              </tr>
              <tr>
                  <td colspan="6"><span style="font-weight:bold;">PREVENTIVE ACTION:-</span><?php echo $get_stc_safety['preventive']; ?></td>
              </tr>
              <tr>
                <td>SCPAR</td>
                <td colspan="2">COMPLIANCE BY (SUP/ENG)</td>
                <td colspan="2">REVIEWED BY (SAFETY OFFICER)</td>
                <td>APPROVED BY (Director)</td>
              </tr>
              <tr>
                  <td>Date</td>
                  <td colspan="2"><?php echo date('d-m-Y', strtotime($get_stc_safety['compliancebysupengdate'])); ?></td>
                  <td colspan="2"><?php echo date('d-m-Y', strtotime($get_stc_safety['reviewedbysodate'])); ?></td>
                  <td colspan="2"><?php echo date('d-m-Y', strtotime($get_stc_safety['reviewedbydirdate'])); ?></td>
              </tr>
              <tr>
                  <td>Sign</td>
                  <td colspan="2"></td>
                  <td colspan="2"></td>
                  <td></td>
              </tr>
              <tr>
                  <td>Name</td>
                  <td colspan="2"><?php echo $get_stc_safety['compliancebysupengname']; ?></td>
                  <td colspan="2"><?php echo $get_stc_safety['reviewedbysoname']; ?></td>
                  <td><?php echo $get_stc_safety['reviewedbydirname']; ?></td>
              </tr>
          </tbody>
      </table>
    </div>
    <div class="col-sm-2 col-md-2">
      <div><img style="position:relative; top:10px; width:10cm;height:280px" src="https://stcassociate.com/stc_sub_agent47/safety_img/<?php echo $get_stc_safety['beforeimage']; ?>"><br><br><h4 class="text-center">Before</h4></div>
      <div><img style="position:relative; top:10px; width:10cm;height:280px" src="https://stcassociate.com/stc_sub_agent47/safety_img/<?php echo $get_stc_safety['afterimage']; ?>"><br><br><h4 class="text-center">After</h4></div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12" style="font-size:14px;">
      ©Global  AC system jsr Pvt Ltd. ALL RIGHTS RESERVED   Issue 01/Rev. 001 3 Jan, 2018_NA
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
}
?>
