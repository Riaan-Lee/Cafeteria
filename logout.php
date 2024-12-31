<?php
    // logging out
    session_start();
    session_destroy();
    //alternative way os 'sessiom_unset();

    //Redirect user to login page of logout is completed
    header("Location:login.php");
?>