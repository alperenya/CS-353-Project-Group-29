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
    <title>doctor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background: rgb(240,248,255);">
    <nav class="navbar navbar-light navbar-expand-md" style="background: #ffffff;">
        <div class="container-fluid"><a class="navbar-brand" href="#" style="font-weight: bold;color: var(--blue);">Hospital Management System</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse d-xl-flex justify-content-xl-end" id="navcol-1">
                <ul class="navbar-nav">
                    <li class="nav-item"><button class="btn btn-primary" type="button">Log Out<i class="fa fa-sign-out" style="margin-left: 5px;"></i></button></li>
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
                            <div class="col text-right d-xl-flex justify-content-xl-end align-items-xl-center"><input type="month" style="margin-right: 10px;"><button class="btn btn-primary btn-sm" type="button"><span>Show Schedule&nbsp;</span><i class="fa fa-arrow-right"></i></button></div>
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
                            <tr>
                                <td>Ahmet Ahi</td>
                                <td>01.02.1997</td>
                                <td class="text-right"><button class="btn btn-primary btn-sm" type="button"><span>Go To Details&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr style="background: rgb(255,151,161);">
                                <td>Cancelled slot</td>
                                <td>02.02.1997</td>
                                <td class="text-right"><button class="btn btn-success btn-sm" type="button"><span>Re-enable slot&nbsp;</span><i class="fa fa-check"></i></button></td>
                            </tr>
                            <tr>
                                <td>Alperen Yalcin</td>
                                <td>03.02.1997</td>
                                <td class="text-right"><button class="btn btn-primary btn-sm" type="button"><span>Go To Details&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>Mehmet Gündoğdu</td>
                                <td>04.02.1997</td>
                                <td class="text-right"><button class="btn btn-primary btn-sm" type="button"><span>Go To Details&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>Cancel specific date slot:</td>
                                <td><input type="date"></td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Cancel Slot&nbsp;</span><i class="fa fa-times"></i></button></td>
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