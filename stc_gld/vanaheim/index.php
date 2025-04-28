<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");
include "../../MCU/obdb.php";  // Include the database connectivity file

// Initialize the database connection
$db = new tesseract();
$conn = $db->stc_dbs;

// Handle different API actions using query parameters or POST data
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getCustomers':
        getCustomers($conn);
        break;

    case 'addCustomer':
        addCustomer($conn);
        break;

    case 'getAgents':
        getAgents($conn);
        break;

    case 'getChallan':
        getChallan($conn);
        break;

    case 'getChallaned':
        getChallaned($conn);
        break;

    case 'updateChallanStatus':
        updateChallanStatus($conn);
        break;

    case 'updateChallanBillNo':
        updateChallanBillNo($conn);
        break;
    
    case 'getDistinctChallanNos':
        getDistinctChallanNos($conn);
        break;
    
    case 'getDistinctBillNos':
        getDistinctBillNos($conn);
        break;
        
    case 'getChallanDetails':
        getChallanDetails($conn);
        break;

    case 'addPayment':
        addPayment($conn);
        break;

    case 'deleteChallan':
        deleteChallan($conn);
        break;

    case 'getProductDetails':
        getProductDetails($conn);
        break;

    case 'getRequisitions':
        getRequisitions($conn);
        break;

    case 'setChallanRequisition':
        setChallanRequisition($conn);
        break;

    default:
        echo json_encode(['error' => 'Invalid API action', 'fault' => $_GET['action']]);
        break;
}

// Function to fetch customers from the database
function getCustomers($conn) {
    $query = "SELECT gld_customer_id, gld_customer_cont_no, gld_customer_email, gld_customer_title FROM gld_customer order by `gld_customer_cont_no` asc";
    $result = $conn->query($query);

    $customers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }

    echo json_encode($customers);
}

// Function to fetch customers from the database
function getAgents($conn) {
    $query = "SELECT `stc_own_agents_id`, `stc_own_agents_name` FROM `stc_own_agents` ORDER BY `stc_own_agents_name` ASC";
    $result = $conn->query($query);

    $agents = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $agents[] = $row;
        }
    }

    echo json_encode($agents);
}

