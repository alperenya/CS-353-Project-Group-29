<?php
// Initialize the session.
session_start();

// Check if the user is logged in, if not then redirect to index page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

//Required parameters for connection.
$servername = "dijkstra.ug.bcc.bilkent.edu.tr";
$username = "oguzhan.angin";
$password = "3366Ioem";
$db = "oguzhan_angin";

//Connect to remote database at djikstra using credentials.
$con = new mysqli($servername, $username, $password, $db);

//Check if connection is successfull
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
//If the request method is post, apply the company with given cid.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Apply($con);
} else {
    CheckApplicationNumber($con);
}
//Check if the user has already applied for 3 or more companies.
function CheckApplicationNumber($con)
{
    if ($result = $con->query("SELECT cid FROM apply WHERE sid='" . $_SESSION["id"] . "';")) {
        if ($result->num_rows >= 3) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION["newmessage"] = true;
                $_SESSION["message"] = 'Already applied for 3 companies. Please cancel at least one in order to proceed.';
                $result->free_result();
                $con->close();
                header("location: management.php");
                exit;
            }
        }
        $result->free_result();
    } else {
        echo "<script type='text/javascript'>alert('Application failed for some internal issue.');</script>";
        return;
    }
}

//Check the validity of the application and complete apply if it is valid.
function Apply($con)
{
    //Check if company id received successfully.
    if (!array_key_exists("cid", $_POST)) {
        echo "<script type='text/javascript'>alert('Could not receive id of the applied company.');</script>";
        return;
    }

    //Sanitize the input.
    $sanitizedcid = htmlspecialchars($_POST["cid"]);

    //Check if there is quota and user is not applied for the company.
    if ($result = $con->query("SELECT cid FROM company WHERE quota>0 AND cid='" . $sanitizedcid . "' AND cid NOT IN (SELECT cid FROM apply WHERE sid=" . $_SESSION["id"] . ") LIMIT 1;")) {
        if ($result->num_rows > 0) {
            //Apply the company by inserting the apply tuple to apply table.
            if ($con->query("INSERT INTO apply VALUES (" . $_SESSION["id"] . ", '" . $sanitizedcid . "');") === true) {
                echo "<script type='text/javascript'>alert('Successfully applied to company with id: " . $sanitizedcid . "');</script>";

                //Decrease the company quota
                if ($con->query("UPDATE company SET quota=quota-1 WHERE cid='" . $sanitizedcid . "';") === TRUE) {
                    echo "<script type='text/javascript'>console.log('Successfully decreased quota of: " . $sanitizedcid . "');</script>";
                } else {
                    echo "<script type='text/javascript'>console.log('Quota decrease failed to for company with id: " . $sanitizedcid . "');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Application failed for some internal issue.');</script>";
                return;
            }
            $result->free_result();
        } else {
            echo "<script type='text/javascript'>alert('Application failed. Check if you have already applied for the company.');</script>";
            $result->free_result();
            return;
        }
        $result->free_result();
    } else {
        echo "<script type='text/javascript'>alert('Application validity check failed for some internal issue.');</script>";
        return;
    }

    //Check if user has 3 applications.
    if ($result = $con->query("SELECT cid FROM apply WHERE sid='" . $_SESSION["id"] . "';")) {
        if ($result->num_rows >= 3) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION["newmessage"] = true;
                $_SESSION["message"] = "Successfully applied to company with id: " . $sanitizedcid;
                $result->free_result();
                $con->close();
                header("location: management.php");
                exit;
            }
        }
        $result->free_result();
    } else {
        echo "<script type='text/javascript'>alert('Application failed for some internal issue.');</script>";
        return;
    }
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.07),
            0 2px 4px rgba(0, 0, 0, 0.07),
            0 4px 8px rgba(0, 0, 0, 0.07),
            0 8px 16px rgba(0, 0, 0, 0.07),
            0 16px 32px rgba(0, 0, 0, 0.07),
            0 32px 64px rgba(0, 0, 0, 0.07);
        }
    </style>
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
                    //To show logged in user.
                    echo "<h4 class='mt-1'>Hello " . $_SESSION["username"] . "</h4>"
                    ?>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-primary"">Log Out<i class="fa fa-sign-out"
                                                                            style="margin-left: 5px;"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="row m-0" style="background: #f0f8ff; width: 100vw;">
    <div class="col p-0">
        <div class="container rounded elevate"
             style="background-color: white; padding: 20px; margin-top: 5%; margin-bottom: 5%;">
            <h2>Available Companies</h2>
            <div class="row">
                <div class="col d-flex">
                    <form class="input-group my-3 w-50" method="post">
                        <input name="cid" type="text" class="form-control" placeholder="Company ID"
                               aria-label="Company ID"
                               aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="button-addon2">Apply</button>
                        </div>
                    </form>
                    <a href="management.php" class="btn btn-outline-dark my-3" style=" margin-left: auto;"><i
                                class="fa fa-chevron-circle-left" style="margin-right: 5px;"></i><span>Back</span></a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Company Name</th>
                        <th>Quota</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //List the companies that have available quota and the user has not applied yet.
                    if ($result = $con->query("SELECT cid, cname, quota FROM company as c1 WHERE quota>0 AND c1.cid NOT IN (SELECT cid FROM apply WHERE sid=" . $_SESSION["id"] . ");")) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr> <td>" . $row["cid"] . "</td> <td>" . $row["cname"] . "</td> <td>" . $row["quota"] . "</td> </tr>";
                            }
                        }
                        $result->free_result();
                    } else {
                        echo "<script type='text/javascript'>alert('Cannot retrieve available companies');</script>";
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>