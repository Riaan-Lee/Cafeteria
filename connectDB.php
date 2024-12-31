<?php
    // connect to db server
    $server = "localhost";
    $serveruser = "root";
    $serverpassword = "";
    $db = "Dinner-on-Us"; // Adjusted database name

    // establish the connection
    $connect = mysqli_connect($server, $serveruser, $serverpassword, $db);

    // confirm connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Connection successful message (optional, remove in production)
    // echo "Connection successful";
?>