// Function to add a new customer and associate them with a product
function addCustomer($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $customerId = $data['id'];
    $customerName = $data['name'];
    $customerContact = $data['contact'];
    $customerEmail = $data['email'];
    $customerAddress = $data['address'];
    $productId = $data['product_id'];
    $requisition = $data['requisition'];
    $quantity = $data['quantity'];
    $rate = $data['rate'];
    $discount = $data['discount'];
    $userId=$data['userId'];
    $agentId=$data['agentId'];
    if($productId){

        // Insert new customer if no existing customer is selected
        if (!$customerId) {
            $query = "INSERT INTO gld_customer (gld_customer_title, gld_customer_cont_no, gld_customer_email, gld_customer_city_id, gld_customer_state_id, gld_customer_address) VALUES ('$customerName', '$customerContact', '$customerEmail', '65', '16', '$customerAddress')";
            $conn->query($query);
            $customerId = $conn->insert_id;
        }
        $date = date('Y-m-d H:i:s');
        $adhoc_id=0;
        // Link customer to the product and set the quantity
        $query = $conn->query("SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_qty` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid`='$productId' AND `stc_purchase_product_adhoc_status`=1 ORDER BY `stc_purchase_product_adhoc_id` ASC");
        while ($row = $query->fetch_assoc()) {
            $delivered=0;
            $sql_qry=$conn->query("
                SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
                FROM `stc_cust_super_requisition_list_items_rec` 
                WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$row['stc_purchase_product_adhoc_id']."'
            ");
            if(mysqli_num_rows($sql_qry)>0){
                foreach($sql_qry as $sql_row){
                    $delivered+=$sql_row['stc_cust_super_requisition_list_items_rec_recqty'];
                }
            }
            $deliveredgld=0;
            $sql_qry=$conn->query("
                SELECT `qty` FROM `gld_challan` WHERE `adhoc_id`='".$row['stc_purchase_product_adhoc_id']."'
            ");
            if(mysqli_num_rows($sql_qry)>0){
                foreach($sql_qry as $sql_row){
                    $deliveredgld+=$sql_row['qty'];
                }
            }
            $stock=$row['stc_purchase_product_adhoc_qty'] - ($delivered + $deliveredgld);
            if ($stock >= $quantity) {
                $adhoc_id = $row['stc_purchase_product_adhoc_id'];
                break;
            }
        }
        if($adhoc_id>0){
            $productQuery = "INSERT INTO gld_challan (cust_id, requisition_id, adhoc_id, product_id, qty, rate, discount, agent_id, created_date, created_by) VALUES ('$customerId', '$requisition', '$adhoc_id', '$productId', '$quantity', '$rate', '$discount', '$agentId', '$date', '$userId')";
            if ($conn->query($productQuery)) {
                echo json_encode(['success' => true, 'message' => 'Challan created successfully']);
            } else {
                echo json_encode(['error' => 'Failed to create Challan']);
            }
        }else {
            echo json_encode(['error' => 'Out of Stock.']);
        }
    }else{
        echo json_encode(['error' => 'Invalid product ID']);
    }
}

// Function to fetch customers from the database
function getChallan($conn) {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $query = "SELECT GC.id, COALESCE(CONCAT(stc_product_name, ' (', stc_brand_title, ')'), stc_product_name) AS stc_product_name, gld_customer_title, requisition_id, challan_number, ROUND(qty, 2) AS qty, ROUND(rate, 2) AS rate, ROUND(discount, 2) AS discount, ROUND(paid_amount, 2) AS paid_amount, payment_status, status, created_date, stc_trading_user_name FROM gld_challan GC LEFT JOIN stc_product ON GC.product_id = stc_product_id LEFT JOIN gld_customer ON GC.cust_id = gld_customer_id LEFT JOIN stc_trading_user ON GC.created_by = stc_trading_user_id LEFT JOIN stc_brand ON stc_product.stc_product_brand_id = stc_brand.stc_brand_id WHERE (challan_number LIKE '%$search%' OR stc_product_name LIKE '%$search%' OR gld_customer_title LIKE '%$search%' OR payment_status LIKE '%$search%') AND status=0 ORDER BY TIMESTAMP(`created_date`) DESC";

    $result = $conn->query($query);

    $challanData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $challanData[] = $row;
        }
    }
    echo json_encode($challanData);
}

// Function to fetch customers from the database
function getChallaned($conn) {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $query = "SELECT GC.id, COALESCE(CONCAT(stc_product_name, ' (', stc_brand_title, ')'), stc_product_name) AS stc_product_name, bill_number, challan_number, gld_customer_title, requisition_id, challan_number, ROUND(qty, 2) AS qty, stc_product_unit, ROUND(rate, 2) AS rate, ROUND(discount, 2) AS discount, ROUND(paid_amount, 2) AS paid_amount, payment_status, status, created_date, stc_trading_user_name FROM gld_challan GC LEFT JOIN stc_product ON GC.product_id = stc_product_id LEFT JOIN gld_customer ON GC.cust_id = gld_customer_id LEFT JOIN stc_trading_user ON GC.created_by = stc_trading_user_id LEFT JOIN stc_brand ON stc_product.stc_product_brand_id = stc_brand.stc_brand_id WHERE (challan_number LIKE '%$search%' OR stc_product_name LIKE '%$search%' OR gld_customer_title LIKE '%$search%' OR payment_status LIKE '%$search%') AND (status=1 OR status=2 OR status=3) ORDER BY TIMESTAMP(`created_date`) DESC";

    $result = $conn->query($query);

    $challanData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['created_date']=date('d-m-Y H:i a', strtotime($row['created_date']));
            $challanData[] = $row;
        }
    }
    echo json_encode($challanData);
}

