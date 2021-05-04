<?php
    //Required parameters for connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "HMS";
    //Connect database using credentials
    $con = new mysqli($servername, $username, $password, $db);

    //Check if connection is successfull
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
?>