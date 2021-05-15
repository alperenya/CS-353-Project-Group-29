<?php
include("config.php");

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["type"] != "doctor") {
    header("location: index.php");
    exit;
}

//Check if there is a message from previous redirected page. Alert the message if exists.
if (isset($_SESSION["newmessage"]) && $_SESSION["newmessage"] === true) {
    echo "<script type='text/javascript'>alert('" . $_SESSION["message"] . "');</script>";
    $_SESSION["newmessage"] = false;
}

$id = $_SESSION['person_id'];

$resMonthPick = "";
$resMonthPick2 = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resMonthPick = ShowSchedule($con, $id);
    $resMonthPick2 = ShowCancel($con, $id);
    Reenable($con, $id);
    Cancel($con, $id);
}

function Reenable($con, $id)
{
    if (array_key_exists("date", $_POST)) {
        $date = htmlspecialchars($_POST["date"]);

        $sqlDate = "DELETE FROM schedule WHERE occupation_type = 'Cancel' and date = '$date' and person_id = '$id'";

        $con->query($sqlDate);
    }
}

function Cancel($con, $id)
{
    if (array_key_exists("cancelDate", $_POST)) {
        $date = htmlspecialchars($_POST["cancelDate"]);

        $sqlDate2 = "INSERT INTO schedule VALUES ('$id', '$date', 'Cancel');";
        
        $con->query($sqlDate2);
    }
}

function ShowSchedule($con, $id)
{
    if (array_key_exists("month", $_POST)) {
        $month = htmlspecialchars($_POST["month"]);
        $month = date("m", strtotime($month));

        $sqlMonthPick = "SELECT P.first_name, P.last_name, A.date, exam_id
FROM persons P, appointment A
WHERE P.person_id IN (SELECT patient_id
                      FROM appointment_of
                      WHERE doctor_id = '$id' AND exam_id = A.exam_id) and MONTH(date) = '$month';";

        $resMonthPick = $con->query($sqlMonthPick);
        return $resMonthPick;
    }
    else{

        $sqlMonthPick = "SELECT P.first_name, P.last_name, A.date, exam_id
FROM persons P, appointment A
WHERE P.person_id IN (SELECT patient_id
                      FROM appointment_of
                      WHERE doctor_id = '$id' AND exam_id = A.exam_id) and MONTH(date) = 1;";

        $resMonthPick = $con->query($sqlMonthPick);
        return $resMonthPick;
    }
}

function ShowCancel($con, $id)
{
    if (array_key_exists("month", $_POST)) {
        $month = htmlspecialchars($_POST["month"]);
        $month = date("m", strtotime($month));


        $sqlMonthPick2 = "SELECT occupation_type, date
FROM schedule
WHERE occupation_type = 'Cancel' and MONTH(date) = '$month' and person_id = '$id';";

        $resMonthPick2 = $con->query($sqlMonthPick2);
        return $resMonthPick2;
    }
    else{
        $sqlMonthPick2 = "SELECT occupation_type, date
FROM schedule
WHERE occupation_type = 'Cancel' and MONTH(date) = 1 and person_id = '$id';";

        $resMonthPick2 = $con->query($sqlMonthPick2);
        return $resMonthPick2;
    }

}

$con->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>doctor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background: rgb(240,248,255);">
<nav class="navbar navbar-light navbar-expand-md" style="background: #ffffff;">
    <div class="container-fluid"><a class="navbar-brand" href="#" style="font-weight: bold;color: var(--blue);">Hospital
            Management System</a>
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse d-xl-flex justify-content-xl-end" id="navcol-1">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-primary" type="button">Log Out<i class="fa fa-sign-out"
                                                                                         style="margin-left: 5px;"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="row" style="margin: 0px;">
    <div class="col">
        <div class="container">
            <div class="row" style="margin-top: 5%;">
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <h2>Appointments and Cancelled Slots:</h2>
                        </div>
                        <form method="post" id="patient-form">
                            <div class="col text-right d-xl-flex justify-content-xl-end align-items-xl-center"><input
                                        type="month" name="month" style="margin-right: 10px;">
                                <button class="btn btn-primary btn-sm" type="submit"><span>Show Schedule&nbsp;</span><i
                                            class="fa fa-arrow-right"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Patient Name/Slot type:</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($resMonthPick2 && $row1 = $resMonthPick2->fetch_assoc()) : ?>
                    <tr>
                    <tr style="background: rgb(255,219,222);">
                        <td><?php echo $row1["occupation_type"]; ?></td>
                        <td><?php echo $row1["date"]; ?></td>
                        <form action="" method="post">
                            <input name="date" style="display: none;" value=<?php echo $row1["date"]; ?>>
                            <td class="text-right">
                                <button class="btn btn-success btn-sm" type="submit"><span>Re-enable&nbsp;</span><i
                                            class="fa fa-check"></i></button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
                <?php
                while ($resMonthPick && $row1 = $resMonthPick->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row1["first_name"], " ", $row1["last_name"]; ?></td>
                        <td><?php echo $row1["date"]; ?></td>
                        <form action="appointment.php" method="get">
                            <input name="exam_id" style="display: none;" value=<?php echo $row1["exam_id"]; ?>>
                            <td class="text-right">
                                <button class="btn btn-primary btn-sm" type="submit" name="detail"><span>Go To Details&nbsp;</span><i
                                            class="fa fa-arrow-right"></i></button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td>Cancel specific date slot:</td>
                    <form action="" method="post">
                        <td><input type="date" name="cancelDate"></td>
                        <td class="text-right">
                            <button class="btn btn-danger btn-sm" type="submit"><span>Cancel Slot&nbsp;</span><i
                                        class="fa fa-times"></i></button>
                        </td>
                    </form>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>