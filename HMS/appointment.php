<?php
include("config.php");

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to index page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

//Check if there is a message from previous redirected page. Alert the message if exists.
if (isset($_SESSION["newmessage"]) && $_SESSION["newmessage"] === true) {
    echo "<script type='text/javascript'>alert('" . $_SESSION["message"] . "');</script>";
    $_SESSION["newmessage"] = false;
}

if (array_key_exists("exam_id", $_GET)) $exam_id = $_GET["exam_id"];
else if (array_key_exists("exam_id", $_POST)) $exam_id = $_POST["exam_id"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (array_key_exists("symptom", $_POST)) {
        AddSymptom($con);
    } else if (array_key_exists("disease", $_POST)) {
        AddDisease($con);
    } else if (array_key_exists("test", $_POST)) {
        AddTest($con);
    }
    else if (array_key_exists("removesymptom", $_POST)) {
        RemoveSymptom($con);
    }
    else if (array_key_exists("removedisease", $_POST)) {
        RemoveDisease($con);
    }
    else if (array_key_exists("removetest", $_POST)) {
        RemoveTest($con);
    }
}
function RemoveSymptom($con){
    echo $_POST["removesymptom"];
}
function RemoveDisease($con){
    echo $_POST["removedisease"];
}
function RemoveTest($con){
    echo $_POST["removetest"];
}
function AddSymptom($con)
{
    $sqlAddSymptom = "INSERT INTO symptoms_of VALUES('" . $_POST["exam_id"] . "', '" . $_POST["symptom"] . "');";
    echo $sqlAddSymptom;
    $addSymptomResult = $con->query($sqlAddSymptom);
    if (!$addSymptomResult) echo $con->connect_error;
}

function AddDisease($con)
{
    $sqlDianosisID = "SELECT diagnosis_id FROM examination_result WHERE exam_id =" . $_POST["exam_id"] . ";";
    $diagnosisIDResult = $con->query($sqlDianosisID);
    if (!$diagnosisIDResult) echo $con->connect_error;
    $diagnosis_id_row = $diagnosisIDResult->fetch_assoc();

    $sqlAddDiagresult = "INSERT INTO diagnosis_result VALUES(" . $diagnosis_id_row["diagnosis_id"] . ", '" . $_POST["disease"] . "', '');";
    $addSymptomResult = $con->query($sqlAddDiagresult);
    if (!$addSymptomResult) echo $con->connect_error;
}

function AddTest($con)
{
    $sqlAddSymptom = "INSERT INTO assigned_tests VALUES(" . $_POST["test"] . ", " . $_POST["exam_id"] . ");";
    $addSymptomResult = $con->query($sqlAddSymptom);
    if (!$addSymptomResult) echo $con->connect_error;
}


$sqlSharedSymptoms = "SELECT name FROM symptoms_of WHERE exam_id = " . $_GET["exam_id"] . ";";
$symptomsResult = $con->query($sqlSharedSymptoms);
if (!$symptomsResult) echo $con->connect_error;

$sqlAllSymptoms = "SELECT name FROM symptoms;";
$allSymptomsResult = $con->query($sqlAllSymptoms);
if (!$allSymptomsResult) echo $con->connect_error;

$sqlDiagnosedDiseases =
    "SELECT name FROM diagnosis_result WHERE diagnosis_id = 
(SELECT diagnosis_id FROM diagnosis WHERE diagnosis_id = 
(SELECT diagnosis_id FROM examination_result WHERE exam_id = '" . $_GET["exam_id"] . "'));";
$diagnosisResult = $con->query($sqlDiagnosedDiseases);
if (!$diagnosisResult) echo $con->connect_error;

$sqlAllDiseases = "SELECT name FROM diseases";
$allDiseasesResult = $con->query($sqlAllDiseases);
if (!$allDiseasesResult) echo $con->connect_error;

$sqlAssignedTests = "SELECT name FROM tests WHERE test_id = (SELECT test_id FROM assigned_tests WHERE exam_id = " . $_GET["exam_id"] . ");";
$assignedTestsResult = $con->query($sqlAssignedTests);
if (!$assignedTestsResult) echo $con->connect_error;

