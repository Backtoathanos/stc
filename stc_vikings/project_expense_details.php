<?php 
if(isset($_GET['pro_id'])){
    $num = $_GET['pro_id'];
    $str_length = 5;
    $dateFrom=date('Y-m-d', strtotime($_GET['dateFrom']));
    $dateTo=date('Y-m-d', strtotime($_GET['dateTo']));
    // hardcoded left padding if number < $str_length
    $str = substr("0000{$num}", -$str_length);

    include "../MCU/db.php";
    $checkpurchaseorder=mysqli_query($con, "
      SELECT * FROM `stc_cust_project`
      WHERE `stc_cust_project_id`='".mysqli_real_escape_string($con, $_GET['pro_id'])."'
    ");
    
    if(!$checkpurchaseorder || mysqli_num_rows($checkpurchaseorder) == 0) {
        echo "<div style='text-align: center; padding: 50px; color: #dc3545;'>";
        echo "<h2>Project Not Found</h2>";
        echo "<p>The requested project does not exist or you don't have permission to view it.</p>";
        echo "<a href='javascript:history.back()' class='btn btn-primary'>Go Back</a>";
        echo "</div>";
        exit;
    }
    
    $get_stc_purchase_product=mysqli_fetch_assoc($checkpurchaseorder);
    $get_purchase_product_merchant_name=$get_stc_purchase_product['stc_cust_project_title'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Project Expense Details - STC-
        <?php echo $str;?>
    </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
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

        .invoice table td,
        .invoice table th {
            padding: 5px;
            background: #7d6161;
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

        .invoice table .qty,
        .invoice table .total,
        .invoice table .unit {
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

        .tfootar {
            font-size: 20px;
            font-weight: bold;
        }

        .invoice {
            background-color: #FFF;
        }

        .summary-card {
            transition: transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .page-break {
            page-break-before: always;
            break-before: page;
        }

        .table-row {
            page-break-inside: avoid;
        }

        @media print {
            .invoice {
                margin-top: -5px;
                font-size: 15px !important;
                overflow: hidden !important;
            }

            /* Repeat header on every printed page */
            .print-page {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .print-page thead {
                display: table-header-group;
            }

            .print-page tbody {
                display: table-row-group;
            }

            /* IMPORTANT: only reset the OUTER wrapper cells (not nested table cells) */
            .print-page > thead > tr > td,
            .print-page > tbody > tr > td {
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                color: black;
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
                display: none !important;
            }

            .tm-footer {
                visibility: hidden;
            }

            .block-foot {
                position: relative;
                bottom: 0px;
            }

            /*tfoot {
            page-break-inside: avoid;
          }*/
            .invoice .header {
                border-bottom: 2px solid;
                margin-bottom: 10px;
            }

            .invoice .med {
                position: relative;
                top: 0px;
                bottom: 0px;
            }

            .block-foot {
                position: fixed;
                margin-bottom: -8px;
            }

            .table {
                height: 100%;
            }

            #logo_print_pre {
                margin-top: 6px;
                float: right;
                margin-right: 0px;
            }

            #logo_print_pre {
                margin-top: 6px;
                float: right;
                margin-right: 0px;
            }
        }
    </style>
</head>

<body>
    <div class="text-right hidden-print">
        <button id="printInvoice" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
        <button class="btn btn-info"><i class="fas fa-file-pdf-o"></i> Export as PDF</button>
    </div>

    <div class="container-fluid tm-mt-big tm-mb-big invoice">
        <div class="print-footer"></div>
        <table class="print-page">
            <thead>
                <tr>
                    <td>
                        <div class="row header">
                            <!-- Create order -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                <div style="height: 50px;"><img style="height: 50px;"
                                        src="https://stcassociate.com/stc_symbiote/img/stc-header.png"></div>
                                <p>
                                    Rajmahal Apartment, D/304 3rd Floor, Block No 1, Pardih, Jamshedpur, Jharkhand
                                    832110
                                </p>
                                <p>
                                    Mobile No. : +91-8986811304<br>
                                    E.Mail:stc111213@gmail.com<br>
                                    GSTIN: 20JCBPS6008G1ZT
                                </p>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <h2 align="center">Project Expense Summary</h2>
                                <div style="text-align: center;">
                                    <h4 align="center">Project ID: STC/P/
                                        <?php echo $str; ?>
                                    </h4>
                                    <h4 align="center">Date:
                                        <?php echo date('d-m-Y'); ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2" style="text-align: right;">
                                <a target="_blank" id="logo_print_pre" href="#" style="float: right;">
                                    <img src="https://stcassociate.com/stc_symbiote/img/stc_logo.png"
                                        style="max-height: 120px; width: auto;">
                                </a>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div style="font-size: 18px; margin-bottom: 20px;" class="">
                                    <p><strong>Project:</strong>
                                        <?php echo $get_stc_purchase_product['stc_cust_project_title']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <!-- main area -->
                        <div class="row med">
                            <div class="container-fluid">
                                <!-- Create order -->
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="">
                                            <h4><b>Kind Attn: Mr/Miss.
                                                    <?php echo $get_stc_purchase_product['stc_cust_project_responsive_person']; ?>
                                                </b></h4>
                                        </div>
                                        <div
                                            style="font-size: 16px; margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #3989c6; border-radius: 5px;">
                                            <p style="margin: 0; font-style: italic; color: #555;">
                                                <strong>"Excellence in project management is not just about meeting
                                                    deadlines, but about
                                                    delivering value that exceeds expectations and builds lasting
                                                    partnerships."</strong>
                                            </p>
                                            <p
                                                style="margin: 10px 0 0 0; text-align: right; color: #666; font-size: 14px;">
                                                - STC Associates
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Requisitions for Current Month -->
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <table border="0" cellspacing="0" cellpadding="0"
                                                style="width: 100%; border-collapse: collapse; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); table-layout: fixed;">
                                                <thead>
                                                    <tr style="background-color: #3989c6; color: white;">
                                                        <th
                                                            style="padding: 10px; text-align: center; border: 1px solid #ddd; width: 8%;">
                                                            SL
                                                            No</th>
                                                        <th
                                                            style="padding: 10px; text-align: left; border: 1px solid #ddd; width: 50%;">
                                                            Item Name</th>
                                                        <th
                                                            style="padding: 10px; text-align: center; border: 1px solid #ddd; width: 12%;">
                                                            Unit</th>
                                                        <th
                                                            style="padding: 10px; text-align: center; border: 1px solid #ddd; width: 12%;">
                                                            Qty</th>
                                                        <th
                                                            style="padding: 10px; text-align: right; border: 1px solid #ddd; width: 15%;">
                                                            Rate</th>
                                                        <th
                                                            style="padding: 10px; text-align: right; border: 1px solid #ddd; width: 15%;">
                                                            Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    // Get requisitions with proper joins and rate calculation
                                                    $query="
                                                        SELECT 
                                                        D.stc_cust_project_id,
                                                        B.stc_cust_super_requisition_list_items_title as item_name,
                                                        B.stc_cust_super_requisition_list_items_unit as unit,
                                                        A.stc_cust_super_requisition_list_items_rec_recqty as qty,
                                                        E.stc_purchase_product_adhoc_rate as rate,
                                                        A.stc_cust_super_requisition_list_items_rec_recqty * E.stc_purchase_product_adhoc_rate as total,
                                                        A.stc_cust_super_requisition_list_items_rec_date as rec_date
                                                        FROM stc_cust_super_requisition_list_items_rec A 
                                                        INNER JOIN stc_cust_super_requisition_list_items B
                                                        ON A.stc_cust_super_requisition_list_items_rec_list_item_id = B.stc_cust_super_requisition_list_id
                                                        INNER JOIN stc_cust_super_requisition_list C
                                                        ON A.stc_cust_super_requisition_list_items_rec_list_id = C.stc_cust_super_requisition_list_id
                                                        INNER JOIN stc_cust_project D
                                                        ON C.stc_cust_super_requisition_list_project_id = D.stc_cust_project_id
                                                        INNER JOIN stc_purchase_product_adhoc E
                                                        ON A.stc_cust_super_requisition_list_items_rec_list_poaid = E.stc_purchase_product_adhoc_id
                                                        WHERE DATE(A.stc_cust_super_requisition_list_items_rec_date) BETWEEN '".$dateFrom."' AND '".$dateTo."'
                                                        AND D.stc_cust_project_id = '".mysqli_real_escape_string($con, $_GET['pro_id'])."' AND E.stc_purchase_product_adhoc_rate > 0
                                                        ORDER BY D.stc_cust_project_title, B.stc_cust_super_requisition_list_items_title ASC
                                                    ";
                                                    // echo $query;
                                                    $requisition_query = mysqli_query($con, $query);
                                                    $rows_per_page = 20;
                                                    $grand_total = 0;
                                                    $rows = array();

                                                    if ($requisition_query) {
                                                        while ($req_row = mysqli_fetch_assoc($requisition_query)) {
                                                            $rows[] = $req_row;
                                                            $grand_total += (float) $req_row['total'];
                                                        }
                                                    }

                                                    $total_rows = count($rows);
                                                    $total_pages = ($total_rows > 0) ? (int) ceil($total_rows / $rows_per_page) : 1;
                                                    $sl = 0;

                                                    if ($total_rows > 0) {
                                                        foreach ($rows as $req_row) {
                                                            $sl++;
                                                            $rate = $req_row['rate'];
                                                            $total = $req_row['total'];
                                                            $row_break_class = (($sl > 1) && (($sl - 1) % $rows_per_page === 0)) ? ' page-break' : '';
                                                ?>
                                                <tr class="table-row<?php echo $row_break_class; ?>">
                                                    <td
                                                        style="padding: 12px; text-align: center; border: 1px solid #ddd; background-color: #f8f9fa;">
                                                        <?php echo $sl; ?>
                                                    </td>
                                                    <td
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        <?php echo htmlspecialchars($req_row['item_name']); ?>
                                                    </td>
                                                    <td
                                                        style="padding: 12px; text-align: left; border: 1px solid #ddd;">
                                                        <?php echo htmlspecialchars($req_row['unit']); ?>
                                                    </td>
                                                    <td
                                                        style="padding: 12px; text-align: center; border: 1px solid #ddd;">
                                                        <?php echo number_format($req_row['qty'], 2); ?>
                                                    </td>
                                                    <td
                                                        style="padding: 12px; text-align: right; border: 1px solid #ddd;">
                                                        ₹
                                                        <?php echo number_format($rate, 2); ?>
                                                    </td>
                                                    <td
                                                        style="padding: 12px; text-align: right; border: 1px solid #ddd; font-weight: bold;">
                                                        ₹
                                                        <?php echo number_format($total, 2); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                            if (($sl % $rows_per_page === 0) && ($sl < $total_rows)) {
                                                                $page_no = (int) ($sl / $rows_per_page);
                                                ?>
                                                <tr>
                                                    <td colspan="6"
                                                        style="padding: 8px 0; text-align: right; border: none; font-size: 12px; color: #333;">
                                                        Page <?php echo $page_no; ?> of <?php echo $total_pages; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                            }
                                                        }
                                                ?>
                                                <tr
                                                    style="background-color: #f8f9fa; font-weight: bold; border-top: 2px solid #3989c6;">
                                                    <td colspan="5"
                                                        style="padding: 15px; text-align: right; border: 1px solid #ddd; font-size: 16px;">
                                                        Grand Total:</td>
                                                    <td
                                                        style="padding: 15px; text-align: right; border: 1px solid #ddd; font-size: 16px; color: #3989c6;">
                                                        ₹
                                                        <?php echo number_format($grand_total, 2); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6"
                                                        style="padding: 40px; text-align: center; border: 1px solid #ddd; color: #666; background-color: #f8f9fa;">
                                                        <i class="fas fa-inbox"
                                                            style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                                        <h4 style="color: #666; margin: 15px 0;">No Requisitions Found
                                                        </h4>
                                                        <p style="margin: 0;">No requisitions were found for the date range (
                                                            <?php echo date('d M Y', strtotime($dateFrom)); ?> to
                                                            <?php echo date('d M Y', strtotime($dateTo)); ?>)
                                                        </p>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }

                                                    $last_page_no = ($total_rows > 0) ? $total_pages : 1;
                                                ?>
                                                <tr>
                                                    <td colspan="6"
                                                        style="padding: 8px 0; text-align: right; border: none; font-size: 12px; color: #333;">
                                                        Page <?php echo $last_page_no; ?> of <?php echo $total_pages; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#printInvoice').click(function () {
                Popup($('.invoice')[0].outerHTML);
                function Popup(data) {
                    window.print();
                    return true;
                }
            });

            // Calculate optimal rows per page
            function calculateRowsPerPage() {
                var windowHeight = $(window).height();
                var headerHeight = $('.header').outerHeight();
                var availableHeight = windowHeight - headerHeight - 100; // 100px for margins
                var rowHeight = 50; // Approximate row height
                var optimalRows = Math.floor(availableHeight / rowHeight);
                return Math.max(10, Math.min(20, optimalRows)); // Between 10-20 rows
            }

            // Apply page break logic
            function applyPageBreaks() {
                var rowsPerPage = calculateRowsPerPage();
                $('.table-row').each(function (index) {
                    if (index > 0 && index % rowsPerPage === 0) {
                        $(this).addClass('page-break');
                    }
                });
            }

            // Apply on load
            // applyPageBreaks();
        });
    </script>
</body>

</html>
<?php
}
?>