// update challan status to print preview
function updateChallanStatus($conn) {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get the array of IDs from the request
    $ids = $data['ids'];
    
    // Check if we have valid IDs
    if (!empty($ids) && is_array($ids)) {
        // Escape and format IDs for SQL IN clause
        $escapedIds = implode(',', array_map('intval', $ids)); // Use intval to ensure the IDs are treated as numbers

        // Query to check for distinct cust_id values for the provided IDs
        $checkQuery = "SELECT DISTINCT `cust_id` FROM `gld_challan` WHERE `id` IN ($escapedIds)";
        $result = $conn->query($checkQuery);
        
        if ($result && $result->num_rows > 1) {
            // More than one distinct customer ID found
            echo json_encode(['error' => 'Different customers found for the selected challans. Update aborted.']);
            return; // Stop the execution here if the customer IDs are different
        }

        // If only one distinct customer ID is found, proceed with the update
        $date = date('dmYHis');
        $updateQuery = "UPDATE `gld_challan` SET `status` = '1', `challan_number` = '$date' WHERE `id` IN ($escapedIds)";

        // Execute the update query
        if ($conn->query($updateQuery)) {
            echo json_encode(['success' => true, 'message' => 'Challan status updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to update challan status.']);
        }
    } else {
        echo json_encode(['error' => 'No valid IDs provided.']);
    }
}

// update challan status2 to print preview
function updateChallanBillNo($conn) {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the array of IDs from the request
    $ids = $data['ids'];

    // Check if we have valid IDs
    if (!empty($ids) && is_array($ids)) {
        // Escape and format IDs for SQL IN clause
        $escapedIds = implode(',', array_map('intval', $ids));

        // Query to check for distinct cust_id values for the provided IDs
        $checkQuery = "SELECT DISTINCT `cust_id` FROM `gld_challan` WHERE `id` IN ($escapedIds)";
        $result = $conn->query($checkQuery);

        if ($result && $result->num_rows > 1) {
            // More than one distinct customer ID found
            echo json_encode(['error' => 'Different customers found for the selected challans. Update aborted.']);
            return; // Stop the execution here if the customer IDs are different
        }

        // Get the last bill number and increment it by 1
        $lastBillQuery = "SELECT MAX(CAST(`bill_number` AS UNSIGNED)) AS last_bill_number FROM `gld_challan`";
        $billResult = $conn->query($lastBillQuery);
        $lastBillNumber = ($billResult && $billResult->num_rows > 0) ? (int)$billResult->fetch_assoc()['last_bill_number'] + 1 : 1;

        // Generate a unique challan number based on the current date and time
        $date = date('dmYHis');
        $updateQuery = "UPDATE `gld_challan` SET `bill_number` = '$lastBillNumber', `status` = '3', `challan_number` = '$date' WHERE `id` IN ($escapedIds)";

        // Execute the update query
        if ($conn->query($updateQuery)) {
            echo json_encode(['success' => true, 'message' => 'Challan status updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to update challan status.']);
        }
    } else {
        echo json_encode(['error' => 'No valid IDs provided.']);
    }

}

// function to get distinct challans
function getDistinctChallanNos($conn) {
    $query = "SELECT DISTINCT challan_number FROM gld_challan WHERE status<>0 ORDER BY TIMESTAMP(created_date) DESC";
    $result = $conn->query($query);

    $challanNos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $challanNos[] = ['challan_number' => $row['challan_number']];
        }
    }
    echo json_encode($challanNos);
}

// function to get distinct challans
function getDistinctBillNos($conn) {
    $query = "SELECT DISTINCT bill_number FROM gld_challan WHERE (status='2' OR status='3') AND bill_number<>'' ORDER BY TIMESTAMP(created_date) DESC";
    $result = $conn->query($query);

    $challanNos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $challanNos[] = ['challan_number' => $row['bill_number']];
        }
    }
    echo json_encode($challanNos);
}

