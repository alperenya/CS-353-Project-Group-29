<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /internship/index.php");
    exit;
}


//Required parameters for connection
$servername = "dijkstra.ug.bcc.bilkent.edu.tr";
$username = "oguzhan.angin";
$password = "3366Ioem";
$db = "oguzhan_angin";

//Connect to remote database at djikstra using credentials
$con = new mysqli($servername, $username, $password, $db);

//Check if connection is successfull
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

//If the request method is post, cancel the application with given cid.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    CancelApplication($con);
    $con->close();
}


function CancelApplication($con)
{
    //Check if username and password received successfully
    if (!array_key_exists("companyid", $_POST)) {
        echo "<script type='text/javascript'>alert('Could not receive company id.');</script>";
        return;
    }

    $sanitizedcid = htmlspecialchars($_POST["companyid"]);

    if ($con->query("DELETE FROM apply WHERE sid=" . $_SESSION["id"] . " AND cid='" . $sanitizedcid . "';") === TRUE) {
        if ($con->query("UPDATE company SET quota=quota+1 WHERE cid='" . $sanitizedcid . "';") === TRUE) {
            echo "<script type='text/javascript'>console.log('Successfully incremented quota of: " . $sanitizedcid . "');</script>";
        } else {
            echo "<script type='text/javascript'>console.log('Quota increment failed to for company with id: " . $sanitizedcid . "');</script>";
        }

        $_SESSION[newmessage] = true;
        $_SESSION[message] = "Application cancelled successfully.";
        return;
    } else {
        $_SESSION[newmessage] = true;
        $_SESSION[message] = "Application cancellation failed";
        return;
    }
}

?>