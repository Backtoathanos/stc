<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";

class prime extends tesseract {
// Search product with pagination
    public function stc_landingpage_products($search, $page = 1, $perPage = 9) {
        $results = array();

        // Calculate offset for pagination
        $offset = ($page - 1) * $perPage;

        // Base query
        $query = "SELECT DISTINCT `stc_product_id`, `stc_product_name`, `stc_sub_cat_name`, `stc_cat_name`, `stc_product_image` 
                  FROM `stc_product` 
                  LEFT JOIN `stc_category` ON `stc_cat_id` = `stc_product_cat_id` 
                  LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id` = `stc_product_sub_cat_id`";

        // Add search condition if $search is not empty
        if (!empty($search)) {
            $query .= " WHERE `stc_product_name` LIKE ? OR `stc_product_desc` LIKE ?";
        }

        // Add sorting and limit with pagination
        $query .= " ORDER BY `stc_product_name` ASC LIMIT ?, ?";

        // Prepare the statement
        $stmt = mysqli_prepare($this->stc_dbs, $query);
        if (!$stmt) {
            return array('error' => 'Failed to prepare the query.');
        }

        // Bind parameters
        if (!empty($search)) {
            $searchTerm = "%" . $search . "%";
            mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $offset, $perPage);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $offset, $perPage);
        }

        // Execute the query
        if (!mysqli_stmt_execute($stmt)) {
            return array('error' => 'Failed to execute the query.');
        }

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch rows and store them in the $results array
        while ($row = mysqli_fetch_assoc($result)) {
            $query="
                SELECT DISTINCT `stc_purchase_product_adhoc_rate` 
                FROM `stc_purchase_product_adhoc` 
                WHERE `stc_purchase_product_adhoc_productid`='".$row['stc_product_id']."' AND `stc_purchase_product_adhoc_rate`>0 LIMIT 1
            ";
            $sql_qry=mysqli_query($this->stc_dbs, $query);
            $row['rate']=0;
            if(mysqli_num_rows($sql_qry)>0){
                $result2=mysqli_fetch_assoc($sql_qry);
                $row['rate']=$result2['stc_purchase_product_adhoc_rate'];
            }
            if($row['stc_product_image']!=''){
                $results[] = $row;
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the results
        return $results;
    }

    // Search product with pagination
    public function stc_search_products($search, $page = 1, $perPage = 25) {
        $results = array();

        // Calculate offset for pagination
        $offset = ($page - 1) * $perPage;

        // Base query
        $query = "SELECT DISTINCT `stc_product_id`, `stc_product_name`, `stc_sub_cat_name`, `stc_cat_name`, `stc_product_image` 
                  FROM `stc_product` 
                  LEFT JOIN `stc_category` ON `stc_cat_id` = `stc_product_cat_id` 
                  LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id` = `stc_product_sub_cat_id`";

        // Add search condition if $search is not empty
        if (!empty($search)) {
            $query .= " WHERE `stc_product_name` LIKE ? OR `stc_product_desc` LIKE ?";
        }

        // Add sorting and limit with pagination
        $query .= " ORDER BY `stc_product_name` ASC LIMIT ?, ?";

        // Prepare the statement
        $stmt = mysqli_prepare($this->stc_dbs, $query);
        if (!$stmt) {
            return array('error' => 'Failed to prepare the query.');
        }

        // Bind parameters
        if (!empty($search)) {
            $searchTerm = "%" . $search . "%";
            mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $offset, $perPage);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $offset, $perPage);
        }

        // Execute the query
        if (!mysqli_stmt_execute($stmt)) {
            return array('error' => 'Failed to execute the query.');
        }

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch rows and store them in the $results array
        while ($row = mysqli_fetch_assoc($result)) {
            $query="
                SELECT DISTINCT `stc_purchase_product_adhoc_rate` 
                FROM `stc_purchase_product_adhoc` 
                WHERE `stc_purchase_product_adhoc_productid`='".$row['stc_product_id']."' AND `stc_purchase_product_adhoc_rate`>0 LIMIT 1
            ";
            $sql_qry=mysqli_query($this->stc_dbs, $query);
            $row['rate']=0;
            if(mysqli_num_rows($sql_qry)>0){
                $result2=mysqli_fetch_assoc($sql_qry);
                $row['rate']=$result2['stc_purchase_product_adhoc_rate'];
            }
            if($row['stc_product_image']!=''){
                $results[] = $row;
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the results
        return $results;
    }

    public function stc_get_products($product_data) {
        // Initialize variables
        $products = [];
        $total = 0;
    
        // Check if product data is provided
        if (!empty($product_data)) {
            // Extract product IDs from the product data
            $product_ids = array_column($product_data, 'productId');
    
            // Create placeholders for the prepared statement
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    
            // Base query to fetch product details and rate
            $query = "
                SELECT 
                    p.`stc_product_id`, 
                    p.`stc_product_name`, 
                    sc.`stc_sub_cat_name`, 
                    c.`stc_cat_name`, 
                    p.`stc_product_image`, 
                    COALESCE(a.`stc_purchase_product_adhoc_rate`, 0) AS rate
                FROM `stc_product` p
                LEFT JOIN `stc_category` c ON c.`stc_cat_id` = p.`stc_product_cat_id`
                LEFT JOIN `stc_sub_category` sc ON sc.`stc_sub_cat_id` = p.`stc_product_sub_cat_id`
                LEFT JOIN (
                    SELECT 
                        `stc_purchase_product_adhoc_productid`, 
                        MIN(`stc_purchase_product_adhoc_rate`) AS `stc_purchase_product_adhoc_rate`
                    FROM `stc_purchase_product_adhoc`
                    WHERE `stc_purchase_product_adhoc_rate` > 0
                    GROUP BY `stc_purchase_product_adhoc_productid`
                ) a ON a.`stc_purchase_product_adhoc_productid` = p.`stc_product_id`
                WHERE p.`stc_product_id` IN ($placeholders)
            ";
    
            // Prepare the statement
            $stmt = mysqli_prepare($this->stc_dbs, $query);
            if (!$stmt) {
                return [
                    'status' => 'failed',
                    'message' => 'Failed to prepare the SQL statement.'
                ];
            }
    
            // Bind parameters
            $types = str_repeat('i', count($product_ids)); // 'i' for integer
            mysqli_stmt_bind_param($stmt, $types, ...$product_ids);
    
            // Execute the query
            if (!mysqli_stmt_execute($stmt)) {
                return [
                    'status' => 'failed',
                    'message' => 'Failed to execute the SQL statement.'
                ];
            }
    
            // Get the result
            $result = mysqli_stmt_get_result($stmt);
    
            // Fetch rows and store them in the $products array
            while ($row = mysqli_fetch_assoc($result)) {
                // Find the corresponding quantity from the product data
                $product_info = array_filter($product_data, function ($item) use ($row) {
                    return $item['productId'] == $row['stc_product_id'];
                });
                $quantity = !empty($product_info) ? reset($product_info)['quantity'] : 1; // Default to 1 if not found
    
                // Include only products with non-empty images
                if (!empty($row['stc_product_image'])) {
                    $products[] = [
                        'id' => $row['stc_product_id'],
                        'name' => $row['stc_product_name'],
                        'sub_category' => $row['stc_sub_cat_name'],
                        'category' => $row['stc_cat_name'],
                        'image' => $row['stc_product_image'],
                        'rate' => $row['rate'],
                        'quantity' => $quantity // Use the quantity from the cart
                    ];
                    $total += $row['rate'] * $quantity; // Add rate * quantity to the total
                }
            }
    
            // Close the statement
            mysqli_stmt_close($stmt);
    
            // Return the results
            return [
                'status' => 'success',
                'products' => $products,
                'total' => $total
            ];
        } else {
            // If no product data is provided
            return [
                'status' => 'failed',
                'message' => 'No product data provided.'
            ];
        }
    }

    // Search product with pagination
    public function stc_get_states() {
        $results = array();
    
        // Base query
        $query = "SELECT `stc_state_id` AS id, `stc_state_name` AS name FROM `stc_state`";
    
        // Prepare the statement
        $stmt = mysqli_prepare($this->stc_dbs, $query);
        if (!$stmt) {
            return array('status' => 'error', 'message' => 'Failed to prepare the query.');
        }
    
        // Execute the query
        if (!mysqli_stmt_execute($stmt)) {
            return array('status' => 'error', 'message' => 'Failed to execute the query.');
        }
    
        // Get the result
        $result = mysqli_stmt_get_result($stmt);
    
        // Fetch rows and store them in the $results array
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    
        // Close the statement
        mysqli_stmt_close($stmt);
    
        // Return the results
        return array('status' => 'success', 'data' => $results);
    }

    // save orders
    public function stc_save_orders($output_array) {
        $results = array();
    
        // Check database connection
        if (!$this->stc_dbs) {
            return array('status' => 'error', 'message' => 'Database connection failed.');
        }
    
        // Loop through each cart item and insert into the database
        foreach ($output_array['cart_items'] as $item) {
            $product_id = $item['productId']; // Product ID
            $quantity = $item['quantity']; // Quantity
            $rate = $item['rate']; // Rate
    
            // Validate input data
            if (empty($product_id) || empty($quantity) || empty($rate)) {
                return array('status' => 'error', 'message' => 'Invalid product data.');
            }
    
            $query = "INSERT INTO `orders` (
                          `first_name`, 
                          `last_name`, 
                          `email`, 
                          `phone_number`, 
                          `street_address`, 
                          `city`, 
                          `zipCode`, 
                          `state`, 
                          `product_id`, 
                          `quantity`, 
                          `rate`,  
                          `status`, 
                          `created_at`
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
            // Prepare the statement
            $stmt = mysqli_prepare($this->stc_dbs, $query);
            if (!$stmt) {
                $error = mysqli_error($this->stc_dbs); // Capture the SQL error
                return array('status' => 'error', 'message' => 'Failed to prepare the query: ' . $error, 'data' => $item['quantity']);
            }
            $status = 1;
            // Bind parameters
            mysqli_stmt_bind_param($stmt, 'ssssssssiddi', 
                $output_array['first_name'],
                $output_array['last_name'],
                $output_array['email'],
                $output_array['phone_number'],
                $output_array['street_address'],
                $output_array['city'],
                $output_array['zipCode'],
                $output_array['state'],
                $product_id,
                $quantity,
                $rate,
                $status
            );
    
            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                $error = mysqli_error($this->stc_dbs); // Capture the SQL error
                return array('status' => 'error', 'message' => 'Failed to execute the query: ' . $error);
            }
    
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    
        // Return success message
        return array('status' => 'success', 'message' => 'Order saved successfully.', 'data' => $output_array['cart_items']);
    }

    public function stc_getProductByid($productId) {
        // Base query to fetch product details
        $query = "SELECT DISTINCT `stc_product_id`, `stc_product_name`, `stc_sub_cat_name`, `stc_cat_name`, `stc_product_image` 
                  FROM `stc_product` 
                  LEFT JOIN `stc_category` ON `stc_cat_id` = `stc_product_cat_id` 
                  LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id` = `stc_product_sub_cat_id`
                  WHERE `stc_product_id` = ?";

        // Prepare the statement
        $stmt = $this->stc_dbs->prepare($query);
        if (!$stmt) {
            return ['status' => 'error', 'message' => 'Failed to prepare the query.'];
        }

        // Bind parameters
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Fetch product rate
            $rateQuery = "SELECT DISTINCT `stc_purchase_product_adhoc_rate` 
                          FROM `stc_purchase_product_adhoc` 
                          WHERE `stc_purchase_product_adhoc_productid` = ? AND `stc_purchase_product_adhoc_rate` > 0 
                          LIMIT 1";
            $rateStmt = $this->stc_dbs->prepare($rateQuery);
            $rateStmt->bind_param('i', $row['stc_product_id']);
            $rateStmt->execute();
            $rateResult = $rateStmt->get_result();

            $row['rate'] = 0;
            if ($rateResult->num_rows > 0) {
                $rateRow = $rateResult->fetch_assoc();
                $row['rate'] = $rateRow['stc_purchase_product_adhoc_rate'];
            }

            // Prepare response
            $response = [
                'status' => 'success',
                'data' => [
                    'product_id' => $row['stc_product_id'],
                    'product_name' => $row['stc_product_name'],
                    'product_price' => $row['rate'], // Use the fetched rate as the price
                    'stock_status' => 'In Stock', // Assuming stock status is always available
                    'product_description' => 'Product description', // Add description if available
                    'images' => [$row['stc_product_image']] // Add more images if available
                ]
            ];

            return $response;
        } else {
            return ['status' => 'error', 'message' => 'Product not found.'];
        }
    }
}
// Handle search request
if (isset($_GET['searchlanidng']) && isset($_GET['page'])) {
    $search = $_GET['searchlanidng'];
    $page = intval($_GET['page']); // Current page number
    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_landingpage_products($search, $page);
    echo json_encode($opobjlogin);
}

// Handle search request
if (isset($_GET['search']) && isset($_GET['page'])) {
    $search = $_GET['search'];
    $page = intval($_GET['page']); // Current page number
    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_search_products($search, $page);
    echo json_encode($opobjlogin);
}

// Handle search request
if (isset($_POST['show_carts'])) {
    // Get product data from the request
    $product_data = json_decode($_POST['product_data'], true);

    // Validate product data
    if (!empty($product_data) && is_array($product_data)) {
        $objlogin = new prime();
        $response = $objlogin->stc_get_products($product_data);
        echo json_encode($response);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Invalid product data.'
        ]);
    }
}

// get states
if (isset($_POST['get_states'])) {
    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_get_states();
    echo json_encode($opobjlogin);
}

// save order details
if (isset($_POST['order_details_save'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $street_address = $_POST['street_address'];
    $city = $_POST['city'];
    $zipCode = $_POST['zipCode'];
    $state = $_POST['state'];
    $cart_items = json_decode($_POST['cart_items'], true); // Decode JSON string to array

    // Check if cart_items is an array
    if (!is_array($cart_items)) {
        echo json_encode(array('status' => 'error', 'message' => 'Invalid cart items format.'));
        exit;
    }

    $output_array = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone_number' => $phone_number,
        'street_address' => $street_address,
        'city' => $city,
        'zipCode' => $zipCode,
        'state' => $state,
        'cart_items' => $cart_items
    );

    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_save_orders($output_array);
    echo json_encode($opobjlogin);
}

// Handle search request
if (isset($_GET['getProduct_byId']) && isset($_GET['productId'])) {
    $productId = intval($_GET['productId']);
    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_getProductByid($productId);
    echo json_encode($opobjlogin);
}

?>