<?php
require_once('includes/load.php');

// Check if the user has permission to update specifications
page_require_level(2);

if (isset($_POST['update-specifications'])) {
  $product_id = (int)$_POST['product-id'];
  $specification_names = $_POST['specification_name'];
  $specification_values = $_POST['specification_value'];

  // Clear existing specifications
  $db->query("DELETE FROM product_specifications WHERE product_id = $product_id");

  // Insert updated specifications
  if (!empty($specification_names)) {
    foreach ($specification_names as $index => $spec_name) {
      $spec_name = $db->escape($spec_name);
      $spec_value = $db->escape($specification_values[$index]);

      // Insert the specification if the name is not empty
      if (!empty($spec_name)) {
        $insert_sql = "INSERT INTO product_specifications (product_id, specification_name, specification_value) ";
        $insert_sql .= "VALUES ({$product_id}, '$spec_name', '$spec_value')";
        $db->query($insert_sql);
      }
    }

    $session->msg('s', "Product specifications updated");
    redirect('edit_product.php?id=' . $product_id, false);
  } else {
    $session->msg('d', "No specifications provided.");
    redirect('edit_product.php?id=' . $product_id, false);
  }
} else {
  $session->msg('d', 'Invalid request.');
  redirect('edit_product.php', false);
}
?>
