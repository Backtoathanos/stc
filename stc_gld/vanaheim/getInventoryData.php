<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

// Get search query from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Modify query to filter by product name if a search query is provided
$cquery = "SELECT ppa.stc_purchase_product_adhoc_id, P.stc_product_id, P.stc_product_name, stc_sub_cat_name, P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image, R.stc_rack_name, sum(ppa.stc_purchase_product_adhoc_qty) as stc_item_inventory_pd_qty, P.stc_product_gst, ppa.stc_purchase_product_adhoc_rate as rate_including_gst, ROUND(ppa.stc_purchase_product_adhoc_rate * (1 + P.stc_product_sale_percentage / 100), 2) AS rate_including_percentage FROM stc_purchase_product_adhoc ppa LEFT JOIN stc_product P ON ppa.stc_purchase_product_adhoc_productid = P.stc_product_id LEFT JOIN stc_rack R ON ppa.stc_purchase_product_adhoc_rackid = R.stc_rack_id LEFT JOIN stc_sub_category ON stc_product_sub_cat_id = stc_sub_cat_id WHERE ppa.stc_purchase_product_adhoc_qty > 0 AND ppa.stc_purchase_product_adhoc_status=1 AND ppa.stc_purchase_product_adhoc_productid<>0";

// If search query is not empty, add a filter for the product name
if ($search !== '') {
    $cquery .= " AND (P.stc_product_name LIKE '%$search%' OR R.stc_rack_name LIKE '%$search%') ";
}

$cquery .= " GROUP BY P.stc_product_id ORDER BY P.stc_product_name ASC";

$result = mysqli_query($con, $cquery);

$data = [];
foreach($result as $key => $row){
    
    $row['rate_including_gst'] = number_format($row['rate_including_gst'], 2);

    $brand_name='';
    if($row['stc_product_brand_id']!=0){
        $query = mysqli_query($con, "SELECT stc_brand_title FROM `stc_brand` WHERE `stc_brand_id` = " . $row['stc_product_brand_id']);
        if(mysqli_num_rows($query)>0){
            $brandData = mysqli_fetch_assoc($query);
            $brand_name = $brandData['stc_brand_title'];
        }
        $row['stc_product_name'] = $row['stc_product_name'] . ' ' . $brand_name;
    }
    $row['stc_product_name'] = $row['stc_sub_cat_name']!="OTHERS" ? $row['stc_sub_cat_name'] . " " .$row['stc_product_name'] : $row['stc_product_name'];

    $query = mysqli_query($con, "SELECT SUM(`qty`) AS total_qty FROM `gld_challan` WHERE `product_id` = " . $row['stc_product_id']);
    $result=mysqli_fetch_assoc($query);
    $gldQty=$result['total_qty'];
    $directqty=0;
    $sql_qry=mysqli_query($con, "
        SELECT `stc_purchase_product_adhoc_id` 
        FROM `stc_purchase_product_adhoc` 
        WHERE `stc_purchase_product_adhoc_productid`='".$row['stc_product_id']."'
    ");
    if(mysqli_num_rows($sql_qry)>0){
        foreach($sql_qry as $sql_row){
            $sql_qry2=mysqli_query($con, "
                SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
                FROM `stc_cust_super_requisition_list_items_rec` 
                WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$sql_row['stc_purchase_product_adhoc_id']."'
            ");
            if(mysqli_num_rows($sql_qry2)>0){
                foreach($sql_qry2 as $sql_row2){
                    $directqty+=$sql_row2['stc_cust_super_requisition_list_items_rec_recqty'];
                }
            }
        }
    }
    $remainingqty=$row['stc_item_inventory_pd_qty'] - ($gldQty + $directqty);
    $row['stc_item_inventory_pd_qty'] = number_format($remainingqty, 2);
    // Remove row if remaining quantity is 0 or less
    if ($remainingqty >0) {
        $data[] = $row; // Add the row to the data array
    } else {
        unset($data[$key]); // Remove the row from the data array
    }
}

// Return data as JSON
echo json_encode($cquery);
?>
