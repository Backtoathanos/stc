<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get search query from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Modify query to filter by product name if a search query is provided
$query = "SELECT P.stc_product_id, P.stc_product_name, P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image, R.stc_rack_name, ppa.stc_purchase_product_adhoc_qty as stc_item_inventory_pd_qty, P.stc_product_gst, ppa.stc_purchase_product_adhoc_rate as rate_including_gst, ROUND(ppa.stc_purchase_product_adhoc_rate * (1 + P.stc_product_sale_percentage / 100), 2) AS rate_including_percentage FROM stc_purchase_product_adhoc ppa LEFT JOIN stc_product P ON ppa.stc_purchase_product_adhoc_productid = P.stc_product_id LEFT JOIN stc_rack R ON ppa.stc_purchase_product_adhoc_rackid = R.stc_rack_id WHERE ppa.stc_purchase_product_adhoc_qty > 0 AND ppa.stc_purchase_product_adhoc_status=1 AND ppa.stc_purchase_product_adhoc_productid<>0";

// If search query is not empty, add a filter for the product name
if ($search !== '') {
    $query .= " AND (P.stc_product_name LIKE '%$search%' OR R.stc_rack_name LIKE '%$search%')";
}

$query .= " GROUP BY P.stc_product_id ORDER BY P.stc_product_name ASC";

$result = mysqli_query($con, $query);

$data = [];
foreach($result as $key => $row){
// while ($row = mysqli_fetch_assoc($result)) {
    $query = mysqli_query($con, "SELECT SUM(`qty`) AS total_qty FROM `gld_challan` WHERE `product_id` = " . $row['stc_product_id']);
    $totalQty=0;
    if(mysqli_num_rows($query)>0){
        $qtyData = mysqli_fetch_assoc($query);
        $totalQty = $qtyData['total_qty'];
    }
    // Calculate remaining quantity
    $remainingQty = $row['stc_item_inventory_pd_qty'] - $totalQty;

    // Update stc_item_inventory_pd_qty in the result array
    $row['stc_item_inventory_pd_qty'] = number_format($remainingQty, 2);
    $row['rate_including_gst'] = number_format($row['rate_including_gst'], 2);

    // Remove row if remaining quantity is 0 or less
    if ($remainingQty >0) {
        $data[] = $row; // Add the row to the data array
    } else {
        unset($data[$key]); // Remove the row from the data array
    }
}

// Return data as JSON
echo json_encode($data);
?>
