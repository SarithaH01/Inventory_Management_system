<?php
$host = 'localhost';
$db   = 'inventory_system';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function generate_bill($sale_id, $sale, $cgst_amount, $sgst_amount, $total_with_tax) {
    // ... (rest of your existing code)

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
                    max-width: 600px;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #000;
                    padding-bottom: 10px;
                }
                .section {
                    margin-top: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                table, th, td {
                    border: 1px solid #000;
                    padding: 10px;
                    text-align: left;
                }
                .total {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="invoice">
                <div class="header">
                    <h2>Invoice</h2>
                </div>
                <div class="section">
                    <table>
                        <tr><th>Invoice ID:</th><td>' . $sale_id . '</td></tr>
                        <tr><th>Date:</th><td>' . $sale['date'] . '</td></tr>
                    </table>
                </div>
                <div class="section">
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                        <tr>
                            <td>' . htmlspecialchars($sale['product_name']) . '</td>
                            <td>' . (int)$sale['qty'] . '</td>
                            <td>?' . number_format((float)$sale['price'], 2) . '</td>
                            <td>?' . number_format((float)$sale['price'] * (int)$sale['qty'], 2) . '</td>
                        </tr>
                    </table>
                </div>
                <div class="section">
                    <table>
                        <tr><th>Subtotal:</th><td>?' . number_format((float)$sale['price'] * (int)$sale['qty'], 2) . '</td></tr>
                        <tr><th>CGST (9%):</th><td>?' . number_format($cgst_amount, 2) . '</td></tr>
                        <tr><th>SGST (9%):</th><td>?' . number_format($sgst_amount, 2) . '</td></tr>
                    </table>
                </div>
                <div class="total">
                    <p>Total: ?' . number_format($total_with_tax, 2) . '</p>
                </div>
                <div class="section">
                    <p>Thank you!</p>
                </div>
            </div>
        </body>
        </html>';

    return $bill_content; // Return the HTML content
}
?>