// get challan details for row
function getChallanDetails($conn) {
    $challan_number = $_GET['challan_no'];
    $status = $_GET['status'];
    $query="gld_challan.challan_number = '$challan_number'";
    if($status=="billed"){
        $query="gld_challan.bill_number = '$challan_number'";
    }
    $query = "SELECT gld_challan.challan_number, gld_customer.gld_customer_title AS customer_name, gld_customer.gld_customer_cont_no AS customer_phone, stc_product.stc_product_name AS product_name, stc_product.stc_product_rack_id AS Rackid, stc_product.stc_product_unit AS unit, stc_product.stc_product_gst AS gst, gld_challan.qty, gld_challan.rate, gld_challan.discount, gld_challan.paid_amount, gld_challan.payment_status, gld_customer.gld_customer_address, gld_challan.created_date  
              FROM gld_challan
              JOIN gld_customer ON gld_challan.cust_id = gld_customer.gld_customer_id
              JOIN stc_product ON gld_challan.product_id = stc_product.stc_product_id
              WHERE ".$query;

    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $challanDetails = [];
        while ($row = $result->fetch_assoc()) {
            $challanDetails['challan_number'] = $row['challan_number'];
            $challanDetails['challan_date'] = $row['created_date'];
            $challanDetails['customer_name'] = $row['customer_name'];
            $challanDetails['customer_phone'] = $row['customer_phone'];
            $challanDetails['customer_address'] = $row['gld_customer_address'];
            $dues=($row['qty'] * $row['rate']) - $row['paid_amount'];
            $pricegst=$row['rate'] * $row['gst'] / 100;
            $challanDetails['products'][] = [
                'product_name' => $row['product_name'],
                'qty' => $row['qty'],
                'rate' => $row['rate'],
                'unit' => $row['unit'],
                'gst' => $row['gst'],
                'discount' => $row['discount'],
                'price_gst' => $pricegst,
                'dues' => $dues
            ];
        }
        echo json_encode($challanDetails);
    } else {
        echo json_encode(['error' => 'No details found for the selected challan.']);
    }
}

// get products details
function getProductDetails($conn) {
    if (!isset($_GET['productId']) || !is_numeric($_GET['productId'])) {
        echo json_encode(['error' => 'Invalid or missing product ID.']);
        return;
    }

    $productId = $_GET['productId'];

    $query = "
        SELECT 
            p.stc_product_id AS productId, 
            p.stc_product_name AS productName,
            p.stc_product_desc AS productDescription,
            c.stc_cat_name AS categoryName,
            sc.stc_sub_cat_name AS subCategoryName,
            r.stc_rack_name AS rackName,
            b.stc_brand_title AS brandName,
            p.stc_product_unit AS unit,
            p.stc_product_hsncode AS hsnCode,
            p.stc_product_gst AS gst,
            p.stc_product_avail AS availability,
            p.stc_product_image AS productImage,
            p.stc_product_sale_percentage AS salePercentage
        FROM stc_product p
        LEFT JOIN stc_category c ON p.stc_product_cat_id = c.stc_cat_id
        LEFT JOIN stc_sub_category sc ON p.stc_product_sub_cat_id = sc.stc_sub_cat_id
        LEFT JOIN stc_rack r ON p.stc_product_rack_id = r.stc_rack_id
        LEFT JOIN stc_brand b ON p.stc_product_brand_id = b.stc_brand_id
        WHERE p.stc_product_id = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $productId); // Bind productId as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $productDetails = $result->fetch_assoc(); // Fetch the single row
            echo json_encode(['success' => true, 'product' => $productDetails, 'message' => 'Product details fetched successfully.']);
        } else {
            echo json_encode(['error' => 'No details found for the selected product.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare query.']);
    }
}

