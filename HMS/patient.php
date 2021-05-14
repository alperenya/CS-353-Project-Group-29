<?php
include("config.php");

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["type"] != "patient") {
    header("location: index.php");
    exit;
}

//Check if there is a message from previous redirected page. Alert the message if exists.
if(isset($_SESSION["newmessage"]) && $_SESSION["newmessage"] === true){
    echo "<script type='text/javascript'>alert('" . $_SESSION["message"] . "');</script>";
    $_SESSION["newmessage"] = false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Patient($con);
}



function Patient($con)
{
    $sqlAppNo = "SELECT MAX(exam_id) FROM appointment_of";
    $resAppNo = $con->query($sqlAppNo);
    $row = $resAppNo->fetch_assoc();
    $index = $row["MAX(exam_id)"] + 1;

    $sqlDocNo ='SELECT MAX(D.person_id) FROM doctors D, department_of DE WHERE D.person_id = DE.person_id AND DE.department_name = "'.$_POST["deps"].'";';
    $resDocNo = $con->query($sqlDocNo);
    $row1 = $resDocNo->fetch_assoc();
    $indexDoctor = $row1["MAX(D.person_id)"];


    $sqlMakeApp = "INSERT INTO appointment_of VALUES('$index', ".$_SESSION['person_id'].", '$indexDoctor' );";
    $resMakeApp = $con->query($sqlMakeApp);

    $date = "";

    if (array_key_exists("datepicker", $_POST)){
    $date = htmlspecialchars($_POST["datepicker"]);
    $sqlMakeApp1 = "INSERT INTO appointment VALUES('$index', '$date' );";
    $resMakeApp1 = $con->query($sqlMakeApp1);

    $sqlMakeApp2 = "INSERT INTO schedule VALUES('$indexDoctor', '$date', 'Appointment' );";
    $resMakeApp2 = $con->query($sqlMakeApp2);
    }


}


$id = $_SESSION['person_id'];
$dep = "";

if (array_key_exists("deps", $_POST)){
    $dep = htmlspecialchars($_POST["deps"]);
}
//show departments
$sqlDep = "SELECT department_name FROM department";
$resDep = $con->query($sqlDep);

$sqlSch = "SELECT date from schedule WHERE person_id IN(SELECT D.person_id FROM doctors D, department_of DE WHERE D.person_id = DE.person_id AND DE.department_name = '$dep')";
$resSch = $con->query($sqlSch);



$sqlApp = "SELECT D.title, P.first_name, P.last_name, DE.department_name, A.date FROM persons P, appointment A, doctors D, department_of DE WHERE A.exam_id IN (SELECT exam_id FROM appointment_of WHERE patient_id = '$id' AND doctor_id = P.person_id AND doctor_id = D.person_id AND doctor_id = DE.person_id)";
$resultApp = $con->query($sqlApp);

$con->close();


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>patient</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
<div class="patient-clean">
        <div class="row" style="margin: 0px;">
            <div class="col">
                <div class="container">
                    <div class="row" style="margin-top: 10%;">
                        <div class="col">
                            <div class="row">
                                <div class="col-xl-5">
                                    <h2>Appointments:</h2>
                                </div>
                                

                                <div class="col d-xl-flex justify-content-xl-end align-items-xl-center">
                                <form method="post" id="patient-form">
                                <select name="deps" style="margin-right: 10px;">
                                    <optgroup label="Departments">
                                        <?php
                                            foreach($resDep as $m)
                                            {
                                            ?>
                                                <option value="<?php echo $m['department_name'];?>"><?php echo $m['department_name'];?></option>
                                            <?php
                                            }
                                        ?>
                                    </optgroup>
                                    
                                </select>
                                <input type="date" name="datepicker" class="form-control datepicker" autocomplete="off">
                                    <button class="btn btn-success btn-sm" type="submit"  id="search"><span>New Appointment&nbsp;</span><i class="fa fa-search"></i></button></form></div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Doctor Name:</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($row1 = $resultApp->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row1["title"], " ", $row1["first_name"], " ", $row1["last_name"]; ?></td>
                                    <td><?php echo $row1["department_name"]; ?></td>
                                    <td><?php echo $row1["date"]; ?></td>
                                    <td><button class="btn btn-primary btn-sm" type="button"><span>Go To Details&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                                </tr>
                            <?php endwhile; ?>
                            

                            <tr id="sche">
                                <td>DOKTOR</td>
                                <td><?php echo $dep ?></td>
                                <td><select>
                                        <optgroup label="Available dates:">
                                            <?php
                                                foreach($resSch as $m)
                                                {
                                                ?>
                                                    <option value="<?php echo $m['date'];?>"><?php echo $m['date'];?></option>
                                                <?php
                                                }
                                            ?>
                                        </optgroup>
                                    </select></td>
                                <td><button class="btn btn-success btn-sm" type="button"><span>Make Appointment</span><i class="fa fa-check"></i></button></td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<!--<script type="text/javascript">
    var disableDates = ["9-11-2019", "14-11-2019", "15-11-2019","2021-05-17"];
      
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        beforeShowDay: function(date){
            dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
            if(disableDates.indexOf(dmy) != -1){
                return false;
            }
            else{
                return true;
            }
        }
    });
</script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>