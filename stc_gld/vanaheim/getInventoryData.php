<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get parameters with defaults
$search = mysqli_real_escape_string($con, $_GET['search'] ?? '');
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10);
$location_stc = $_GET['location'];
$offset = ($page - 1) * $limit;

// Base query
$cquery = "SELECT DISTINCT P.stc_product_id, P.stc_product_name, S.stc_sub_cat_name,   P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image,   P.stc_product_gst, P.stc_product_sale_percentage, B.stc_brand_title FROM stc_product P INNER JOIN stc_sub_category S ON P.stc_product_sub_cat_id = S.stc_sub_cat_id LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id  WHERE P.stc_product_avail = 1";

if ($search) $cquery .= " AND (P.stc_product_id='$search' OR P.stc_product_name LIKE '%$search%' OR P.stc_product_desc LIKE '%$search%')";

// Get total count
$totalRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM ($cquery) AS count_table"))['total'];

// Apply pagination and execute
$result = mysqli_query($con, $cquery . " ORDER BY P.stc_product_name ASC LIMIT $limit OFFSET $offset");

$data = [];
foreach($result as $row) {
    $product_id = $row['stc_product_id'];
    
    // Format product name
    $row['stc_product_name'] = ($row['stc_sub_cat_name'] != "OTHERS" ? $row['stc_sub_cat_name'] . " " : "") . 
                              $row['stc_product_name'] . ' ' . $row['stc_brand_title'];
    
    // Get inventory quantity
    $row['stc_item_inventory_pd_qty'] = getInventoryQty($con, $product_id);
    
    
    $row = getRackAndRates($con, $row, $product_id, $location_stc);
    
    // Calculate remaining quantity
    $row = calculateRemainingQty($con, $row, $product_id, $location_stc);
    
    $data[] = $row;
}

echo json_encode(['records' => $data, 'total' => $totalRow, 'page' => $page, 'limit' => $limit]);

// Helper functions
function getInventoryQty($con, $product_id) {
    $query = mysqli_query($con, "SELECT SUM(`stc_purchase_product_adhoc_qty`) AS qty FROM `stc_purchase_product_adhoc` WHERE stc_purchase_product_adhoc_status=1 AND `stc_purchase_product_adhoc_productid` = $product_id");
    return ($query && mysqli_num_rows($query)) ? mysqli_fetch_assoc($query)['qty'] ?? 0 : 0;
}

function getRackAndRates($con, $row, $product_id, $location_stc) {
    // Get rack name
    $rack_query = mysqli_query($con, "SELECT `stc_rack_name` FROM `stc_purchase_product_adhoc` AS ppa LEFT JOIN `stc_rack` ON ppa.`stc_purchase_product_adhoc_rackid`=`stc_rack_id`WHERE ppa.stc_purchase_product_adhoc_status=1 AND ppa.`stc_purchase_product_adhoc_productid` = $product_id ORDER BY ppa.`stc_purchase_product_adhoc_id` DESC LIMIT 1");
    $row['stc_rack_name'] = ($rack_query && mysqli_num_rows($rack_query)) ? mysqli_fetch_assoc($rack_query)['stc_rack_name'] : '';
    // Get rack name and rates only for Root
    if ($location_stc != "Root") {
        $rack_query = mysqli_query($con, "SELECT `stc_rack_name` FROM `stc_shop` AS S INNER JOIN `stc_purchase_product_adhoc` ON `stc_purchase_product_adhoc_id`=`adhoc_id` LEFT JOIN `stc_rack` R ON S.`rack_id`=`stc_rack_id`WHERE S.`shopname` = '$location_stc' AND `stc_purchase_product_adhoc_productid` = {$product_id} ORDER BY S.`id` DESC LIMIT 1");
        if ($rack_query && mysqli_num_rows($rack_query)) {
            $row['stc_rack_name'] = mysqli_fetch_assoc($rack_query)['stc_rack_name'];
        }
    }
    // Get rates
    $rate_query = mysqli_query($con, "SELECT `stc_purchase_product_adhoc_rate`,  ROUND(stc_purchase_product_adhoc_rate * (1 + ".$row['stc_product_sale_percentage']." / 100), 2) AS rate_with_percent   FROM `stc_purchase_product_adhoc`  WHERE `stc_purchase_product_adhoc_productid`=$product_id  ORDER BY `stc_purchase_product_adhoc_id` DESC LIMIT 1");
    
    if ($rate_query && mysqli_num_rows($rate_query)) {
        $rate = mysqli_fetch_assoc($rate_query);
        $row['rate_including_percentage'] = number_format($rate['rate_with_percent'] ?? 0, 2);
        $row['rate_including_gst'] = number_format($rate['stc_purchase_product_adhoc_rate'] ?? 0, 2);
    } else {
        $grn_query = mysqli_query($con, "SELECT `stc_product_grn_items_rate`,  ROUND(stc_product_grn_items_rate * (1 + ".$row['stc_product_sale_percentage']." / 100), 2) AS rate_with_percent   FROM `stc_product_grn_items`  WHERE `stc_product_grn_items_product_id`=$product_id  ORDER BY `stc_product_grn_items_id` DESC LIMIT 1");
        if ($grn_query && mysqli_num_rows($grn_query)) {
            $grn_rate = mysqli_fetch_assoc($grn_query);
            $row['rate_including_percentage'] = number_format($grn_rate['rate_with_percent'] ?? 0, 2);
            $row['rate_including_gst'] = number_format($grn_rate['stc_product_grn_items_rate'] ?? 0, 2);
        } else {
            $row['rate_including_gst'] = "0.00";
            $row['rate_including_percentage'] = "0.00";
        }
    }
    return $row;
}

