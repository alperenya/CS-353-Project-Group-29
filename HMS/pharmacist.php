<?php
include("config.php");

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}


//Check if connection is successfull
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
//Check if there is a message from previous redirected page. Alert the message if exists.
if(isset($_SESSION["newmessage"]) && $_SESSION["newmessage"] === true){
    echo "<script type='text/javascript'>alert('" . $_SESSION["message"] . "');</script>";
    $_SESSION["newmessage"] = false;
}
?>

<html lang="en">
<head>

</head>
<body>
  eczane
</body>
</html>