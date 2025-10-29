<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);

STCAuthHelper::checkAuth(); 
?>
<?php 
if(isset($_GET['transfer_id'])){
    $transfer_id = intval($_GET['transfer_id']);
    $str_length = 5;
    $str = substr("0000{$transfer_id}", -$str_length);

    include "../MCU/db.php";
    $query = mysqli_query($con, "
        SELECT S.`id`, S.`shopname`, S.`adhoc_id`, S.`qty`, S.`rack_id`, S.`remarks`, S.`created_date`, 
               A.stc_purchase_product_adhoc_itemdesc, A.stc_purchase_product_adhoc_rate, A.stc_purchase_product_adhoc_unit, 
               U.stc_trading_user_name, U.stc_trading_user_cont, U.stc_trading_user_address 
        FROM `stc_shop` S 
        INNER JOIN `stc_purchase_product_adhoc` A ON A.stc_purchase_product_adhoc_id=S.adhoc_id 
        INNER JOIN `stc_trading_user` U ON S.shopname=U.stc_trading_user_location 
        WHERE S.id='$transfer_id'
    ");
    $row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Transfer Challan - STC-<?php echo $str;?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <link rel="stylesheet" href="../stc_symbiote/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../stc_symbiote/css/templatemo-style.css">
    <link rel="stylesheet" href="../stc_symbiote/css/awsomeminho.css">
    <style>
      .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        border: 1.5px solid #333;
      }
      .invoice table td,.invoice table th {
          padding: 5px 8px;
          background: #f7f7f7;
          border-bottom: 1px solid #fff;
          font-size: 15px;
          border: 1.5px solid #333;
      }
      .invoice table th {
          white-space: nowrap;
          font-weight: 600;
          font-size: 16px;
          background: #e9ecef;
          border: 1.5px solid #333;
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
      .invoice table tbody tr:last-child td {
          border: none;
      }
      .tfootar{
        font-size: 20px;
        font-weight: bold;
      }
      .invoice{
        background-color: #FFF;
        padding: 30px 20px 20px 20px;
        border-radius: 8px;
        margin: 30px auto;
        max-width: 1000px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      }
      .header {
        border-bottom: 2px solid #3989c6;
        margin-bottom: 20px;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .header-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
      }
      .header-banner {
        height: 80px;
        width: auto;
        display: block;
        margin-right: 0;
      }
      .header-logo {
        height: 150px;
        width: auto;
        display: block;
        margin-left: 0;
      }
      .header-title {
        text-align: center;
        width: 100%;
      }
      .company-logo {
        height: 110px;
        width: auto;
        display: block;
        margin-left: auto;
        margin-right: 0;
      }
      .company-info {
        font-size: 15px;
        color: #444;
      }
      .challan-title {
        font-size: 2rem;
        font-weight: 700;
        color: #3989c6;
        margin-bottom: 0.5rem;
      }
      .challan-meta h4 {
        font-size: 1.1rem;
        margin-bottom: 0.2rem;
      }
      .details-section {
        margin-top: 30px;
        background: #f9f9f9;
        border: 1px solid #e3e3e3;
        border-radius: 6px;
        padding: 20px;
      }
      .signature-box {
        margin-top: 30px;
      }
      .signature-label {
        font-weight: 600;
        margin-bottom: 8px;
      }
      .signature-line {
        width: 320px;
        height: 60px;
        border: 1.5px solid #333;
        border-radius: 4px;
        background: #fff;
      }
      .impact-heading {
        text-align: left;
        font-size: 2.5rem;
        font-weight: 900;
        font-family: 'Segoe UI', 'Arial', sans-serif;
        color: #222;
        letter-spacing: 2px;
        margin-bottom: 0.5em;
        position: relative;
        padding-left: 16px;
      }
      .impact-heading::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10%;
        width: 6px;
        height: 80%;
        background: linear-gradient(180deg, #ff5722 0%, #ffc107 100%);
        border-radius: 3px;
      }
      @media print {
        .invoice {
          box-shadow: none;
          margin: 0;
          padding: 0;
        }
        .text-right.hidden-print, .tm-footer {
          display: none !important;
        }
        .footer {
          display: flex !important;
          flex-direction: row !important;
          justify-content: space-between !important;
        }
        .footer > div {
          width: 48% !important;
          display: block !important;
        }
      }
    </style>
  </head>
  <body>
    <div class="text-right hidden-print" style="margin: 20px 30px 0 0;">
      <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
    </div>
    <div class="container-fluid tm-mt-big tm-mb-big invoice">
      <div class="header">
        <div class="header-col" style="flex:1; align-items:flex-start;">
          <h1 class="impact-heading">Global AC</h1>
          <p style="margin-top:10px;">
            502/A, Jawahar Nagar, Road No.: 17, PO - Azad Nagar, Mango, Jamshedpur - 832110, Jharkhand, INDIA
          </p>
          <p>
            Mobile No. : +91-7001006703/ +91-9204400572<br>
            E.Mail:gld@globalacsystem.com<br> 
          </p>
        </div>
        <div class="header-col header-title" style="flex:2;">
          <h1 class="challan-title"><b>TRANSFER CHALLAN</b></h1>
          <h4>Transfer No : STC/<?php echo $str; ?></h4>
          <h4>Transfer Date : <?php echo isset($row['created_date']) ? date('d-m-Y',strtotime($row['created_date'])) : '-'; ?></h4>
          <h4>Branch Name : <?php echo isset($row['shopname']) ? htmlspecialchars($row['shopname']) : '-'; ?></h4>
        </div>
        <div class="header-col" style="flex:1; align-items:flex-end;">
          <a target="_blank" id="logo_print_pre" href="#">
            <img src="../stc_symbiote/img/stc_logo.png" class="header-logo" alt="STC Logo">
          </a>
        </div>
      </div>
      <div class="row med">
        <div class="container-fluid">
          <div class="row invoice-setion">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="row">
                <div class="col-lg-12 billshowvalue">
                  <h6>
                    <span>GSTIN : <b>20JCBPS6008G1ZT</b></span>
                  </h6>
                  <h6>
                    Transfer No : <b>STC/<?php echo $str;?></b>
                  </h6>
                  <h6>
                    Transfer Date : <b><?php echo isset($row['created_date']) ? date('d-m-Y',strtotime($row['created_date'])) : '-'; ?></b>
                  </h6>
                  <h6>
                    State : <b>JHARKHAND</b>
                  </h6>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="col cust-ref-sec">
                <h6>
                  Receiver Name : <b><?php echo htmlspecialchars($row['stc_trading_user_name']); ?></b>
                </h6>
                <h6>
                  Receiver Contact : <b><?php echo htmlspecialchars($row['stc_trading_user_cont']); ?></b>
                </h6>
                <h6>
                  Receiver Address : <b><?php echo htmlspecialchars($row['stc_trading_user_address']); ?></b>
                </h6>
                <h6>
                  Remarks : <b><?php echo htmlspecialchars($row['remarks']); ?></b>
                </h6>
              </div>
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th>#</th>
                  <th width="50%" class="text-left">Item Description</th>
                  <th class="text-right">Unit</th>
                  <th class="text-right">Qty</th>
                </tr>
                <?php if($row){ ?>
                <tr>
                  <td>1</td>
                  <td class="text-left"><?php echo htmlspecialchars($row['stc_purchase_product_adhoc_itemdesc']); ?></td>
                  <td class="text-right"><?php echo htmlspecialchars($row['stc_purchase_product_adhoc_unit']); ?></td>
                  <td class="text-right"><?php echo htmlspecialchars(number_format($row['qty'], 2)); ?></td>
                </tr>
                <?php } else { ?>
                <tr><td colspan="4" class="text-center">No data found for this Transfer ID.</td></tr>
                <?php } ?>
              </table>
            </div>
          </div>
          <div class="row footer" style="margin-top: 40px; display: flex; flex-direction: row; justify-content: space-between;">
            <div style="position: relative;left: 23px;width:48%;">
              <div class="pre-cond-plus-total-taxa"></div>
              <div class="pre-cond-plus-total-taxb">
                <div id="signatory-stc">
                  <p>For <span><b>Global AC</b></span></p>
                  <br><br><br>
                  <p><span align="center">Authorised Signatory</span></p>
                </div>
              </div>
            </div>
            <div style="width:48%;">
              <div class="pre-cond-plus-total-taxa"></div>
              <div class="pre-cond-plus-total-taxb">
                <div id="signatory-stc">
                  <p>For <span><b>Receiver</b></span></p>
                  <br><br><br>
                  <p><span align="center">Receiver Signatory</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
      $(document).ready(function(){
         $('#printInvoice').click(function(){
            window.print();
            return true;
        });
      });
    </script>
    <?php include "../stc_symbiote/footer.php";?>
  </body>
</html>
<?php
}
?> 