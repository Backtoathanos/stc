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

    default:
        echo json_encode(['error' => 'Invalid API action', 'fault' => $_GET['action']]);
        break;
}

// Function to fetch customers from the database
function getCustomers($conn) {
    $query = "SELECT stc_trading_customer_id, stc_trading_customer_title FROM stc_trading_customer";
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

    // Insert new customer if no existing customer is selected
    if (!$customerId) {
        $query = "INSERT INTO customers (name, contact, address) VALUES ('$customerName', '$customerContact', '$customerAddress')";
        $conn->query($query);
        $customerId = $conn->insert_id;
    }

    // Link customer to the product and set the quantity
    $productQuery = "INSERT INTO customer_products (customer_id, product_id, quantity) VALUES ('$customerId', '$productId', '$quantity')";
    if ($conn->query($productQuery)) {
        echo json_encode(['success' => true, 'message' => 'Customer and product added successfully']);
    } else {
        echo json_encode(['error' => 'Failed to add customer and product']);
    }
}
