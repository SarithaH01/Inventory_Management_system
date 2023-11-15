<?php

// includes/user_functions.php

function find_user_by_username($username) {
    global $db;

    $username = $db->escape($username);

    $query = "SELECT * FROM users WHERE username = '{$username}' LIMIT 1";
    $result = $db->query($query);

    if ($result && $db->num_rows($result) == 1) {
        return $db->fetch_assoc($result);
    } else {
        return false;
    }
}

function generate_reset_token() {
    // Generate a unique reset token (e.g., a random string)
    return bin2hex(random_bytes(32));
}

function update_reset_token($user_id, $reset_token) {
    global $db;

    $reset_token = $db->escape($reset_token);

    $query = "UPDATE users SET reset_token = '{$reset_token}' WHERE id = {$user_id}";
    $result = $db->query($query);

    return $result;
}

function send_password_reset_email($email, $reset_token) {
    // Implement the email sending logic to send a reset link to the user's email
    // You can use a library like PHPMailer to send emails
}

function update_user_password($username, $new_password) {
    global $db;

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET password = '{$hashed_password}' WHERE username = '{$username}'";
    $result = $db->query($query);

    return $result;
}

?>