function calculateRemainingQty($con, $row, $product_id, $location) {
    $gldQty = getGLDQty($con, $product_id, $location);
    $directqty = getDirectQty($con, $product_id);
    
    if ($location == "Root") {
        $remaining = $row['stc_item_inventory_pd_qty'] - ($gldQty + $directqty);
        $row['stc_item_inventory_pd_qty'] = max($remaining, 0);
    } else {
        $qty = getLocationQty($con, $product_id, $location);
        if ($qty > 0) {
            $qtydispatch = getDispatchedQty($con, $product_id, $location);
            $row['stc_item_inventory_pd_qty'] = $qty - $qtydispatch;
        } else {
            $row['stc_item_inventory_pd_qty'] = 0;
        }
    }
    return $row;
}

function getGLDQty($con, $product_id, $location) {
    $query = $location == "Root" ? "SELECT SUM(`qty`) AS qty FROM `gld_challan` WHERE `product_id` = $product_id" : "SELECT SUM(`qty`) AS qty FROM `gld_challan` INNER JOIN `stc_trading_user` ON `stc_trading_user_id`=`created_by` WHERE `product_id` = $product_id AND `stc_trading_user_location` = '$location'";
    $result = mysqli_fetch_assoc(mysqli_query($con, $query));
    return $result['qty'] ?? 0;
}

function getDirectQty($con, $product_id) {
    $query = mysqli_query($con, "SELECT SUM(srec.stc_cust_super_requisition_list_items_rec_recqty) AS qty FROM stc_purchase_product_adhoc spa JOIN stc_cust_super_requisition_list_items_rec srec ON srec.stc_cust_super_requisition_list_items_rec_list_poaid = spa.stc_purchase_product_adhoc_id WHERE spa.stc_purchase_product_adhoc_productid = $product_id AND spa.stc_purchase_product_adhoc_status = 1");
    return ($query && mysqli_num_rows($query)) ? mysqli_fetch_assoc($query)['qty'] ?? 0 : 0;
}

function getLocationQty($con, $product_id, $location) {
    $qty = 0;
    $query = mysqli_query($con, "SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_qty` FROM stc_purchase_product_adhoc spa WHERE spa.stc_purchase_product_adhoc_productid = $product_id AND spa.stc_purchase_product_adhoc_status = 1");
    if ($query && mysqli_num_rows($query)) {
        foreach($query as $item) {
            $shop_query = mysqli_query($con, "SELECT `shopname`, `qty`, `rack_id` FROM stc_shop WHERE adhoc_id = {$item['stc_purchase_product_adhoc_id']}");
            if ($shop_query && mysqli_num_rows($shop_query)) {
                foreach($shop_query as $shop) {
                    if ($location == $shop['shopname']) {
                        $qty += $shop['qty'];
                        // Add lot info (only once per product)
                        if (!isset($row['lot'])) {
                            $row['lot'] = [
                                'adhoc_id' => $item['stc_purchase_product_adhoc_id'],
                                'qty' => $item['stc_purchase_product_adhoc_qty'],
                                'shopname' => $shop['shopname'],
                                'rack_id' => $shop['rack_id']
                            ];
                        }
                    }
                }
            }
        }
    }
    return $qty;
}

function getDispatchedQty($con, $product_id, $location) {
    $qty = 0;
    $query = mysqli_query($con, "SELECT `qty`, `stc_trading_user_location` FROM `gld_challan` INNER JOIN `stc_trading_user` ON `stc_trading_user_id`=`created_by` WHERE `product_id` = $product_id");
    if ($query && mysqli_num_rows($query)) {
        foreach($query as $item) {
            if ($item['stc_trading_user_location'] == $location) {
                $qty += $item['qty'];
            }
        }
    }
    return $qty;
}
?>