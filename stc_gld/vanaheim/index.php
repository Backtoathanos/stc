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

    case 'addRequisition':
        addRequisition($conn);
        break;
    case 'editRequisition':
        editRequisition($conn);
        break;
    case 'deleteRequisition':
        deleteRequisition($conn);
        break;

    case 'updateRequisitionStatus':
        updateRequisitionStatus($conn);
        break;

    case 'transferProduct':
        transferProduct($conn);
        break;

    case 'createRack':
        createRack($conn);
        break;
    case 'updateProductRack':
        updateProductRack($conn);
        break;

    case 'getRacks':
        getRacks($conn);
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
        $query = $conn->query("SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_qty` FROM `stc_purchase_product_adhoc` INNER JOIN `stc_shop` ON `stc_purchase_product_adhoc_id`=`adhoc_id` WHERE `stc_purchase_product_adhoc_productid`='$productId' AND `stc_purchase_product_adhoc_status`=1 ORDER BY `stc_purchase_product_adhoc_id` ASC");
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
    $query = "SELECT DISTINCT bill_number FROM gld_challan WHERE (status = '2' OR status = '3') AND bill_number <> '' ORDER BY CAST(bill_number AS UNSIGNED) DESC;";
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

// Add a new requisition
function addRequisition($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = trim($conn->real_escape_string($data['name']));
    $quantity = floatval($data['quantity']);
    $unit = $conn->real_escape_string($data['unit']);
    $remarks = $conn->real_escape_string($data['remarks']);
    if($name == '' || $quantity == 0 || $unit == '') {
        echo json_encode(['success' => false, 'error' => 'Please fill all the fields']);
        return;
    }
    // Check for existing requisition with same name and status < 2
    $checkQuery = "SELECT id, status FROM gld_requisitions WHERE name='$name' AND status < 2 LIMIT 1";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        echo json_encode([
            'success' => false,
            'error' => 'A requisition with this name is already raised.',
            'status' => $row['status'],
            'message' => 'Already raised requisition. Please contact by telephone.'
        ]);
        return;
    }
    $query = "INSERT INTO gld_requisitions (name, quantity, unit, remarks, status) VALUES ('$name', $quantity, '$unit', '$remarks', 1)";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
