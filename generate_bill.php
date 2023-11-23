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
        
        // Calculate tax amounts
        $cgst_rate = 9; // CGST rate
        $sgst_rate = 9; // SGST rate
        $cgst_amount = ($sale['price'] * $cgst_rate / 100) * $sale['qty'];
        $sgst_amount = ($sale['price'] * $sgst_rate / 100) * $sale['qty'];
        $total_with_tax = $sale['price'] * $sale['qty'] + $cgst_amount + $sgst_amount;

        

// ... (your existing code)

$date = $sale['date'];
$bill_content = generate_bill($sale_id, $sale, $cgst_amount, $sgst_amount, $total_with_tax, $date);

// ... (your existing code)

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