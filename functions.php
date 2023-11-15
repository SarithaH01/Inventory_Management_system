<?php
$host = 'localhost';
$db   = 'inventory_system';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function generate_bill($sale_id) {
    global $mysqli;

    $sale_id = (int)$sale_id;

    // Perform a join to get product information
    $stmt = $mysqli->prepare("SELECT s.id AS sale_id, s.qty, s.price, s.date, p.name AS product_name
                              FROM sales s
                              LEFT JOIN products p ON s.product_id = p.id
                              WHERE s.id = ?");
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sale = $result->fetch_assoc();
        $stmt->close();

        // Start building the bill content as an HTML invoice
        $bill_content = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Invoice</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    .invoice {
                        border: 1px solid #000;
                        padding: 20px;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    .invoice h2 {
                        margin-top: 5px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    table, th, td {
                        border: 1px solid #000;
                        padding: 10px;
                        text-align: left;
                    }
                </style>
            </head>
            <body>
                <div class="invoice">
                    <h2>Invoice</h2>
                    <table>
                        <tr><th>Invoice ID:</th><td>' . $sale['sale_id'] . '</td></tr>
                        <tr><th>Product Name:</th><td>' . ($sale['product_name'] !== null && $sale['product_name'] !== '' ? htmlspecialchars($sale['product_name']) : "N/A") . '</td></tr>
                        <tr><th>Quantity:</th><td>' . (int)$sale['qty'] . '</td></tr>
                        <tr><th>Unit Price:</th><td>$' . number_format((float)$sale['price'], 2) . '</td></tr>
                        <tr><th>Total Price:</th><td>$' . number_format((float)$sale['price'] * (int)$sale['qty'], 2) . '</td></tr>
                        <tr><th>Date:</th><td>' . $sale['date'] . '</td></tr>
                    </table>
                </div>
            </body>
            </html>';

        return $bill_content; // Return the HTML content
    } else {
        return false; // Return false if sale not found or bill generation failed
    }
}
?>