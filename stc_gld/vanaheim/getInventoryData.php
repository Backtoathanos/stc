<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get search query from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Modify query to filter by product name if a search query is provided
$query = "SELECT DISTINCT P.stc_product_id, P.stc_product_name, P.stc_product_unit, P.stc_product_image, ROUND(i.stc_item_inventory_pd_qty, 2) AS stc_item_inventory_pd_qty, P.stc_product_gst, GI.stc_product_grn_items_rate, ROUND(GI.stc_product_grn_items_rate * (1 + P.stc_product_gst / 100), 2) AS rate_including_gst FROM stc_item_inventory i LEFT JOIN stc_product P ON i.stc_item_inventory_pd_id = P.stc_product_id LEFT JOIN stc_product_grn_items GI ON i.stc_item_inventory_pd_id = GI.stc_product_grn_items_product_id WHERE i.stc_item_inventory_pd_qty > 0 AND GI.stc_product_grn_items_rate = (SELECT `stc_product_grn_items_rate` FROM stc_product_grn_items WHERE `stc_product_grn_items_product_id` = i.stc_item_inventory_pd_id ORDER BY `stc_product_grn_items_id` DESC LIMIT 1 )";

// If search query is not empty, add a filter for the product name
if ($search !== '') {
    $query .= " AND P.stc_product_name LIKE '%$search%'";
}

$query .= " ORDER BY P.stc_product_name ASC";

$result = mysqli_query($con, $query);

$data = [];

while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode($data);
?>
