<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get query parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// Base query for products
$cquery = "
    SELECT DISTINCT
        P.stc_product_id, 
        P.stc_product_name, 
        S.stc_sub_cat_name, 
        P.stc_product_brand_id, 
        P.stc_product_unit, 
        P.stc_product_image, 
        P.stc_product_gst,
        P.stc_product_sale_percentage,
        B.stc_brand_title
    FROM stc_product P
    LEFT JOIN stc_sub_category S ON P.stc_product_sub_cat_id = S.stc_sub_cat_id
    LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id 
    WHERE P.stc_product_avail = 1 
    LIMIT $offset, $limit
";

// Apply search filter
if ($search !== '') {
    $cquery .= " AND (P.stc_product_id='$search' OR P.stc_product_name LIKE '%$search%' 
                    OR P.stc_product_id IN (
                        SELECT stc_purchase_product_adhoc_productid 
                        FROM stc_purchase_product_adhoc 
                        WHERE stc_purchase_product_adhoc_status = 1 
                        AND stc_purchase_product_adhoc_rackid IN (
                            SELECT stc_rack_id FROM stc_rack WHERE stc_rack_name LIKE '%$search%'
                        )
                    )
                )";
}

$totalQuery = "SELECT COUNT(*) AS total FROM ($cquery) AS count_table"; // Count total rows
$totalResult = mysqli_query($con, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult)['total'];

// Apply pagination
$cquery .= " ORDER BY P.stc_product_name ASC LIMIT $limit OFFSET $offset";

$result = mysqli_query($con, $cquery);

$data = [];
foreach($result as $row){
    $product_id = $row['stc_product_id'];
    $brand_name = $row['stc_brand_title'];
    
    // Modify product name
    $row['stc_product_name'] = $row['stc_product_name'] . ' ' . $brand_name;
    $row['stc_product_name'] = $row['stc_sub_cat_name'] != "OTHERS" ? $row['stc_sub_cat_name'] . " " . $row['stc_product_name'] : $row['stc_product_name'];

    // Fetch inventory quantity
    $row['stc_item_inventory_pd_qty'] = 0;
    $query = mysqli_query($con, "SELECT SUM(`stc_purchase_product_adhoc_qty`) AS stc_item_inventory_pd_qty 
                                 FROM `stc_purchase_product_adhoc` 
                                 WHERE stc_purchase_product_adhoc_status=1 
                                 AND `stc_purchase_product_adhoc_productid` = $product_id");
    if ($query && mysqli_num_rows($query) > 0) {
        $row1 = mysqli_fetch_assoc($query);
        $row['stc_item_inventory_pd_qty'] = $row1['stc_item_inventory_pd_qty'] ?? 0;
    }

    // Fetch rates including GST & percentage
    $row['rate_including_gst'] = 0;
    $row['rate_including_percentage'] = 0;
    $row['stc_rack_name'] = '';
    $query = mysqli_query($con, "SELECT `stc_rack_name` FROM `stc_purchase_product_adhoc` AS ppa LEFT JOIN `stc_rack` ON ppa.`stc_purchase_product_adhoc_rackid`=`stc_rack_id`WHERE ppa.stc_purchase_product_adhoc_status=1 AND ppa.`stc_purchase_product_adhoc_productid` = $product_id ORDER BY ppa.`stc_purchase_product_adhoc_id` DESC LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row1 = mysqli_fetch_assoc($query);
        $row['stc_rack_name'] = $row1['stc_rack_name'];
    }
    $query = mysqli_query($con, "SELECT `stc_purchase_product_adhoc_rate`, ROUND(stc_purchase_product_adhoc_rate * (1 + ".$row['stc_product_sale_percentage']." / 100), 2) AS rate_including_percentage  FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid`=$product_id ORDER BY `stc_purchase_product_adhoc_id` DESC LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row1 = mysqli_fetch_assoc($query);
        $row['rate_including_percentage'] = number_format($row1['rate_including_percentage'], 2) ?? 0;
        $row['rate_including_gst'] = number_format($row1['stc_purchase_product_adhoc_rate'], 2) ?? 0;
    }

    if($row['rate_including_gst']==0){
        $query = mysqli_query($con, "SELECT `stc_product_grn_items_rate`, ROUND(stc_product_grn_items_rate * (1 + ".$row['stc_product_sale_percentage']." / 100), 2) AS rate_including_percentage  FROM `stc_product_grn_items` WHERE `stc_product_grn_items_product_id`=$product_id ORDER BY `stc_product_grn_items_id` DESC LIMIT 1");
        if ($query && mysqli_num_rows($query) > 0) {
            $row1 = mysqli_fetch_assoc($query);
            $row['rate_including_percentage'] = number_format($row1['rate_including_percentage'], 2) ?? 0;
            $row['rate_including_gst'] = number_format($row1['stc_product_grn_items_rate'], 2) ?? 0;
        }
    }
    if($row['rate_including_gst']==0){$row['rate_including_gst'] = "0.00";}
        if($row['rate_including_percentage']==0){$row['rate_including_percentage'] = "0.00";}

    // Calculate remaining quantity
    $query = mysqli_query($con, "SELECT SUM(`qty`) AS total_qty FROM `gld_challan` WHERE `product_id` = $product_id");
    $result = mysqli_fetch_assoc($query);
    $gldQty = $result['total_qty'] ?? 0;
    
    $directqty = 0;
    $sql_qry = mysqli_query($con, "
        SELECT SUM(srec.stc_cust_super_requisition_list_items_rec_recqty) AS total_qty
        FROM stc_purchase_product_adhoc spa
        JOIN stc_cust_super_requisition_list_items_rec srec ON srec.stc_cust_super_requisition_list_items_rec_list_poaid = spa.stc_purchase_product_adhoc_id
        WHERE spa.stc_purchase_product_adhoc_productid = $product_id 
        AND spa.stc_purchase_product_adhoc_status = 1
    ");
    if ($sql_qry && mysqli_num_rows($sql_qry) > 0) {
        $row1 = mysqli_fetch_assoc($sql_qry);
        $directqty = $row1['total_qty'] ?? 0;
    }

    $remainingqty = $row['stc_item_inventory_pd_qty'] - ($gldQty + $directqty);
    $row['stc_item_inventory_pd_qty'] = number_format(max($remainingqty, 0), 2);

    $data[] = $row;
}

// Return data as JSON with pagination metadata
echo json_encode([
    'records' => $data,
    'total' => $totalRow,
    'page' => $page,
    'limit' => $limit
]);
?>
