<?php 

/*
    If we want to do anything on a database, we need to connect to it AND be authorised to use it! 
    Here, we're going to create a connection string. We will include this in every subsequent page in our website.

    Because these credentials are HARD CODED, this is a very dangerous file. We need to make sure that it never goes into public_html (only our data/ directory on our server).
*/

define("DB_SERVER", "mysql");
define("DB_USER", "student");
define("DB_PASS", "student");
define("DB_NAME", "dmit2025");

function db_connect() {
    // This creates a connection handle, which opens a line of communication with the server.
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        // There are three places we need to check to make sure we have the correct character encoding: the database, our HTML document, and here, in our MySQLi connection handle.
        $connection->set_charset('utf8mb4');
        return $connection;
    }
}

function db_disconnect($connection) {
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

?>