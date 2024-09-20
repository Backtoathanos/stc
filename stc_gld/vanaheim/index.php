<?php
include "../../MCU/obdb.php";  // Include the database connectivity file

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

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

    case 'getChallan':
        getChallan($conn);
        break;

    case 'getChallanNumnber':
        getChallanNumnber($conn);
        break;

    default:
        echo json_encode(['error' => 'Invalid API action', 'fault' => $_GET['action']]);
        break;
}

// Function to fetch customers from the database
function getCustomers($conn) {
    $query = "SELECT gld_customer_id, gld_customer_cont_no FROM gld_customer";
    $result = $conn->query($query);

    $customers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    }

    echo json_encode($customers);
}

// Function to add a new customer and associate them with a product
function addCustomer($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $customerId = $data['id'];
    $customerName = $data['name'];
    $customerContact = $data['contact'];
    $customerAddress = $data['address'];
    $productId = $data['product_id'];
    $quantity = $data['quantity'];
    $rate = $data['rate'];
    if($productId){

        // Insert new customer if no existing customer is selected
        if (!$customerId) {
            $query = "INSERT INTO gld_customer (gld_customer_title, gld_customer_cont_no, gld_customer_address) VALUES ('$customerName', '$customerContact', '$customerAddress')";
            $conn->query($query);
            $customerId = $conn->insert_id;
        }

        // Link customer to the product and set the quantity
        $productQuery = "INSERT INTO gld_challan (cust_id, product_id, qty, rate) VALUES ('$customerId', '$productId', '$quantity', '$rate')";
        if ($conn->query($productQuery)) {
            echo json_encode(['success' => true, 'message' => 'Customer and product added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add customer and product']);
        }
    }
}

// Function to fetch customers from the database
function getChallan($conn) {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $query = "SELECT id, stc_product_name, gld_customer_title, requisition_id, challan_number, ROUND(qty, 2) as qty, ROUND(rate, 2) as rate, ROUND(paid_amount, 2) as paid_amount, payment_status, status, created_date, created_by 
                  FROM gld_challan GC
                  LEFT JOIN stc_product ON GC.product_id=stc_product_id
                  LEFT JOIN gld_customer ON GC.cust_id=gld_customer_id
                  WHERE challan_number LIKE '%$search%' 
                     OR stc_product_name LIKE '%$search%' 
                     OR gld_customer_title LIKE '%$search%' 
                     OR payment_status LIKE '%$search%'";

    $result = $conn->query($query);

    $challanData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $challanData[] = $row;
        }
    }
    echo json_encode($challanData);
}

function getChallanNumnber($conn) {
    $query = "SELECT id, stc_product_name, gld_customer_title, requisition_id, challan_number, ROUND(qty, 2) as qty, ROUND(rate, 2) as rate, ROUND(paid_amount, 2) as paid_amount, payment_status, status, created_date, created_by 
                  FROM gld_challan GC
                  LEFT JOIN stc_product ON GC.product_id=stc_product_id
                  LEFT JOIN gld_customer ON GC.cust_id=gld_customer_id
                  WHERE challan_number LIKE '%$search%' 
                     OR stc_product_name LIKE '%$search%' 
                     OR gld_customer_title LIKE '%$search%' 
                     OR payment_status LIKE '%$search%'";
}