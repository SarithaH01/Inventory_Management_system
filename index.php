<?php
ob_start();
require_once('includes/load.php');
require_once('user_functions.php');
require_once('includes/functions.php');

if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
}

$msg = array(); // Initialize an array to store messages

// Function to validate password
function validate_password($password) {
    // Password should be at least 8 characters long, start with a capital letter,
    // contain at least 1 special character, and at least 1 number.
    return preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate user credentials.
        $user = validate_user_credentials($username, $password);

        // Validate password criteria
        if (!validate_password($password)) {
            $msg['danger'] = "Password should be at least 8 characters long, start with a capital letter, contain at least 1 special character, and at least 1 number.";
        } elseif ($user) {
            // Login successful. Store user information in the session and redirect.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect to the home page or any other page you prefer.
            redirect('home.php', false);
        } else {
            // Add an error message to the array
            $msg['danger'] = "Username/password is wrong. Please enter correct username/password.";
        }
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<body style="background-image: url('img5.jpg'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="login-page">
        <div class="text-center">
            <h1>Login Panel</h1>
            <h4>Inventory Management System</h4>
        </div>
        <?php echo display_msg($msg); ?>
        <form method="post" action="index.php" class="clearfix">
            <div class="form-group">
                <label for="username" class="control-label">Username</label>
                <input type="name" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="Password" class="control-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
            </div>

            <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>
        </form>
    </div>
</body>

<?php include_once('layouts/footer.php'); ?>