<?php
$page_title = 'Edit Product';
require_once('includes/load.php');

// Check if the user has permission to view this page
page_require_level(2);

$product_id = (int)$_GET['id']; // Product ID
$product = find_by_id('products', $product_id); // Fetch the product
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Fetch product specifications
$all_specifications = find_specifications_by_product_id($product_id);

if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}

if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = (int)$_POST['product-categorie'];
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));

    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }

    // Update product information
    $query = "UPDATE products SET";
    $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}', media_id='{$media_id}'";
    $query .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);

    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Product updated");
      redirect('edit_product.php?id=' . $product_id, false); // Redirect back to the edit page
    } else {
      $session->msg('d', 'Sorry, failed to update product.');
      redirect('edit_product.php?id=' . $product_id, false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?id=' . $product_id, false);
  }
}

if (isset($_POST['update-specifications'])) {
  // Update product specifications if the specifications form is submitted
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
    redirect('edit_product.php?id=' . $product_id, false); // Redirect back to the edit page
  } else {
    $session->msg('d', "No specifications provided.");
    redirect('edit_product.php?id=' . $product_id, false);
  }
}

// Function to fetch product specifications
function find_specifications_by_product_id($product_id) {
  global $db;
  $sql = "SELECT * FROM product_specifications WHERE product_id = {$product_id}";
  return $db->query($sql);
}

include_once('layouts/header.php');
?>

<!-- Your HTML form code here, which includes the specifications fields -->
<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
</head>
<body>
  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Edit Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-7">
          <form method="post" action="edit_product.php?id=<?php echo $product_id; ?>">
            <!-- Product Fields -->
            <div class="form-group">
              <label for="product-title">Product Title</label>
              <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>">
            </div>
            <div class="form-group">
              <label for="product-categorie">Category</label>
              <select class="form-control" name="product-categorie">
                <option value="">Select a category</option>
                <?php foreach ($all_categories as $cat) : ?>
                  <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']) : echo "selected"; endif; ?>>
                    <?php echo remove_junk($cat['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="product-quantity">Quantity</label>
              <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
            </div>
            <div class="form-group">
              <label for="buying-price">Buying Price</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="saleing-price">Selling Price</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="product-photo">Product Photo</label>
              <select class="form-control" name="product-photo">
                <option value="">No image</option>
                <?php foreach ($all_photo as $photo) : ?>
                  <option value="<?php echo (int)$photo['id']; ?>" <?php if ($product['media_id'] === $photo['id']) : echo "selected"; endif; ?>>
                    <?php echo $photo['file_name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- End of Product Fields -->

            <!-- Specifications Fields -->
            <div class="form-group">
              <label for="specifications">Specifications</label>
              <div id="specifications-container">
                <?php if ($all_specifications) : ?>
                  <?php foreach ($all_specifications as $spec) : ?>
                    <div class="row">
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="specification_name[]" placeholder="Specification Name" value="<?php echo $spec['specification_name']; ?>">
                      </div>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="specification_value[]" placeholder="Specification Value" value="<?php echo $spec['specification_value']; ?>">
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <button type="button" id="add-specification" class="btn btn-primary">Add Specification</button>
            </div>
            <!-- End of Specifications Fields -->

            <div class="form-group">
              <button type="submit" name="product" class="btn btn-primary">Update Product</button>
              <button type="submit" name="update-specifications" class="btn btn-primary">Update Specifications</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<?php include_once('layouts/footer.php'); ?>

<script>
  document.getElementById("add-specification").addEventListener("click", function() {
    var specNameInput = document.createElement("input");
    specNameInput.setAttribute("type", "text");
    specNameInput.setAttribute("class", "form-control");
    specNameInput.setAttribute("name", "specification_name[]");
    specNameInput.setAttribute("placeholder", "Specification Name");

    var specValueInput = document.createElement("input");
    specValueInput.setAttribute("type", "text");
    specValueInput.setAttribute("class", "form-control");
    specValueInput.setAttribute("name", "specification_value[]");
    specValueInput.setAttribute("placeholder", "Specification Value");

    var specDiv = document.createElement("div");
    specDiv.setAttribute("class", "row");
    specDiv.appendChild(specNameInput);
    specDiv.appendChild(specValueInput);

    var specificationsContainer = document.getElementById("specifications-container");
    specificationsContainer.appendChild(specDiv);
  });
</script>