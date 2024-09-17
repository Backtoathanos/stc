<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Include your database connection
include "../../MCU/db.php";

// Fetch data from your inventory table
$query = "SELECT P.stc_product_id, P.stc_product_name, P.stc_product_unit, i.stc_item_inventory_pd_qty FROM stc_item_inventory i LEFT JOIN stc_product P ON i.stc_item_inventory_pd_id=P.stc_product_id ORDER BY P.stc_product_name ASC";
$result = mysqli_query($con, $query);

$data = [];

while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode($data);

?>