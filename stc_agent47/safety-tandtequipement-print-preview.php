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
          <div class="col-3"><h3>AUD-07</h3></div>
          <div class="col-6"><h3>AUDIT REGISTER ELECTRIC TOOL (DRILL MACHINE, HAMMER DRILL MACHINE, GRINDER, BLOWER, SOLDERING IRON ETC) </h3></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <h2 class="mb-4 text-center">Tool Inspection Form</h2>

      <!-- Work Order Details -->
      <div class="mb-3">
          <label for="workOrder" class="form-label">Work Order No.:</label>
          <input type="text" class="form-control" id="workOrder" placeholder="Enter Work Order No.">
      </div>
      <div class="row">
          <div class="col-md-6 mb-3">
              <label for="jobSiteName" class="form-label">Job Site Name:</label>
              <input type="text" class="form-control" id="jobSiteName" placeholder="Enter Job Site Name">
          </div>
          <div class="col-md-6 mb-3">
              <label for="startingDate" class="form-label">Starting Date:</label>
              <input type="date" class="form-control" id="startingDate">
          </div>
      </div>

      <!-- Table -->
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>SN</th>
                  <th>Description of Tool</th>
                  <th>Inspection Requirement and Status</th>
                  <th>Safe to Work</th>
                  <th>Name: SUPR/ENGR</th>
                  <th>Sign/Date</th>
              </tr>
              <tr>
                  <th></th>
                  <th>Name / Type / Make</th>
                  <th>Serial No.</th>
                  <th>3 Core Cable & Plug</th>
                  <th>Insulation</th>
                  <th>Earthing Connection</th>
                  <th>Handle</th>
                  <th></th>
                  <th></th>
              </tr>
          </thead>
          <tbody>
              <!-- Placeholder Rows -->
              <tr>
                  <td>1</td>
                  <td><input type="text" class="form-control" placeholder="Name / Type / Make"></td>
                  <td><input type="text" class="form-control" placeholder="Serial No."></td>
                  <td><input type="text" class="form-control" placeholder="3 Core Cable & Plug"></td>
                  <td><input type="text" class="form-control" placeholder="Insulation"></td>
                  <td><input type="text" class="form-control" placeholder="Earthing Connection"></td>
                  <td><input type="text" class="form-control" placeholder="Handle"></td>
                  <td><input type="text" class="form-control" placeholder="Yes / No"></td>
                  <td><input type="text" class="form-control" placeholder="Name"></td>
                  <td><input type="text" class="form-control" placeholder="Sign/Date"></td>
              </tr>
              <!-- Add more rows as needed -->
          </tbody>
      </table>

      <!-- Footer Section -->
      <div class="row mt-4">
          <div class="col-md-6">
              <label class="form-label">Safety Supervisor Signature:</label>
              <input type="text" class="form-control" placeholder="Safety Supervisor Signature">
          </div>
          <div class="col-md-6">
              <label class="form-label">Site in Charge Signature:</label>
              <input type="text" class="form-control" placeholder="Site in Charge Signature">
          </div>
      </div>  
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
