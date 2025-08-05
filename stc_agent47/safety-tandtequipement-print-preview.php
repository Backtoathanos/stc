<?php
include_once("../MCU/db.php");

// Get equipment ID from URL parameter
$equipment_id = isset($_GET['equipment_id']) ? (int)$_GET['equipment_id'] : 0;

// Fetch equipment details
$equipment_data = null;
$tools_data = [];

if ($equipment_id > 0) {
    // Fetch equipment details
    $equipment_sql = "SELECT * FROM stc_safety_tandtequipment WHERE id = $equipment_id";
    $equipment_result = mysqli_query($con, $equipment_sql);
    if ($equipment_result && mysqli_num_rows($equipment_result) > 0) {
        $equipment_data = mysqli_fetch_assoc($equipment_result);
        
        // Fetch tools for this equipment
        $tools_sql = "SELECT * FROM stc_safety_tandtequipment_toollist WHERE tandtequipment_id = $equipment_id ORDER BY id ASC";
        $tools_result = mysqli_query($con, $tools_sql);
        if ($tools_result) {
            while ($tool = mysqli_fetch_assoc($tools_result)) {
                $tools_data[] = $tool;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools & Tackels Equipment </title>
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

        .status-safe {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .status-unsafe {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .table-header-fixed {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1000;
        }
        .page-header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            z-index: 2000;
            border-bottom: 2px solid black;
        }
        .footer-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            border-top: 1px solid #ccc;
            padding: 10px 0;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .page-header-fixed {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background-color: white;
                z-index: 2000;
            }
            .table-header-fixed {
                position: fixed;
                top: 0;
                background-color: white;
                z-index: 1000;
            }
            .footer-section {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
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
            <img style="width: 100px;position: relative;top: -10px;" src="images/globallogo.jpg">
          </div>
          <div class="col-6">
            <h3 style="position: relative;top: 50px;margin-bottom: 30px;" align="center" class="header-title"><span style="color: #fe7f26;"><b>GLOBAL AC SYSTEM JSR PVT LTD.</b></span></h3>
          </div>
        </div>
        <div class="row">
          <div class="col-2"><h3>AUD-07</h3></div>
          <div class="col-10"><h3 style="font-size:14px;">AUDIT REGISTER ELECTRIC TOOL (DRILL MACHINE, HAMMER DRILL MACHINE, GRINDER, BLOWER, SOLDERING IRON ETC) </h3></div>
        </div>
      </div>
    </div>
  </div>
  
  <?php if ($equipment_data): ?>
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <!-- Work Order Details -->
      <div class="row">
          <div class="col-md-4">
              <label class="form-label"><strong>Work Order No.:</strong></label>
              <?php echo htmlspecialchars($equipment_data['work_orderno']); ?>
          </div>
          <div class="col-md-4">
              <label class="form-label"><strong>Job Site Name:</strong></label>
              <?php echo htmlspecialchars($equipment_data['sitename']); ?>
          </div>
          <div class="col-md-4">
              <label class="form-label"><strong>Starting Date:</strong></label>
              <?php echo htmlspecialchars(date('d-m-Y', strtotime($equipment_data['starting_date']))); ?>
          </div>
      </div>

             <!-- Table -->
               <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center"><strong>SN</strong></th>
                    <th class="text-center"><strong>Desc of Tool name</strong></th>
                    <th class="text-center"><strong>Serial No</strong></th>
                    <th class="text-center"><strong>Safe To Work</strong></th>
                    <th class="text-center"><strong>Supr/Engg Name</strong></th>
                    <th class="text-center"><strong>Sign</strong></th>
                    <th class="text-center"><strong>Date</strong></th>
                </tr>
            </thead>
                     <tbody>
               <?php 
               $total_rows = 10; // Default 10 rows
               $tools_count = count($tools_data);
               
                               for ($i = 0; $i < $total_rows; $i++): 
                    if ($i < $tools_count):
                        $tool = $tools_data[$i];
                ?>
                    <tr style="font-size:10px;">
                        <td class="text-center"><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($tool['name']); ?></td>
                        <td><?php echo htmlspecialchars($tool['serial_no']); ?></td>
                        <td class="text-center">
                            <span class="data-cell <?php echo strtolower($tool['safe_to_work']) == 'yes' ? 'status-safe' : 'status-unsafe'; ?>">
                                <?php echo htmlspecialchars($tool['safe_to_work']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($tool['sup_engg_name']); ?></td>
                        <td><?php echo htmlspecialchars($tool['sign']); ?></td>
                        <td><?php echo htmlspecialchars($tool['ddate']); ?></td>
                    </tr>
                <?php else: ?>
                   <tr style="font-size:10px;">
                        <td class="text-center"><?php echo $i + 1; ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
               <?php endif; ?>
               <?php endfor; ?>
           </tbody>
      </table>

                           <!-- Footer Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <label class="form-label"><strong>Site in Charge:</strong></label><br>
                <div class="signature-box">
                    <?php echo htmlspecialchars($equipment_data['siteincharge']); ?>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label"><strong>Site in Charge Signature:</strong></label><br>
                <div class="signature-box">
                    _________________
                </div>
            </div>
        </div>  
      </div>
    </div>
    <?php else: ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="alert alert-warning">
          <h4>No Data Found</h4>
          <p>Equipment record not found or no equipment ID provided.</p>
          <a href="/" class="btn btn-primary">Back to Management</a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer-section" style="margin-top: 50px; border-top: 1px solid #ccc; padding-top: 20px;">
        <div class="row">
            <div class="col-sm-12 text-center" style="font-size:14px;">
                ©Global AC system JSR Pvt Ltd. ALL RIGHTS RESERVED Issue 01/Rev. 00 Jan 1, 2018_NA
            </div>
        </div>
    </div>
</div>

<div class="no-print" style="position: fixed; top: 20px; right: 20px;">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Print
    </button>
    <a href="/" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

