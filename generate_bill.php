<?php
$page_title = 'Invoice';
require_once('includes/load.php');
// Check What level user has permission to view this page
page_require_level(3);

if (isset($_GET['id'])) {
    $sale_id = (int)$_GET['id'];
    require_once('functions.php');
    
    // Fetch sale details directly
    $stmt = $mysqli->prepare("SELECT s.*, p.name AS product_name FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE s.id = ?");
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sale = $result->fetch_assoc();
        $stmt->close();
        
        $bill_content = generate_bill($sale_id);

        if ($bill_content) {
            include('invoice_template.php');
            exit();
        } else {
            echo "Sale not found or bill generation failed.";
        }
    } else {
        echo "Sale not found.";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>