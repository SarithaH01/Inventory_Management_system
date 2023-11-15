<?php
ob_start();
require_once('includes/load.php');
require_once('includes/user_functions.php');

// Include the validate_password function
function validate_password($password) {
    // Password should be at least 8 characters long, start with a capital letter,
    // contain at least 1 special character, and at least 1 number.
    return preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password) === 1;
}

if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
}

$msg = array(); // Initialize the message variable as an array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['new_password'])) {
        $username = $_POST['username'];
        $new_password = $_POST['new_password'];

        // Validate the new password
        if (validate_password($new_password)) {
            // Update the user's password in the database
            $update_success = update_user_password($username, $new_password);

            if ($update_success) {
                $msg['success'] = "Password successfully reset. You can now log in with your new password.";
                redirect('index.php', false);
            } else {
                $msg['danger'] = "Failed to reset the password. Please try again.";
            }
        } else {
            $msg['danger'] = "Password must be at least 8 characters long, start with a capital letter, contain at least 1 special character, and at least 1 number.";
        }
    }
}
?>
<?php include_once('layouts/header.php'); ?>

<body style="background-image: url('img5.jpg'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="login-page">
        <div class="text-center">
            <h1>Forgot Password</h1>
            <h4>Inventory Management System</h4>
        </div>
        <?php echo display_msg($msg); ?>
        <form method="post" action="forgot_password.php" class="clearfix">
            <div class="form-group">
                <label for "username" class="control-label">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for "new_password" class="control-label">New Password</label>
                <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger" style="border-radius: 0%">Reset Password</button>
            </div>
        </form>
    </div>
</body>
<?php include_once('layouts/footer.php'); ?>