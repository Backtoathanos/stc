<?php
session_start();

// Set session and cookie parameters
$session_duration = 24 * 60; // 24 minutes (cPanel limit)
$cookie_duration = 7 * 24 * 60 * 60; // 7 days in seconds

// If session exists
if(isset($_SESSION["stc_agent_id"])) {
    // Check if remember cookie exists, if not set it
    if(!isset($_COOKIE["stc_agent_remember"])) {
        setcookie("stc_agent_remember", $_SESSION["stc_agent_id"], time() + $cookie_duration, "/");
        setcookie("stc_agent_name", $_SESSION["stc_agent_name"], time() + $cookie_duration, "/");
        setcookie("stc_agent_role", $_SESSION["stc_agent_role"], time() + $cookie_duration, "/");
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    $_SESSION["stc_agent_name"] = $_COOKIE["stc_agent_name"];
    $_SESSION["stc_agent_role"] = $_COOKIE["stc_agent_role"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
    setcookie("stc_agent_name", $_COOKIE["stc_agent_name"], time() + $cookie_duration, "/");
    setcookie("stc_agent_role", $_COOKIE["stc_agent_role"], time() + $cookie_duration, "/");
}
// Neither session nor cookie exists
else {
    header("Location: index.html");
    exit();
}
include_once("../MCU/db.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Management - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
    <style> 
        .fade:not(.show) {
          opacity: 10;
        }    

        .close-tag-beg{
            display: none;
        }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php include_once("ui-setting.php");?>        
        <div class="app-main">
                <?php include_once("sidebar-nav.php");?>                   
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-display1 icon-gradient bg-premium-dark">
                                        </i>
                                    </div>
                                    <div>Order Management
                                        <div class="page-title-subheading">This sections shows you your supervisor orders & requirements here.<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#check-order">
                                    <span>Check Order <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#sup-requis">
                                    <span>Check Requisition <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade " id="check-order" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Supervisor Order</h5>
                                                <form class="#">
                                                   <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="mb-0 table table-hover">
                                                                <thead>
                                                                    <th>Order No</th>
                                                                    <th>Order Date</th>
                                                                    <th>Requisition By<br> <span>Supervisor ID</span><br><span>Supervisor name</span></th>
                                                                    <th>Requisition For<br> <span>Project name</span><br><span>project address</span></th>
                                                                    <th>Status</th>
                                                                    <th>View</th>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                    $requisbysupforproqry=mysqli_query($con, "
                                                                        SELECT 
                                                                            `stc_cust_super_requisition_id`,
                                                                            `stc_cust_super_requisition_date`, 
                                                                            `stc_cust_project_title`, 
                                                                            `stc_cust_project_address`, 
                                                                            `stc_cust_pro_supervisor_id`, 
                                                                            `stc_cust_pro_supervisor_fullname`,
                                                                            `stc_cust_super_requisition_status` 
                                                                        FROM `stc_cust_super_requisition`
                                                                        INNER JOIN `stc_cust_pro_supervisor` 
                                                                        ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_super_id`
                                                                        INNER JOIN `stc_cust_project` 
                                                                        ON `stc_cust_project_id`=`stc_cust_super_requisition_project_id`
                                                                        INNER JOIN `stc_agents` 
                                                                        ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
                                                                        WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                        ORDER BY `stc_cust_super_requisition_id` DESC
                                                                    ");
                                                                    if(mysqli_num_rows($requisbysupforproqry)!=0){
                                                                        foreach($requisbysupforproqry as $currrow){
                                                                            $currentstatus='';
                                                                            if($currrow['stc_cust_super_requisition_status']==1){
                                                                                $currentstatus="PROCESS";
                                                                            }elseif($currrow['stc_cust_super_requisition_status']==0){
                                                                                $currentstatus="CANCEL";
                                                                            }else{
                                                                                $currentstatus="ACCEPTED";
                                                                            }
                                                                            echo "
                                                                                <tr>
                                                                                    <td>STC/O/A/S/".substr("0000{$currrow['stc_cust_super_requisition_id']}", -5)."</td>
                                                                                    <td>".date('d-M-Y', strtotime($currrow['stc_cust_super_requisition_date']))."</td>
                                                                                    <td>STC/A/S/".substr("0000{$currrow['stc_cust_pro_supervisor_id']}", -5)."<br>".$currrow['stc_cust_pro_supervisor_fullname']."</td>
                                                                                    <td>".$currrow['stc_cust_project_title']."<br>".$currrow['stc_cust_project_address']."</td>
                                                                                    <td>".$currentstatus."</td>
                                                                                    <td><a href='#' style='font-size: 25px;font-weight: bold;color: black;' class='ag-show-grid' id='".$currrow['stc_cust_super_requisition_id']."'><i class='fas fa-eye'></i></a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan='6'>
                                                                                        <div style='display:none;' id='togdiv".$currrow['stc_cust_super_requisition_id']."'>
                                                                                            Loading...
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            ";
                                                                        }
                                                                    }else{}
                                                                ?>
                                                                </tbody>                                                                   
                                                            </table>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade show active" id="sup-requis" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Supervisor Requisition</h5>
                                                <form class="#">
                                                    <div class="row">
                                                        <div class="col-md-12" style="margin-bottom:10px;">
                                                            <div class="input-group">
                                                                <input type="text" id="stc-order-search" placeholder="Search by project, supervisor, item or type..." class="form-control" style="border-radius:4px 0 0 4px;">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-primary" type="button" id="stc-order-search-btn" style="border-radius:0;"><i class="fas fa-search"></i> Search</button>
                                                                    <button class="btn btn-secondary" type="button" id="stc-order-reset-btn" style="border-radius:0 4px 4px 0;"><i class="fas fa-times"></i> Reset</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div id="stc-ord-bulk-bar" style="display:none;margin-bottom:8px;padding:8px 12px;background:#fffbe6;border:1px solid #ffe58f;border-radius:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                                            <span style="font-weight:600;color:#555;margin-right:6px;"><span id="stc-ord-sel-count">0</span> item(s) selected</span>
                                                            <button type="button" class="btn btn-success btn-sm" id="stc-ord-bulk-approve"><i class="fas fa-check-circle"></i> Approve Selected</button>
                                                            <button type="button" class="btn btn-warning btn-sm" id="stc-ord-bulk-reject" style="color:#fff;"><i class="fas fa-ban"></i> Reject Selected</button>
                                                            <button type="button" class="btn btn-danger btn-sm" id="stc-ord-bulk-delete"><i class="fas fa-trash"></i> Delete Selected</button>
                                                            <button type="button" class="btn btn-default btn-sm" id="stc-ord-bulk-clear" style="margin-left:4px;"><i class="fas fa-times"></i> Clear</button>
                                                        </div>
                                                        <div class="col-md-12" style="overflow-x:auto;">
                                                            <table class="mb-0 table table-hover table-bordered" id="stc-requis-table">
                                                                <thead>
                                                                    <th class="text-center" style="width:38px;"><input type="checkbox" id="stc-ord-check-all" title="Select all on this page" style="width:16px;height:16px;cursor:pointer;"></th>
                                                                    <th class="text-center">Sl No</th>
                                                                    <th class="text-center stc-ord-sortable" data-col="req_date" style="cursor:pointer;white-space:nowrap;">Requisition Date <span class="stc-ord-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span><br><span style="font-size:10px;font-weight:normal;color:#777;">&amp; No</span></th>
                                                                    <th class="text-center stc-ord-sortable" data-col="project_title" style="cursor:pointer;white-space:nowrap;">Requisition For <span class="stc-ord-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span></th>
                                                                    <th class="text-center stc-ord-sortable" data-col="supervisor_name" style="cursor:pointer;white-space:nowrap;">Requisition From <span class="stc-ord-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span></th>
                                                                    <th class="text-center stc-ord-sortable" data-col="item_title" style="cursor:pointer;white-space:nowrap;">Item Desc <span class="stc-ord-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span></th>
                                                                    <th class="text-center">Unit</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th class="text-center">Approve Quantity</th>
                                                                    <th class="text-center">Remains Quantity</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Priority</th>
                                                                    <th class="text-center">Type</th>
                                                                    <th class="text-center">ADD</th>
                                                                    <th class="text-center">REJECT</th>
                                                                </thead>
                                                                <tbody id="stc-order-tbody">
                                                                    <tr id="stc-order-loader">
                                                                        <td colspan="15" class="text-center" style="padding:20px;">
                                                                            <i class="fas fa-spinner fa-spin"></i> Loading...
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div id="stc-order-pagination" style="margin-top:10px;padding:5px 0;"></div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script>
        // function myFunction() {
        //     var input, filter, table, tr, td, i, txtValue;
        //     input = document.getElementById("myInput");
        //     filter = input.value.toUpperCase();
        //     table = document.getElementById("stc-requis-table");
        //     tr = table.getElementsByTagName("tr");
        //     for (i = 0; i < tr.length; i++) {
        //         td = tr[i].getElementsByTagName("td")[0];
        //         if (td) {
        //             txtValue = td.textContent || td.innerText;
        //             if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //                 tr[i].style.display = "";
        //             } else {
        //                 tr[i].style.display = "none";
        //             }
        //         }       
        //     }
        // }
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
                // var pd_title=$('.agent-pro-search').val();
                // window.location.href="stc-product.php?pd_name="+pd_title;
            });

            $('body').delegate('.search-icon-2', 'click', function(e){
                var pd_title=$('.agent-pro-search').val();
                if(pd_title!=""){
                    window.location.href="stc-product.php?pd_name="+pd_title;
                }
            });    
        });

    </script>
    <script>
        /*--------------------------Order List AJAX Pagination--------------------------*/
        var stcOrderCurrentPage = 1;
        var stcOrderPerPage = 15;
        var stcOrderSearch = '';
        var stcOrderSortCol = 'req_date';
        var stcOrderSortDir = 'DESC';

        function stcOrdEsc(str) {
            if (str === null || str === undefined) return '';
            return $('<div>').text(String(str)).html();
        }
        function stcOrdEscAttr(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g,'&amp;').replace(/"/g,'&quot;')
                .replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function stcLoadOrderList(page) {
            stcOrderCurrentPage = page;
            var $tbody = $('#stc-order-tbody');
            $tbody.html('<tr id="stc-order-loader"><td colspan="15" class="text-center" style="padding:20px;"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
            $('#stc-order-pagination').html('');

            $.ajax({
                url: 'nemesis/stc_project.php',
                method: 'POST',
                data: { get_order_list: 1, page: page, per_page: stcOrderPerPage, search: stcOrderSearch, sort_col: stcOrderSortCol, sort_dir: stcOrderSortDir },
                dataType: 'json',
                success: function(res) {
                    $tbody.empty();
                    $('#stc-ord-check-all').prop('checked', false);
                    stcOrdUpdateBulkBar();
                    if (!res.success || !res.rows || res.rows.length === 0) {
                        $tbody.html('<tr><td colspan="15" class="text-center"><b>No Requisition Found!!!</b></td></tr>');
                        return;
                    }
                    $.each(res.rows, function(i, r) {
                        var rowStyle  = r.is_urgent ? ' style="background:#ffa5a5;color:black;"' : '';
                        var statusBadge = (r.item_status == 1)
                            ? '<span style="background-color:#3498db;color:white;padding:2px 6px;border-radius:3px;">Ordered</span>'
                            : '<span style="background-color:#2ecc71;color:white;padding:2px 6px;border-radius:3px;">Approved</span>';

                        var actionHtml = '';
                        var deleteHtml = '';
                        if (r.req_status == 1) {
                            actionHtml = '<a href="#" class="btn btn-primary add_to_purchase" atc-ic="'+r.item_list_id+'" id="add_to_accept_cart'+r.item_list_id+'" title="Approve" style="font-size:35px;color:black;"><i class="fas fa-plus-circle"></i></a>';
                            deleteHtml = '<a href="#" class="btn btn-danger remove_from_purchase" operat-ic="'+r.item_list_id+'" list-id="'+r.list_id+'" title="Reject" id="rem_from_accept_cart'+r.item_list_id+'" style="font-size:35px;color:black;"><i class="fas fa-ban"></i></a>';
                        }

                        var tr = '<tr id="stc-req-tr-'+r.item_list_id+'" class="tr-search-fromhere"'+rowStyle+'>'
                            + '<td class="text-center"><input type="checkbox" class="stc-ord-check" style="width:16px;height:16px;cursor:pointer;"'
                            +     ' data-item-id="'+r.item_list_id+'"'
                            +     ' data-list-id="'+r.list_id+'"'
                            +     ' data-req-qty="'+r.req_qty_raw+'"'
                            +     ' data-item-title="'+stcOrdEscAttr(r.item_title)+'"'
                            +     ' data-req-date="'+stcOrdEscAttr(r.req_date)+'"></td>'
                            + '<td class="text-center">'+r.sl+'</td>'
                            + '<td>'+r.list_id+' <br> '+stcOrdEsc(r.req_date)+'</td>'
                            + '<td>'+stcOrdEsc(r.project_title)+'</td>'
                            + '<td>'+stcOrdEsc(r.supervisor_name)+'</td>'
                            + '<td><a href="#" class="stc-req-item-name-open-edit"'
                            +     ' data-item-id="'+r.item_list_id+'"'
                            +     ' data-item-name="'+stcOrdEscAttr(r.item_title)+'"'
                            +     ' data-item-priority="'+(r.is_urgent ? 2 : 1)+'">'
                            +     stcOrdEsc(r.item_title)+'</a></td>'
                            + '<td class="text-center">'+stcOrdEsc(r.unit)+'</td>'
                            + '<td class="text-right">'+r.req_qty
                            +     '<input type="hidden" class="stc-sup-req-qty'+r.item_list_id+'" value="'+r.req_qty_raw+'"></td>'
                            + '<td><input type="number" class="form-control stc-sup-appr-qty'+r.item_list_id+'" style="width:60px;padding:4px;" value="'+r.req_qty_raw+'"></td>'
                            + '<td class="text-right">'+r.remaining_qty+'</td>'
                            + '<td>'+statusBadge+'</td>'
                            + '<td class="text-center">'+stcOrdEsc(r.priority)+'</td>'
                            + '<td>'+stcOrdEsc(r.type)+'</td>'
                            + '<td class="text-center">'+actionHtml+'</td>'
                            + '<td class="text-center">'+deleteHtml+'</td>'
                            + '</tr>';
                        $tbody.append(tr);
                    });

                    stcRenderOrderPagination(res.total, res.page, res.per_page, res.total_pages);
                    // Refresh sort icons
                    $('.stc-ord-sortable .stc-ord-sort-icon').html('&#8645;').css('color','#aaa');
                    $('.stc-ord-sortable[data-col="'+res.sort_col+'"] .stc-ord-sort-icon')
                        .html(res.sort_dir === 'ASC' ? '&#8593;' : '&#8595;').css('color','#333');
                },
                error: function() {
                    $tbody.html('<tr><td colspan="15" class="text-center text-danger">Error loading data. Please refresh.</td></tr>');
                }
            });
        }

        function stcRenderOrderPagination(total, page, per_page, total_pages) {
            var $pg = $('#stc-order-pagination');
            if (total_pages <= 1) { $pg.html(''); return; }
            var html = '<nav><ul class="pagination pagination-sm" style="flex-wrap:wrap;margin-bottom:0;">';
            html += '<li class="'+(page <= 1 ? 'disabled' : '')+'">'
                +  '<a href="#" class="stc-order-page-btn" data-page="'+(page - 1)+'">&laquo;</a></li>';
            var start = Math.max(1, page - 2);
            var end   = Math.min(total_pages, page + 2);
            if (start > 1) {
                html += '<li><a href="#" class="stc-order-page-btn" data-page="1">1</a></li>';
                if (start > 2) html += '<li class="disabled"><a>&hellip;</a></li>';
            }
            for (var p = start; p <= end; p++) {
                html += '<li class="'+(p === page ? 'active' : '')+'">'
                      + '<a href="#" class="stc-order-page-btn" data-page="'+p+'">'+p+'</a></li>';
            }
            if (end < total_pages) {
                if (end < total_pages - 1) html += '<li class="disabled"><a>&hellip;</a></li>';
                html += '<li><a href="#" class="stc-order-page-btn" data-page="'+total_pages+'">'+total_pages+'</a></li>';
            }
            html += '<li class="'+(page >= total_pages ? 'disabled' : '')+'">'
                +  '<a href="#" class="stc-order-page-btn" data-page="'+(page + 1)+'">&raquo;</a></li>';
            html += '</ul>'
                +  '<small style="margin-left:10px;color:#888;">Total: '+total+' items &nbsp;&bull;&nbsp; Page '+page+' of '+total_pages+'</small>'
                +  '</nav>';
            $pg.html(html);
        }

        /* ---- Bulk helpers ---- */
        function stcOrdGetChecked() {
            var items = [];
            $('.stc-ord-check:checked').each(function(){
                items.push({
                    item_id    : $(this).data('item-id'),
                    list_id    : $(this).data('list-id'),
                    req_qty    : $(this).data('req-qty'),
                    item_title : $(this).data('item-title') || '—',
                    req_date   : $(this).data('req-date')   || '—'
                });
            });
            return items;
        }
        function stcOrdUpdateBulkBar() {
            var n = $('.stc-ord-check:checked').length;
            $('#stc-ord-sel-count').text(n);
            if (n > 0) {
                $('#stc-ord-bulk-bar').css('display','flex');
            } else {
                $('#stc-ord-bulk-bar').hide();
                $('#stc-ord-check-all').prop('checked', false);
            }
        }
        function stcOrdDoBulkAction(action, items, reason) {
            var item_ids = [], list_ids = [], req_qtys = [];
            $.each(items, function(i, it){
                item_ids.push(it.item_id);
                list_ids.push(it.list_id);
                req_qtys.push(it.req_qty);
            });
            var label = action === 'approve' ? 'Approving' : (action === 'reject' ? 'Rejecting' : 'Deleting');
            $('#stc-ord-bulk-bar button').prop('disabled', true);
            $.ajax({
                url: 'nemesis/stc_project.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    stc_bulk_order_action: 1,
                    action: action,
                    item_ids: item_ids,
                    list_ids: list_ids,
                    req_qtys: req_qtys,
                    reason: reason || ''
                },
                success: function(res) {
                    $('#stc-ord-bulk-bar button').prop('disabled', false);
                    if (res && res.results) {
                        var r = res.results;
                        var isDone = action === 'approve' ? 'Approved' : (action === 'reject' ? 'Rejected' : 'Deleted');
                        Swal.fire({
                            icon: r.fail > 0 ? 'warning' : 'success',
                            title: isDone + ' Complete',
                            html: '<b>Success:</b> ' + r.success + '&emsp;<b>Failed:</b> ' + r.fail,
                            timer: 3500,
                            timerProgressBar: true
                        });
                    }
                    stcLoadOrderList(stcOrderCurrentPage);
                },
                error: function() {
                    $('#stc-ord-bulk-bar button').prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                }
            });
        }

        $(document).ready(function(){
            stcLoadOrderList(1);

            $('#stc-order-search-btn').on('click', function(){
                stcOrderSearch = $.trim($('#stc-order-search').val());
                stcLoadOrderList(1);
            });

            $('#stc-order-reset-btn').on('click', function(){
                $('#stc-order-search').val('');
                stcOrderSearch = '';
                stcLoadOrderList(1);
            });

            $('#stc-order-search').on('keydown', function(e){
                if (e.which === 13) {
                    e.preventDefault();
                    stcOrderSearch = $.trim($(this).val());
                    stcLoadOrderList(1);
                }
            });

            $('body').delegate('.stc-order-page-btn', 'click', function(e){
                e.preventDefault();
                var pg = parseInt($(this).data('page'), 10);
                if (!isNaN(pg) && pg >= 1) stcLoadOrderList(pg);
            });

            $(document).on('click', '.stc-ord-sortable', function(){
                var col = $(this).data('col');
                if (stcOrderSortCol === col) {
                    stcOrderSortDir = (stcOrderSortDir === 'ASC') ? 'DESC' : 'ASC';
                } else {
                    stcOrderSortCol = col;
                    stcOrderSortDir = 'ASC';
                }
                stcLoadOrderList(1);
            });

            /* Select-all checkbox */
            $(document).on('change', '#stc-ord-check-all', function(){
                var checked = $(this).prop('checked');
                $('.stc-ord-check').prop('checked', checked);
                stcOrdUpdateBulkBar();
            });
            /* Individual checkbox */
            $(document).on('change', '.stc-ord-check', function(){
                var total = $('.stc-ord-check').length;
                var checked = $('.stc-ord-check:checked').length;
                $('#stc-ord-check-all').prop('checked', total > 0 && total === checked);
                stcOrdUpdateBulkBar();
            });

            /* Bulk Approve */
            $('#stc-ord-bulk-approve').on('click', function(){
                var items = stcOrdGetChecked();
                if (!items.length) return;
                Swal.fire({
                    icon: 'question',
                    title: 'Approve ' + items.length + ' Item(s)?',
                    text: 'All selected items will be approved with their full requested quantity.',
                    showCancelButton: true,
                    confirmButtonColor: '#27ae60',
                    confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) stcOrdDoBulkAction('approve', items, '');
                });
            });

            /* Bulk Reject — open modal */
            $('#stc-ord-bulk-reject').on('click', function(){
                var items = stcOrdGetChecked();
                if (!items.length) return;
                $('#stc-ord-bulk-reject-count').text('You are about to reject ' + items.length + ' item(s).');
                $('#stc-ord-bulk-reject-reason').val('');
                $('#stc-ord-bulk-reject-modal').modal('show');
            });
            $('#stc-ord-bulk-reject-confirm').on('click', function(){
                var reason = $.trim($('#stc-ord-bulk-reject-reason').val());
                if (!reason) {
                    Swal.fire({ icon: 'warning', title: 'Reason Required', text: 'Please enter a reason for rejection.' });
                    return;
                }
                var items = stcOrdGetChecked();
                $('#stc-ord-bulk-reject-modal').modal('hide');
                stcOrdDoBulkAction('reject', items, reason);
            });

            /* Bulk Delete */
            $('#stc-ord-bulk-delete').on('click', function(e){
                e.preventDefault();
                var items = stcOrdGetChecked();
                if (!items.length) return;

                // Build item list HTML for the confirmation dialog
                var listHtml = '<div style="max-height:260px;overflow-y:auto;margin-top:8px;">'
                    + '<table style="width:100%;border-collapse:collapse;font-size:13px;">'
                    + '<thead><tr style="background:#f8d7da;">'
                    + '<th style="padding:6px 8px;border:1px solid #f5c6cb;text-align:left;">#</th>'
                    + '<th style="padding:6px 8px;border:1px solid #f5c6cb;text-align:left;">Item Description</th>'
                    + '<th style="padding:6px 8px;border:1px solid #f5c6cb;text-align:left;">Req. Date</th>'
                    + '</tr></thead><tbody>';
                $.each(items, function(i, it){
                    var bg = i % 2 === 0 ? '#fff' : '#fff5f5';
                    listHtml += '<tr style="background:'+bg+';">'
                        + '<td style="padding:5px 8px;border:1px solid #f5c6cb;">'+(i+1)+'</td>'
                        + '<td style="padding:5px 8px;border:1px solid #f5c6cb;font-weight:600;">' + $('<div>').text(it.item_title).html() + '</td>'
                        + '<td style="padding:5px 8px;border:1px solid #f5c6cb;white-space:nowrap;">' + $('<div>').text(it.req_date).html() + '</td>'
                        + '</tr>';
                });
                listHtml += '</tbody></table></div>'
                    + '<p style="margin-top:10px;color:#e74c3c;font-weight:600;font-size:13px;">&#9888; This action cannot be undone.</p>';

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete ' + items.length + ' Item(s)?',
                    html: listHtml,
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete All',
                    cancelButtonText: 'Cancel',
                    width: 560,
                    customClass: { htmlContainer: 'text-left' }
                }).then(function(result) {
                    if (result.isConfirmed) stcOrdDoBulkAction('delete', items, 'Deleted via bulk action.');
                });
            });

            /* Clear selection */
            $('#stc-ord-bulk-clear').on('click', function(){
                $('.stc-ord-check, #stc-ord-check-all').prop('checked', false);
                stcOrdUpdateBulkBar();
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            // supervisor order
            $('body').delegate('.ag-show-grid', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $('#togdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        get_orders_pert:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        $('#togdiv'+odid).html(orders);
                    }
                });
                // alert(odid);
            });

            // change order item status
            $('body').delegate('.setforwardaction', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                var validate_value=$('#setforwardactionvalue'+odid).val();
                // $('#togdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        set_for_validate:1,
                        odid:odid,
                        validate_value:validate_value
                    },
                    dataType: "JSON",
                    success : function(action){
                        Swal.fire({ icon: 'info', title: 'Status Updated', text: action['action'], timer: 2500, timerProgressBar: true });
                        $('#togdiv'+odid).html(action['reaction']);

                    }
                });
                // alert(odid+validate_value);
            });

            // place order
            $('body').delegate('.placeorder', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        place_order:1,
                        odid:odid
                    },
                    success : function(orders){
                        if(orders == "reload"){
                            Swal.fire({ icon: 'warning', title: 'Session Expired', text: 'Please log in again.', timer: 2000, showConfirmButton: false, timerProgressBar: true }).then(function(){ window.location.reload(); });
                        }else if(orders == "no"){
                            Swal.fire({ icon: 'error', title: 'Cannot Place Order', text: "Please check remaining quantity and ensure the item is enabled." });
                        }else{
                            Swal.fire({ icon: 'success', title: 'Order Placed', text: orders, timer: 2500, timerProgressBar: true });
                            $('#togdiv'+odid).toggle(400);
                        }
                    }
                });
            });

            // set to clean
            $('body').delegate('.settoclean', 'click', function(e){
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        clean_requisition:1,
                        odid:odid
                    },
                    success : function(orders){
                            Swal.fire({ icon: 'success', title: 'Done', text: orders, timer: 2500, timerProgressBar: true });
                            $('#togdiv'+odid).toggle(400);
                    }
                });
            });

            /*--------------------------Requistion Section-------------------------*/
            // add to purchase
            $('body').delegate('.add_to_purchase', 'click', function(e){
                e.preventDefault();
                var item_id=$(this).attr("atc-ic");
                var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                var itemreqqty=$('.stc-sup-req-qty'+item_id).val();
                var itemstatus=1;
                if(!((parseFloat(itemqty) > 0) && (parseFloat(itemreqqty) >= parseFloat(itemqty)))){
                    Swal.fire({ icon: 'warning', title: 'Invalid Quantity', text: 'Please enter a valid approved quantity that does not exceed the requested quantity.' });
                    return;
                }
                Swal.fire({
                    icon: 'question',
                    title: 'Confirm Approval',
                    text: 'Are you sure you want to approve this item?',
                    showCancelButton: true,
                    confirmButtonColor: '#3498db',
                    confirmButtonText: '<i class="fas fa-check"></i> Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then(function(result){
                    if(result.isConfirmed){
                        $.ajax({
                            url : "nemesis/stc_project.php",
                            method : "POST",
                            data : {
                                stc_addtopurchase:1,
                                item_id:item_id,
                                itemqty:itemqty,
                                itemstatus:itemstatus
                            },
                            success : function(requisition){
                                if(requisition.trim()=="success"){
                                    Swal.fire({ icon: 'success', title: 'Approved!', text: 'Requisition approved successfully. Procurement has been notified.', timer: 3000, timerProgressBar: true });
                                    $('#stc-req-tr-'+item_id).fadeOut(500);
                                }else{
                                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please check and try again.' });
                                }
                            }
                        });
                    }
                });
            });

            // supervisor requist
            $('body').delegate('.ag-req-show-grid', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $('#togreqdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        get_req_orders_pert:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        $('#togreqdiv'+odid).html(orders);
                    }
                });            
            });

            // add to cart per line items
            $('body').delegate('.add_to_accept_cart', 'click', function(e){
                e.preventDefault();
                // $(this).css('color','red');
                $(this).css('display','none');
                var item_id=$(this).attr("atc-ic");
                var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                var itemstatus=$('.stc-sup-items-status'+item_id).val();
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        go_for_req_sess:1,
                        item_id:item_id,
                        itemqty:itemqty,
                        itemstatus:itemstatus
                    },
                    success : function(requisition){
                        Swal.fire({ icon: 'info', title: 'Cart', text: requisition, timer: 2500, timerProgressBar: true });
                        $('#rem_from_accept_cart'+item_id).css('display','block');
                    }
                });
            });


            // remove from cart per line items
            $('body').delegate('.rem_from_accept_cart', 'click', function(e){
                e.preventDefault();
                $(this).css('display','none');
                var item_id=$(this).attr("operat-ic");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        remove_from_req_sess:1,
                        item_id:item_id
                    },
                    success : function(requisition){
                        // console.log(requisition);
                        // alert(requisition);
                        $('#add_to_accept_cart'+item_id).css('display','block');
                    }
                });
            });

            // placed this requisiton 
            $('body').delegate('.placerequisition', 'click', function(e){
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        place_requisition:1,
                        odid:odid
                    },
                    success : function(orders){
                        if(orders == "reload"){
                            Swal.fire({ icon: 'warning', title: 'Session Expired', text: 'Please log in again.', timer: 2000, showConfirmButton: false, timerProgressBar: true }).then(function(){ window.location.reload(); });
                        }else if(orders == "no"){
                            Swal.fire({ icon: 'error', title: 'Cannot Place Requisition', text: "Please check remaining quantity and ensure the item is enabled." });
                        }else{
                            Swal.fire({ icon: 'success', title: 'Requisition Placed', text: orders, timer: 2500, timerProgressBar: true });
                            $('#togreqdiv'+odid).toggle(400);
                        }
                    }
                });
            });

            // call req list items edit
            $('body').delegate('.edit-req-item', 'click', function(e){
                e.preventDefault();
                var req_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_show:1,
                        req_id:req_id
                    }, 
                    success     : function(response_items){
                        // console.log(response_items);
                        $(".stc-super-own-name-text").val(response_items);
                        $('.stc-super-own-req-id-hidd').val(req_id);
                        $('#stc-sup-requisition-item-edit-modal').modal('show');
                    }
                });
            });

            // open edit modal from item name (order-management table)
            $('body').delegate('.stc-req-item-name-open-edit', 'click', function(e){
                e.preventDefault();
                var $a = $(this);
                var itemId = $a.attr('data-item-id');
                var itemName = $a.attr('data-item-name');
                var priority = $a.attr('data-item-priority');
                if (priority === undefined || priority === '') {
                    priority = 1;
                }
                $('.stc-super-own-name-text').val(itemName);
                $('.stc-super-own-req-id-hidd').val(itemId);
                $('.stc-sup-priority').val(String(priority));
                $('#stc-sup-requisition-item-edit-modal').modal('show');
            });

            var req_id=0;
            var list_id=0;
            // call req list items edit
            $('body').delegate('.remove_from_purchase', 'click', function(e){
                e.preventDefault();
                var $that = $(this);
                Swal.fire({
                    icon: 'warning',
                    title: 'Reject This Item?',
                    text: 'Are you sure you want to reject this item? A reason will be required.',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    confirmButtonText: '<i class="fas fa-ban"></i> Yes, Reject',
                    cancelButtonText: 'Cancel'
                }).then(function(result){
                    if(result.isConfirmed){
                        req_id = $that.attr("operat-ic");
                        list_id = $that.attr("list-id");
                        $('.rejectionmodalbtn').click();
                    }
                });
            });
            $('body').delegate('.remove_from_purchase_finally', 'click', function(e){
                e.preventDefault();
                var reason=$('.reason-text').val();
                if(reason.trim()==""){
                    Swal.fire({ icon: 'warning', title: 'Reason Required', text: 'Please enter a reason for rejection.' });
                    return false;
                }
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_delete:1,
                        req_id:req_id,
                        list_id:list_id,
                        reason:reason
                    }, 
                    success     : function(response_items){
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: 'Item has been rejected successfully.', timer: 2000, timerProgressBar: true }).then(function(){ window.location.reload(); });
                    }
                });
            });

            // update  req list items edit
            $('body').delegate('.stc-super-own-edit-btn', 'click', function(e){
                e.preventDefault();
                var req_item_id=$('.stc-super-own-req-id-hidd').val();
                var req_item_name=$('.stc-super-own-name-text').val();
                var req_item_priority=$('.stc-sup-priority').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_update:1,
                        req_item_id:req_item_id,
                        req_item_name:req_item_name,
                        req_item_priority:req_item_priority
                    }, 
                    success     : function(response_items){
                        response_items = response_items.trim();
                        if(response_items == "Item Updated Successfully."){
                            Swal.fire({ icon: 'success', title: 'Updated!', text: response_items, timer: 2000, timerProgressBar: true }).then(function(){ window.location.reload(); });
                        }else{
                            Swal.fire({ icon: 'error', title: 'Update Failed', text: response_items });
                        }
                    }
                });
            });
        });
    </script>
    <a href="#" data-toggle="modal" data-target="#stc-sup-requisition-rejection-modal" class="rejectionmodalbtn" style="display: none;"></a>
</body>
</html>
<div class="modal fade" id="stc-sup-requisition-item-edit-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Requisition Item Change</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <h4>Item Name :</h4>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                   <input type="text" class="form-control stc-super-own-name-text">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6" style="display:none;">
                    <h4>Item Priority :</h4>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6" style="display:none;">
                <select class="form-control stc-sup-priority">
                    <option value="1">Normal</option>
                    <option value="2">Urgent</option>
                </select>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <input type="hidden" class="stc-super-own-req-id-hidd">
                    <button class="btn btn-success stc-super-own-edit-btn" href="#">Save</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="stc-ord-bulk-reject-modal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-ban"></i> Bulk Reject — Reason</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="stc-ord-bulk-reject-count" style="font-weight:600;color:#c0392b;"></p>
                <div class="form-group">
                    <label>Reason for Rejection <span style="color:red;">*</span></label>
                    <textarea id="stc-ord-bulk-reject-reason" class="form-control" rows="4" placeholder="Enter reason for rejecting selected items..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="stc-ord-bulk-reject-confirm"><i class="fas fa-ban"></i> Confirm Reject</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="stc-sup-requisition-rejection-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reason for Rejection</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h4>Reason :</h4>
                    <textarea class="form-control reason-text" placeholder="Enter reason for rejection." rows="5"></textarea></br>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <input type="hidden" class="stc-super-own-req-id-hidd">
                    <button class="btn btn-success remove_from_purchase_finally" href="Javascript:void(0)">Send</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>