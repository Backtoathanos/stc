<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get search query from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Modify query to filter by product name if a search query is provided
$query = "SELECT DISTINCT P.stc_product_id, P.stc_product_name, P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image, ROUND(i.stc_item_inventory_pd_qty, 2) AS stc_item_inventory_pd_qty, P.stc_product_gst, GI.stc_product_grn_items_rate, ROUND(GI.stc_product_grn_items_rate * (1 + P.stc_product_gst / 100), 2) AS rate_including_gst FROM stc_item_inventory i LEFT JOIN stc_product P ON i.stc_item_inventory_pd_id = P.stc_product_id LEFT JOIN stc_product_grn_items GI ON i.stc_item_inventory_pd_id = GI.stc_product_grn_items_product_id WHERE i.stc_item_inventory_pd_qty > 0 AND GI.stc_product_grn_items_rate = (SELECT `stc_product_grn_items_rate` FROM stc_product_grn_items WHERE `stc_product_grn_items_product_id` = i.stc_item_inventory_pd_id ORDER BY `stc_product_grn_items_id` DESC LIMIT 1 )";

// If search query is not empty, add a filter for the product name
if ($search !== '') {
    $query .= " AND P.stc_product_name LIKE '%$search%'";
}

$query .= " ORDER BY P.stc_product_name ASC";

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

    $brand_name='';
    if($row['stc_product_brand_id']!=0){
        $query = mysqli_query($con, "SELECT stc_brand_title FROM `stc_brand` WHERE `stc_brand_id` = " . $row['stc_product_brand_id']);

        if(mysqli_num_rows($query)>0){
            $brandData = mysqli_fetch_assoc($query);
            $brand_name = $brandData['stc_brand_title'];
        }
        $row['stc_product_name'] = $row['stc_product_name'] . ' ' . $brand_name;
    }

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