// Edit an existing requisition
function editRequisition($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id']);
    $name = $conn->real_escape_string($data['name']);
    $quantity = floatval($data['quantity']);
    $unit = $conn->real_escape_string($data['unit']);
    $remarks = $conn->real_escape_string($data['remarks']);
    $query = "UPDATE gld_requisitions SET name='$name', quantity=$quantity, unit='$unit', remarks='$remarks' WHERE id=$id";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
// Delete a requisition
function deleteRequisition($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id']);
    $query = "DELETE FROM gld_requisitions WHERE id=$id";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
// View (list) requisitions (update getRequisitions)
function getRequisitions($conn) {
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    $where = "";
    if ($search !== '') {
        $where = "WHERE name LIKE '%$search%' OR unit LIKE '%$search%' OR remarks LIKE '%$search%'";
    }
    $query = "SELECT * FROM gld_requisitions $where ORDER BY id DESC LIMIT $offset, $limit";
    $result = $conn->query($query);
    $records = [];
    while ($row = $result->fetch_assoc()) {
        // Map status to text for frontend
        $statusText = '';
        switch ((int)$row['status']) {
            case 1: $statusText = 'Request'; break;
            case 2: $statusText = 'Accepted'; break;
            case 3: $statusText = 'Dispatched'; break;
            case 4: $statusText = 'Received'; break;
            default: $statusText = 'Unknown';
        }
        $row['status_text'] = $statusText;
        $records[] = $row;
    }
    // Get total count for pagination
    $countQuery = "SELECT COUNT(*) as total FROM gld_requisitions $where";
    $countResult = $conn->query($countQuery);
    $total = $countResult->fetch_assoc()['total'];
    echo json_encode(['records' => $records, 'total' => $total]);
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

function updateRequisitionStatus($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id']);
    $status = intval($data['status']);
    $query = "UPDATE gld_requisitions SET status=$status WHERE id=$id";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

function transferProduct($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $conn->real_escape_string($data['product_id']);
    $existingbranch = $conn->real_escape_string($data['existingbranch']);
    $branch = $conn->real_escape_string($data['branch']);
    $transfer_qty = isset($data['transfer_qty']) ? floatval($data['transfer_qty']) : null; // For scenario 2

    // Find all adhoc records for this product and branch
    $query = "SELECT a.stc_purchase_product_adhoc_id, s.qty FROM stc_purchase_product_adhoc a INNER JOIN stc_shop s ON a.stc_purchase_product_adhoc_id = s.adhoc_id WHERE a.stc_purchase_product_adhoc_productid = '$product_id' AND s.shopname = '$existingbranch' GROUP BY a.stc_purchase_product_adhoc_id";
    
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $success = true;
        $message = 'Product transferred successfully.';
        $total_available = 0;
        $adhoc_records = [];
        
        // First calculate total available quantity across all adhoc records
        while ($row = $result->fetch_assoc()) {
            $adhoc_id = $row['stc_purchase_product_adhoc_id'];
            $shop_qty = $row['qty'];
            
            // Get total challan qty for this adhoc_id and branch
            $getchallan = $conn->query("SELECT SUM(qty) as qty FROM gld_challan INNER JOIN stc_trading_user ON created_by = stc_trading_user_id WHERE stc_trading_user_location = '$existingbranch' AND adhoc_id = '$adhoc_id'");
            $challanqty = $getchallan ? floatval($getchallan->fetch_assoc()['qty']) : 0;
            $available = $shop_qty - $challanqty;
            
            if ($available > 0) {
                $total_available += $available;
                $adhoc_records[] = ['adhoc_id' => $adhoc_id,'available_qty' => $available
                ];
            }
        }
        
        // Check if we have enough quantity to transfer
        if ($transfer_qty !== null && $transfer_qty > $total_available) {
            echo json_encode(['success' => false, 'error' => 'Not enough quantity available for transfer.']);
            return;
        }
        
        $qty_to_transfer = ($transfer_qty !== null) ? $transfer_qty : $total_available;
        $remaining_qty = $qty_to_transfer;
        
        // Process each adhoc record to transfer the quantity
        foreach ($adhoc_records as $record) {
            if ($remaining_qty <= 0) break;
            
            $adhoc_id = $record['adhoc_id'];
            $adhoc_available = $record['available_qty'];
            $qty_from_this_adhoc = min($adhoc_available, $remaining_qty);
            
            // Get all shop entries for this adhoc_id and branch, ordered by qty DESC
            $getRows = $conn->query("SELECT * FROM stc_shop WHERE shopname = '$existingbranch' AND qty > 0 AND adhoc_id = '$adhoc_id' ORDER BY qty DESC");
            
            $adhoc_remaining = $qty_from_this_adhoc;
            
            while ($getRows && $row = $getRows->fetch_assoc()) {
                if ($adhoc_remaining <= 0) break;
                
                $id = $row['id']; // Assuming there's an id column
                $availableQty = $row['qty'];
                $transfer_amount = min($availableQty, $adhoc_remaining);
                
                // Reduce quantity from existing branch
                $conn->query("UPDATE stc_shop SET qty = qty - $transfer_amount WHERE id = '$id'");
                
                // Add quantity to new branch
                $conn->query("INSERT INTO stc_shop (shopname, qty, adhoc_id, rack_id, remarks) 
                             VALUES ('$branch', $transfer_amount, '$adhoc_id', '0', 'Transferred from $existingbranch')");
                
                $adhoc_remaining -= $transfer_amount;
                $remaining_qty -= $transfer_amount;
            }
        }
        
        echo json_encode(['success' => $success, 'message' => $message, 'transferred_qty' => $qty_to_transfer]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No record found for this product and branch.']);
    }
}

function createRack($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $rack_name = $conn->real_escape_string($data['rack_name']);
    $locationcookie = $conn->real_escape_string($data['locationcookie']);
    $query = "INSERT INTO stc_rack (stc_rack_name, stc_rack_location) VALUES ('$rack_name', '$locationcookie')";
    if ($conn->query($query)) {
        $id = $conn->insert_id;
        $conn->query("DELETE FROM stc_shop WHERE stc_rack_name = ''");
        echo json_encode(['success' => true, 'id' => $id, 'name' => $rack_name]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

function updateProductRack($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = intval($data['product_id']);
    $rack_id = intval($data['rack_id']);
    $locationcookie = $conn->real_escape_string($data['locationcookie']);
    $query = "UPDATE stc_shop SET rack_id = $rack_id WHERE adhoc_id IN (SELECT stc_purchase_product_adhoc_id FROM stc_purchase_product_adhoc WHERE stc_purchase_product_adhoc_productid = $product_id) AND shopname = '$locationcookie'";
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

function getRacks($conn) {
    $racks = [];
    $locationcookie = isset($_GET['locationcookie']) ? $_GET['locationcookie'] : '';
    $rack_query = $conn->query("SELECT stc_rack_id, stc_rack_name FROM stc_rack WHERE stc_rack_location = '$locationcookie' ORDER BY stc_rack_name ASC");
    while ($rack = $rack_query->fetch_assoc()) {
        $racks[] = [
            'id' => $rack['stc_rack_id'],
            'name' => $rack['stc_rack_name']
        ];
    }
    echo json_encode(['racks' => $racks]);
}

?>