$sqlAllTests = "SELECT * FROM tests";
$allTestsResult = $con->query($sqlAllTests);
if (!$allTestsResult) echo $con->connect_error;

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
    <div class="container-fluid"><a class="navbar-brand" href="#" style="font-weight: bold;color: var(--blue);">Hospital
            Management System</a>
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse d-xl-flex justify-content-xl-end" id="navcol-1">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="<?php echo $_SESSION["type"] == "Doctor" ? "doctor.php" : "patient.php" ?>"
                       class="btn btn-primary" type="button" style="margin-right: 10px;"><i
                                class="fa fa-arrow-left" style="margin-left: 5px;"></i>&nbsp;Back
                    </a>
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
                    <?php
                    while ($row = $symptomsResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        if ($_SESSION["type"] == "doctor") ?>
                            <form method="post">
                                <input style="display: none;" name="exam_id" value="<?php echo $exam_id?>">
                                <input style="display: none;" name="removesymptom" value="<?php echo $row["name"]?>">
                                <td class='text-right'><button class='btn btn-danger btn-sm' type='submit'><span>Remove&nbsp;</span><i class='fa fa-arrow-right'></i></button></td>
                            </form>
                        <?php echo "</tr>";
                    }
                    $symptomsResult->free_result();
                    ?>
                    <?php if ($_SESSION["type"] == "doctor") { ?>
                        <form method="post">
                            <input name="exam_id" style="display: none;" value="<?php echo $exam_id ?>">
                            <tr>
                                <td>
                                    <select name="symptom">
                                        <optgroup label="Symptoms:">
                                            <?php while ($row = $allSymptomsResult->fetch_assoc()) {
                                                echo "<option value=" . $row["name"] . " selected=''>" . $row["name"] . "</option>";
                                            } ?>
                                        </optgroup>
                                    </select>
                                </td>
                                <td class='text-right'>
                                    <button class='btn btn-success btn-sm' type='submit'>
                                        <span>Add Symptom&nbsp;</span><i class='fa fa-check'></i></button>
                                </td>
                            </tr>
                        </form>
                    <?php } ?>
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
                    <?php while ($row = $diagnosisResult->fetch_assoc()) { ?>
                        <tr>
                            <?php
                            echo "<td>" . $row["name"] . "</td>";
                            if ($_SESSION["type"] == "doctor"){?>
                                <form method="post">
                                    <input style="display: none;" name="exam_id" value="<?php echo $exam_id ?>">
                                    <input style="display: none;" name="removedisease" value="<?php echo $row["name"]?>">
                                    <td class='text-right'><button class='btn btn-danger btn-sm' type='submit'><span>Remove&nbsp;</span><i class='fa fa-arrow-right'></i></button></td>";
                                </form>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    <?php if ($_SESSION["type"] == "doctor") { ?>
                        <form method="post">
                            <input name="exam_id" style="display: none;" value="<?php echo $exam_id ?>">
                            <tr>
                                <td>
                                    <select name="disease">
                                        <optgroup label="Diseases:">
                                            <?php while ($row = $allDiseasesResult->fetch_assoc()) {
                                                echo "<option value=" . $row["name"] . " selected=''>" . $row["name"] . "</option>";
                                            } ?>
                                        </optgroup>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-success btn-sm" type="submit">
                                        <span>Add Diagnosis&nbsp;</span><i class="fa fa-check"></i></button>
                                </td>
                            </tr>
                        </form>
                    <?php } ?>
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
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $assignedTestsResult->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["name"] ?></td>
                            <?php if ($_SESSION["type"] == "doctor") {?>
                                <form method="post">
                                    <input style="display: none;" name="exam_id" value="<?php echo $exam_id ?>">
                                    <input style="display: none;" name="removetest" value="<?php echo $row["name"]?>">
                                    <td class='text-right'><button class='btn btn-danger btn-sm' type='submit'><span>Remove&nbsp;</span><i class='fa fa-arrow-right'></i></button></td>";
                                </form>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                    <?php if ($_SESSION["type"] == "doctor") { ?>
                        <form method="post">
                            <input name="exam_id" style="display: none;" value="<?php echo $exam_id ?>">
                            <tr>
                                <td>
                                    <select name="test">
                                        <optgroup label="Tests:">
                                            <?php while ($row = $allTestsResult->fetch_assoc()) {
                                                echo "<option value=" . $row["test_id"] . " selected=''>" . $row["name"] . "</option>";
                                            } ?>
                                        </optgroup>
                                    </select>
                                </td>
                                <td class="text-right" colspan="2">
                                    <button class="btn btn-success btn-sm" type="submit"><span>Request Test&nbsp;</span><i
                                                class="fa fa-check"></i></button>
                                </td>
                            </tr>
                        </form>
                    <?php } ?>
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