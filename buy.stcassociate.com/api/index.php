<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";

class prime extends tesseract {

    // Search product with pagination
    public function stc_search_products($search, $page = 1, $perPage = 15) {
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
            $results[] = $row;
        }

        // Close the statement
        mysqli_stmt_close($stmt);

        // Return the results
        return $results;
    }
}

// Handle search request
if (isset($_GET['search']) && isset($_GET['page'])) {
    $search = $_GET['search'];
    $page = intval($_GET['page']); // Current page number
    $objlogin = new prime();
    $opobjlogin = $objlogin->stc_search_products($search, $page);
    echo json_encode($opobjlogin);
}
?>