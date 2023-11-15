<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif; /* Use a font that supports the Rupee symbol */
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
            <tr><th>Invoice ID:</th><td><?php echo $sale['id']; ?></td></tr>
            <tr><th>Product Name:</th><td><?php echo ($sale['product_name'] !== null && $sale['product_name'] !== '' ? htmlspecialchars($sale['product_name']) : "N/A"); ?></td></tr>
            <tr><th>Quantity:</th><td><?php echo (int)$sale['qty']; ?></td></tr>
            <tr><th>Unit Price:</th><td>&#8377;<?php echo number_format((float)$sale['price'], 2); ?></td></tr>
            <tr><th>Total Price:</th><td>&#8377;<?php echo number_format((float)$sale['price'] * (int)$sale['qty'], 2); ?></td></tr>
            <tr><th>Date:</th><td><?php echo $sale['date']; ?></td></tr>
        </table>
        <!-- Optionally, you can include the Print button -->
        <button onclick="printInvoice()">Print</button>
    </div>

    <!-- JavaScript function for printing -->
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</body>
</html>