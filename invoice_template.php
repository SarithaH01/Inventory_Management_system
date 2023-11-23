
<?php
// Generate a unique invoice number
$timestamp = time(); // Current timestamp
$random_number = mt_rand(1000, 9999); // Random 4-digit number
$invoice_number = 'INV-' . substr($timestamp, 6) . $random_number;

// Rest of your existing code...

?>

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
            background-color: #f5f5f5; /* Set the background color for the entire invoice */
        }
        .invoice {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* Set the background color for the invoice content */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
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
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 10px;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
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
                <!-- Update the Invoice ID section -->
<tr>
    <th>Invoice Number:</th>
    <td><?php echo $invoice_number; ?></td>
    <th>Date:</th>
    <td><?php echo $date; ?></td>
</tr>

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
                    <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                    <td><?php echo (int)$sale['qty']; ?></td>
                    <td>&#8377;  <?php echo number_format((float)$sale['price'], 2); ?></td>
                    <td>&#8377;  <?php echo number_format((float)$sale['price'] * (int)$sale['qty'], 2); ?></td>
                </tr>
                <tr>
                    <th colspan="3">Subtotal:</th>
                    <td>&#8377;  <?php echo number_format((float)$sale['price'] * (int)$sale['qty'], 2); ?></td>
                </tr>
                <tr>
                    <th colspan="3">CGST (9%):</th>
                    <td>&#8377;  <?php echo number_format($cgst_amount, 2); ?></td>
                </tr>
                <tr>
                    <th colspan="3">SGST (9%):</th>
                    <td>&#8377;  <?php echo number_format($sgst_amount, 2); ?></td>
                </tr>
            </table>
        </div>
        <div class="total">
            <p>Total: &#8377;  <?php echo number_format($total_with_tax, 2); ?></p>
        </div>
        <div class="section print-button">
            <!-- Add a Print button -->
            <button onclick="window.print()">Print Invoice</button>
        </div>
        <div class="section">
            <p>Thank you!</p>
        </div>
    </div>
</body>
</html>