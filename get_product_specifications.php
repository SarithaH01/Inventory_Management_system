<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Specifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Product Specifications</h1>
    <?php
    $pdo = new PDO('mysql:host=localhost;dbname=inventory_system', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

    if ($product_id) {
        $sql = "SELECT * FROM product_specifications WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<table>';
            echo '<tr><th>Specification Name</th><th>Specification Value</th></tr>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['specification_name'] . '</td>';
                echo '<td>' . $row['specification_value'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p style="text-align: center; color: #333;">This product has no specifications.</p>';
        }
    } else {
        echo '<p style="text-align: center; color: #333;">Product ID not provided.</p>';
    }
    ?>
</body>
</html>
