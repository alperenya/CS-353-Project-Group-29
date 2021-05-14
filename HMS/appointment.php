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
    <title>patient</title>
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
                    <li class="nav-item"><button class="btn btn-primary" type="button" style="margin-right: 10px;"><i class="fa fa-arrow-left" style="margin-left: 5px;"></i>&nbsp;Back</button><button class="btn btn-primary" type="button">Log Out<i class="fa fa-sign-out" style="margin-left: 5px;"></i></button></li>
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
                            <div class="col-xl-12">
                                <h2>Symptoms:</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Symptom name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Headache</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>Chestpain</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td><select>
                                        <optgroup label="Available slots:">
                                            <option value="12" selected="">Sore throat</option>
                                            <option value="13">Coughing</option>
                                            <option value="14">Date 3</option>
                                        </optgroup>
                                    </select></td>
                                <td class="text-right"><button class="btn btn-success btn-sm" type="button"><span>Add Symptom&nbsp;</span><i class="fa fa-check"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin: 0px;">
        <div class="col">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-xl-12">
                                <h2>Diagnosis:</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Disease name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cyrogyrosis</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>High eye number</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td><select>
                                        <optgroup label="Available slots:">
                                            <option value="12" selected="">Covid-20</option>
                                            <option value="13">Cancer</option>
                                            <option value="14">Date 3</option>
                                        </optgroup>
                                    </select></td>
                                <td class="text-right"><button class="btn btn-success btn-sm" type="button"><span>Add Diagnosis Disease&nbsp;</span><i class="fa fa-check"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin: 0px;">
        <div class="col">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-xl-12">
                                <h2>Tests:</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Test name</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Blood test</td>
                                <td>Finalized</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td>Urine test</td>
                                <td>Assigned</td>
                                <td class="text-right"><button class="btn btn-danger btn-sm" type="button"><span>Remove&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            <tr>
                                <td><select>
                                        <optgroup label="Available slots:">
                                            <option value="12" selected="">MR</option>
                                            <option value="13">Eye Test</option>
                                            <option value="14">Date 3</option>
                                        </optgroup>
                                    </select></td>
                                <td class="text-right" colspan="2"><button class="btn btn-success btn-sm" type="button"><span>Request Test&nbsp;</span><i class="fa fa-check"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modal Title</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p>The content of your modal.</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-dismiss="modal">Close</button><button class="btn btn-primary" type="button">Save</button></div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>