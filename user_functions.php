<?php
function find_user_by_username($username) {
    global $db;
    $username = $db->escape($username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($query);
    return $db->fetch_assoc($result);
}



function update_user_password($username, $new_password) {
    global $db;
    $username = $db->escape($username);

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $query = "UPDATE users SET password = '{$hashed_password}' WHERE username = '{$username}'";
    return $db->query($query);
}

function validate_user_credentials($username, $password) {
    $user = find_user_by_username($username);

    if ($user && password_verify($password, $user['password'])) {
        return $user; // Return user information if credentials are valid.
    } else {
        return false; // Return false if credentials are invalid.
    }
}
?>