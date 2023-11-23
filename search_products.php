<?php
// search_products.php

require_once('includes/load.php');
page_require_level(2);

// Get search parameters
$category_id = isset($_POST['category']) ? (int)$_POST['category'] : null;
$product_title = isset($_POST['product_title']) ? $_POST['product_title'] : null;

// Use the search parameters to filter the products
$products = search_products($category_id, $product_title);

// Output the search results
foreach ($products as $product) {
    echo '<tr>';
    echo '<td class="text-center">' . count_id() . '</td>';
    echo '<td>';
    if ($product['media_id'] === '0') {
        echo '<img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">';
    } else {
        echo '<img class="img-avatar img-circle" src="uploads/products/' . $product['image'] . '" alt="">';
    }
    echo '</td>';
    echo '<td>' . remove_junk($product['name']) . '</td>';
    echo '<td class="text-center">' . remove_junk($product['categorie']) . '</td>';
    echo '<td class="text-center">' . remove_junk($product['quantity']) . '</td>';
    echo '<td class="text-center">' . remove_junk($product['buy_price']) . '</td>';
    echo '<td class="text-center">' . remove_junk($product['sale_price']) . '</td>';
    echo '<td class="text-center">' . read_date($product['date']) . '</td>';
    echo '<td class="text-center">';
    echo '<div class="btn-group">';
    echo '<a href="edit_product.php?id=' . (int)$product['id'] . '" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">';
    echo '<span class="glyphicon glyphicon-edit"></span>';
    echo '</a>';
    echo '<a href="delete_product.php?id=' . (int)$product['id'] . '" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">';
    echo '<span class="glyphicon glyphicon-trash"></span>';
    echo '</a>';
    echo '<a href="get_product_specifications.php?product_id=' . (int)$product['id'] . '" class="btn btn-primary btn-xs" title="View Specifications" data-toggle="tooltip">';
    echo 'View Specifications';
    echo '</a>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}
?>
