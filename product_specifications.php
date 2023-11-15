<?php
// Include your database connection or any necessary includes here
require_once('includes/database.php'); // Include your database connection script

// Function to retrieve product specifications by product ID
function find_specifications_by_product_id($product_id) {
    // Include your database connection script (database.php or equivalent)
    require_once('includes/database.php');

    // Ensure your database connection is established correctly in your database.php file

    if ($db->connect_error) {
        die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
    }

    // Modify this query to match your database structure (use your table name 'product_specifications')
    $query = "SELECT * FROM product_specifications WHERE product_id = ?";

    // Prepare the query
    $stmt = $db->prepare($query);

    if ($stmt === false) {
        die('Error: ' . $db->error);
    }

    // Bind the product_id parameter
    $stmt->bind_param('i', $product_id);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the specifications
    $specifications = [];
    while ($row = $result->fetch_assoc()) {
        $specifications[] = $row;
    }

    // Close the statement and the database connection
    $stmt->close();
    $db->close();

    return $specifications;
}

// Function to update product specifications
function update_product_specifications($product_id, $specifications) {
    // Include your database connection script (database.php or equivalent)
    require_once('includes/database.php');

    // Ensure your database connection is established correctly in your database.php file

    if ($db->connect_error) {
        die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
    }

    // First, delete existing specifications for the product
    $deleteQuery = "DELETE FROM product_specifications WHERE product_id = ?";

    // Prepare the delete query
    $stmt = $db->prepare($deleteQuery);

    if ($stmt === false) {
        die('Error: ' . $db->error);
    }

    // Bind the product_id parameter
    $stmt->bind_param('i', $product_id);

    // Execute the delete query
    if ($stmt->execute()) {
        // Insert the updated specifications
        $insertQuery = "INSERT INTO product_specifications (product_id, specification_name, specification_value) VALUES (?, ?, ?)";
        $stmt = $db->prepare($insertQuery);

        if ($stmt === false) {
            die('Error: ' . $db->error);
        }

        foreach ($specifications as $spec) {
            $name = $spec['specification_name'];
            $value = $spec['specification_value'];

            // Bind the parameters
            $stmt->bind_param('iss', $product_id, $name, $value);

            // Execute the insert query
            if (!$stmt->execute()) {
                die('Error: ' . $stmt->error);
            }
        }

        // Close the statement and the database connection
        $stmt->close();
        $db->close();
        return true; // Return true if the update was successful
    } else {
        die('Error: ' . $stmt->error);
        return false; // Return false if the delete query fails
    }
}
?>