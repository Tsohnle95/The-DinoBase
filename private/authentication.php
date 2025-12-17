<?php

session_start();

require_once dirname(__DIR__, 1) . '/data/connect.php';
$connection = db_connect();

/**
 * Authenticates user based on username and password.
 */
function authenticate($username, $password) {
    global $connection;

    $statement = $connection->prepare("SELECT `account_id`, `hashed_pass` FROM group_1_catalogue_admin WHERE `users` = ?;");

    if (!$statement) {
        die("Prepare failed: " . $connection->error);
    }

    $statement->bind_param("s", $username);
    $statement->execute();

    $statement->store_result();

    if ($statement->num_rows > 0) {
        
        $statement->bind_result($account_id, $hashed_pass);
        $statement->fetch();

        if (password_verify($password, $hashed_pass)) {

            // To prevent session hijacking attacks, we can reset the session ID and data.
            session_regenerate_id(TRUE);

            $_SESSION['user_id'] = $account_id;
            $_SESSION['username'] = $username;
            $_SESSION['last_regeneration'] = time();

            return true;

        }

    }

    // If we do not get a match for the username in the database, we'll return FALSE; however, if things do go according to plan, we'll return TRUE in the control structure above.
    return false;
}


/**
 * Check if a user is logged in.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}


/**
 * Redirect the user if they're not authenticated (not logged in).
 */
function require_login() {
    if (!is_logged_in()) {
        // If the user is not logged in, we'll send them to the login page.
        header("Location: login.php");
        // This kills the rest of the script and prevents anything from rendering client-side (no HTML output for a page the user shouldn't see).
        exit();
    }
}

/**
 * Logs the user out.
 */
function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


?>