// update challan add payment
function addPayment($conn) {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get the array of IDs from the request
    $challan_id = $data['challan_id'];
    $payment_amount = $data['payment_amount'];
    
    // update the SQL query
    $query = "UPDATE `gld_challan` SET `paid_amount` = '".$payment_amount."'  WHERE `id` =$challan_id";
    
    // Execute the query
    if ($conn->query($query)) {
        echo json_encode(['success' => true, 'message' => 'Challan status updated successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to update challan status.']);
    }
}

// delete challan
function deleteChallan($conn) {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get the array of IDs from the request
    $id = $data['id'];
    // update the SQL query
    $query = "DELETE FROM  `gld_challan` WHERE `id` =$id";
    
    // Execute the query
    if ($conn->query($query)) {
        echo json_encode(['success' => true, 'message' => 'Challan deleted successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to delete challan.']);
    }
}

// get requisitions details
function getRequisitions($conn) {

    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    $query = "
        SELECT 
            `id`, 
            `requisition_list_id`, 
            I.`stc_cust_super_requisition_list_items_title`, 
            I.`stc_cust_super_requisition_items_finalqty`, 
            `stc_cust_project_title`, 
            `stc_cust_pro_supervisor_fullname`, 
            `stc_cust_pro_supervisor_contact`,
            `status`
        FROM `stc_requisition_gld` 
        LEFT JOIN `stc_cust_super_requisition_list_items` I ON `requisition_list_id` = I.`stc_cust_super_requisition_list_id` 
        LEFT JOIN `stc_cust_super_requisition_list` L ON I.`stc_cust_super_requisition_list_items_req_id` = L.`stc_cust_super_requisition_list_id` 
        LEFT JOIN `stc_cust_pro_supervisor` ON L.`stc_cust_super_requisition_list_super_id` = `stc_cust_pro_supervisor_id` 
        LEFT JOIN `stc_cust_project` ON L.`stc_cust_super_requisition_list_project_id` = `stc_cust_project_id` 
        WHERE `status` = 1
    ";

    if ($search != '') {
        $query .= " AND (
            `stc_cust_project_title` LIKE ? OR 
            `stc_cust_super_requisition_list_items_title` LIKE ? OR 
            `stc_cust_pro_supervisor_fullname` LIKE ? OR 
            `stc_cust_pro_supervisor_contact` LIKE ?
        )";
    }

    if ($stmt = $conn->prepare($query)) {
        if ($search != '') {
            $searchWildcard = '%' . $search . '%';
            $stmt->bind_param("ssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $productDetails = $result->fetch_all(MYSQLI_ASSOC); // Fetch all matching rows
             // Apply number_format to the `stc_cust_super_requisition_items_finalqty` column
            foreach ($productDetails as &$product) {
                $query=mysqli_query($conn, "SELECT DISTINCT `stc_cust_super_requisition_list_purchaser_pd_id`, `stc_product_name` FROM `stc_cust_super_requisition_list_purchaser` LEFT JOIN `stc_product` ON `stc_cust_super_requisition_list_purchaser_pd_id`=`stc_product_id` WHERE `stc_cust_super_requisition_list_purchaser_list_item_id`='".$product['requisition_list_id']."'");
                if(mysqli_num_rows($query)>0){
                    foreach($query as $row){
                        $product['stc_product_id'] = $row['stc_cust_super_requisition_list_purchaser_pd_id'];
                        $product['stc_product_name'] = $row['stc_product_name'];
                    }
                }
                if (isset($product['stc_cust_super_requisition_items_finalqty'])) {
                    $product['stc_cust_super_requisition_items_finalqty'] = number_format($product['stc_cust_super_requisition_items_finalqty'], 2);
                }
            }
            echo json_encode([
                'success' => true, 
                'products' => $productDetails, 
                'message' => 'Product details fetched successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'No details found for the selected product.'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to prepare query.'
        ]);
    }

}
// set requisitions details
function setChallanRequisition($conn) {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract values from the decoded JSON
    $ListId = $data['ListId'] ?? '';
    $customerName = $data['userName'] ?? '';
    $customerContact = $data['contact'] ?? '';
    $customerAddress = $data['address'] ?? '';
    $productId = $data['productId'] ?? null;
    $requisition = $data['requisition'] ?? '';
    $quantity = $data['quantity'] ?? 0;
    $rate = 0;
    $userId=$data['userId'] ?? 0;

    if ($productId) {
        // Check if a customer already exists with the given contact number
        $checkQuery = "SELECT gld_customer_id FROM gld_customer WHERE gld_customer_cont_no = '$customerContact'";
        $result = $conn->query($checkQuery);

        if ($result && $result->num_rows > 0) {
            // If customer exists, fetch the customer ID
            $row = $result->fetch_assoc();
            $customerId = $row['gld_customer_id'];
        } else {
            // Insert a new customer if not found
            $insertQuery = "INSERT INTO gld_customer (gld_customer_title, gld_customer_cont_no, gld_customer_city_id, gld_customer_state_id, gld_customer_address) 
                            VALUES ('$customerName', '$customerContact', '65', '16', '$customerAddress')";
            if ($conn->query($insertQuery)) {
                $customerId = $conn->insert_id;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add customer', 'query' => $insertQuery]);
                return;
            }
        }

        $checkQuery = "SELECT `stc_purchase_product_adhoc_rate` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid`='$productId' ORDER BY `stc_purchase_product_adhoc_id` DESC LIMIT 1";
        $result = $conn->query($checkQuery);
        if ($result && $result->num_rows > 0) {
            // If customer exists, fetch the customer ID
            $row = $result->fetch_assoc();
            $rate = $row['stc_purchase_product_adhoc_rate'];
        }
        $date = date('Y-m-d H:i:s');
        $adhoc_id=0;
        // Link customer to the product and set the quantity
        $query = $conn->query("SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_qty` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid`='$productId' AND `stc_purchase_product_adhoc_status`=1 ORDER BY `stc_purchase_product_adhoc_id` ASC");
        while ($row = $query->fetch_assoc()) {
            $delivered=0;
            $sql_qry=$conn->query("
                SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
                FROM `stc_cust_super_requisition_list_items_rec` 
                WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$row['stc_purchase_product_adhoc_id']."'
            ");
            if(mysqli_num_rows($sql_qry)>0){
                foreach($sql_qry as $sql_row){
                    $delivered+=$sql_row['stc_cust_super_requisition_list_items_rec_recqty'];
                }
            }
            $deliveredgld=0;
            $sql_qry=$conn->query("
                SELECT `qty` FROM `gld_challan` WHERE `adhoc_id`='".$row['stc_purchase_product_adhoc_id']."'
            ");
            if(mysqli_num_rows($sql_qry)>0){
                foreach($sql_qry as $sql_row){
                    $deliveredgld+=$sql_row['qty'];
                }
            }
            $stock=$row['stc_purchase_product_adhoc_qty'] - ($delivered + $deliveredgld);
            if ($stock >= $quantity) {
                $adhoc_id = $row['stc_purchase_product_adhoc_id'];
                break;
            }
        }
        if($adhoc_id>0){
            // Link customer to the product and set the quantity
            $productQuery = "INSERT INTO gld_challan (cust_id, requisition_id, adhoc_id, product_id, qty, rate, agent_id, created_date, created_by) VALUES ('$customerId', '$requisition', '$adhoc_id', '$productId', '$quantity', '$rate', '0', '$date', '$userId')";
            if ($conn->query($productQuery)) {
                $conn->query("UPDATE `stc_requisition_gld` SET `status`=2 WHERE `id`='$ListId'");
                echo json_encode(['success' => true, 'message' => 'Challan created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create Challan']);
            }
        }else {
            echo json_encode(['error' => false, 'message' => 'Out of Stock.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    }
}

?>