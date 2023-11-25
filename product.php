<?php
$page_title = 'All Product';
require_once('includes/load.php');
page_require_level(2);

// Fetch distinct categories for the dropdown
$categories = find_all('categories');

// Array to store low stock products
$low_stock_products = array();

// Function to get product category name
function get_product_category_name($product) {
    $key = isset($product['Categories']) ? 'Categories' : 'categorie'; // Adjust 'categorie' based on your database column name
    return isset($product[$key]) ? remove_junk($product[$key]) : '';
}

// Function to search for products
function search_products($category_id, $product_title) {
    global $db;

    // Initialize the WHERE clause
    $where_clause = " WHERE 1 = 1";

    // Apply category filter if selected
    if (!empty($category_id)) {
        $where_clause .= " AND p.categorie_id = {$category_id}";
    }

    // Apply product title filter if entered
    if (!empty($product_title)) {
        $where_clause .= " AND p.name LIKE '%{$product_title}%'";
    }

    $sql = "SELECT p.*, c.name AS Categories FROM products p";
    $sql .= " LEFT JOIN categories c ON p.categorie_id = c.id";
    $sql .= $where_clause;
    $sql .= " ORDER BY p.id DESC";

    return find_by_sql($sql);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = isset($_POST['category']) ? (int)$_POST['category'] : null;
    $product_title = isset($_POST['product_title']) ? $_POST['product_title'] : null;

    // Use the search parameters to filter the products
    $products = search_products($category_id, $product_title);

    // Check for low stock products
    foreach ($products as $product) {
        if ($product['quantity'] < 20) {
            $low_stock_products[] = $product;
        }
    }
} else {
    // If the form is not submitted, display all products
    $products = join_product_table();

    // Check for low stock products on initial page load
    foreach ($products as $product) {
        if ($product['quantity'] < 20) {
            $low_stock_products[] = $product;
        }
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <!-- Add the search form here -->
                <form method="post" action="product.php" class="form-inline">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select class="form-control" name="category" id="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product_title">Product Title:</label>
                        <input type="text" class="form-control" name="product_title" id="product_title" placeholder="Enter product title">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <!-- End of search form -->
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Photo</th>
                            <th> Product Title </th>
                            <th class="text-center" style="width: 10%;"> Categories </th>
                            <th class="text-center" style="width: 10%;"> In-Stock </th>
                            <th class="text-center" style="width: 10%;"> Buying Price </th>
                            <th class="text-center" style="width: 10%;"> Selling Price </th>
                            <th class="text-center" style="width: 10%;"> Product Added </th>
                            <th class="text-center" style="width: 100px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)) : ?>
                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo count_id(); ?>
                                        <?php if ($product['quantity'] < 20) : ?>
                                            <span class="badge" style="background-color: darkorange; color: white;">Low Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $image_path = "uploads/products/no_image.png";
                                        if (!empty($product['media_id'])) {
                                            $media_id = find_by_id('media', (int)$product['media_id']);
                                            $image_path = "uploads/products/{$media_id['file_name']}";
                                        }
                                        ?>
                                        <img class="img-avatar img-circle" src="<?php echo $image_path; ?>" alt="">
                                    </td>
                                    <td> <?php echo remove_junk($product['name']); ?></td>
                                    <td class="text-center"> <?php echo get_product_category_name($product); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                                    <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                                    <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                            <a href="get_product_specifications.php?product_id=<?php echo (int)$product['id']; ?>" class="btn btn-primary btn-xs" title="View Specifications" data-toggle="tooltip">
                                                View Specifications
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>





<script>
    // Display attractive alert box for low stock products
    window.onload = function () {
        <?php if (!empty($low_stock_products)) : ?>
            var alertContainer = document.createElement('div');
            alertContainer.style.position = 'fixed';
            alertContainer.style.top = '0';
            alertContainer.style.left = '0';
            alertContainer.style.width = '100%';
            alertContainer.style.height = '100%';
            alertContainer.style.display = 'flex';
            alertContainer.style.alignItems = 'center';
            alertContainer.style.justifyContent = 'center';
            alertContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';

            var alertBox = document.createElement('div');
            alertBox.style.width = '300px';
            alertBox.style.padding = '20px';
            alertBox.style.backgroundColor = '#f8d7da'; // Light red background color
            alertBox.style.border = '1px solid #f5c6cb'; // Border color
            alertBox.style.color = '#721c24'; // Text color
            alertBox.style.borderRadius = '5px';
            alertBox.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.2)';
            alertBox.style.textAlign = 'center';

            // Add the alert message to the alert box
            alertBox.innerHTML = '<strong>Attention!</strong><br>Low stock for products:<br>';
            <?php foreach ($low_stock_products as $product) : ?>
                alertBox.innerHTML += "- <?php echo remove_junk($product['name']); ?><br>";
            <?php endforeach; ?>

            // Add OK button
            var okButton = document.createElement('button');
            okButton.textContent = 'OK';
            okButton.style.padding = '10px';
            okButton.style.marginTop = '10px';
            okButton.style.backgroundColor = '#d9534f'; // Red color for the button
            okButton.style.border = 'none';
            okButton.style.color = 'white';
            okButton.style.borderRadius = '5px';
            okButton.style.cursor = 'pointer';

            // Event listener to remove the alert box when OK is clicked
            okButton.addEventListener('click', function () {
                document.body.removeChild(alertContainer);
            });

            // Append the elements to the alert container
            alertBox.appendChild(okButton);
            alertContainer.appendChild(alertBox);

            // Append the alert container to the body
            document.body.appendChild(alertContainer);
        <?php endif; ?>
    };
</script>