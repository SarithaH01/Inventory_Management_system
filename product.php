<?php
$page_title = 'All Product';
require_once('includes/load.php');
page_require_level(2);
$products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Photo</th>
                            <th>Product Title</th>
                            <th class="text-center" style="width: 10%;">Categories</th>
                            <th class="text-center" style="width: 10%;">In-Stock</th>
                            <th class="text-center" style="width: 10%;">Buying Price</th>
                            <th class="text-center" style="width: 10%;">Selling Price</th>
                            <th class="text-center" style="width: 10%;">Product Added</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php if ($product['media_id'] === '0') : ?>
                                        <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                                    <?php else : ?>
                                        <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo remove_junk($product['name']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                <td class="text-center"><?php echo read_date($product['date']); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// email_functions.php (Place this code in a separate file)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/Exception.php';
require 'vendor/PHPMailer/PHPMailer.php';
require 'vendor/PHPMailer/SMTP.php';

 function send_low_stock_email($product_name) {
    $mail = new PHPMailer(true);
    try {
        // Configure SMTP for your mail server
        $mail->isSMTP();
        $mail->Host = 'http://php.net/smtp-port'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'www.sarithahari124@gmail.com'; // Replace with your email
        $mail->Password = '15801580'; // Replace with your password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $to = '22mp1971@rit.ac.in'; // Replace with the admin's email address
        $subject = 'Low Stock Alert';
        $message = 'Product "' . $product_name . '" is low on stock.';
        $mail->setFrom('www.sarithahari124@gmail.com', 'Saritha'); // Replace with your name and email
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>

<?php include_once('layouts/footer.php'); ?>