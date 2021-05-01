<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
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
//Check if there is a message from previous redirected page. Alert the message if exists.
if(isset($_SESSION["newmessage"]) && $_SESSION["newmessage"] === true){
    echo "<script type='text/javascript'>alert('" . $_SESSION["message"] . "');</script>";
    $_SESSION["newmessage"] = false;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Internship</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .elevate {
            box-shadow:
            0 1px 2px rgba(0,0,0,0.07),
            0 2px 4px rgba(0,0,0,0.07),
            0 4px 8px rgba(0,0,0,0.07),
            0 8px 16px rgba(0,0,0,0.07),
            0 16px 32px rgba(0,0,0,0.07),
            0 32px 64px rgba(0,0,0,0.07);
        }
    </style>
    <script type="text/javascript">
        //Cancel application to company with certain id using jquery request.
        function CancelApplication(cid){
            const url = "cancel.php";
            const data = {
                companyid: cid
            }
            $.post(url, data, function(data, status){
                location.reload();
            });
        }
    </script>
</head>

<body>
<nav class="navbar navbar-light navbar-expand-md">
    <div class="container-fluid"><a class="navbar-brand" href="index.php"
                                    style="font-weight: bold;color: var(--blue);">Internship
            Management System</a>
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse d-xl-flex justify-content-xl-end" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" style="margin-right: 10px;">
                    <?php
                        echo "<h4 class='mt-1'>Hello " . $_SESSION["username"] . "</h4>"
                    ?>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-primary" type="button">Log Out<i class="fa fa-sign-out"
                                                                            style="margin-left: 5px;"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="row m-0" style="background: #f0f8ff; width: 100vw;">
    <div class="col p-0">
        <div class="container rounded elevate" style="background-color: white; padding: 20px; margin-top: 5%; margin-bottom: 5%;">
            <h2>Applied Internships</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Company Name</th>
                        <th>Quota</th>
                        <th>Cancel</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //List the companies that are already applied by current user
                    if ($result = $con->query("SELECT cid, cname, quota FROM company WHERE cid in (SELECT cid FROM apply WHERE sid=" . $_SESSION["id"] . ");")) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $argument = '"' . $row["cid"] . '"';
                                echo "<tr> <td>" . $row["cid"] . "</td> <td>" . $row["cname"] . "</td> <td>" . $row["quota"] . "</td> <td><button onclick='CancelApplication(" . $argument . ")' class='btn btn-danger btn-sm' type='button'><i class='fa fa-remove'></i></button></td> </tr>";
                            }
                        }
                        $result->free_result();
                    } else {
                        echo "<script type='text/javascript'>alert('Listing failed for some internal issue.');</script>";
                    }
                    $con->close();
                    ?>
                    </tbody>
                </table>
                <a href="apply.php" class="btn btn-primary" type="button">Apply for New Internship</a>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>