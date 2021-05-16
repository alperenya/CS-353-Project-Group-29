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

$id = $_SESSION['person_id'];
$updatedValue = "";
$component = "";
$resultid = "";

if (array_key_exists("updateValue", $_POST)){
    $updatedValue = htmlspecialchars($_POST["updateValue"]);
}

if (array_key_exists("comp", $_POST)){
    $resultid = substr(htmlspecialchars($_POST["comp"]), 0, 11);
    $component = substr(htmlspecialchars($_POST["comp"]), 11, 3);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Lab($con, $id, $updatedValue, $component, $resultid);
}


function Lab($con, $id, $updatedValue, $component, $resultid)
{
    $sqlUpdate = "UPDATE component_result SET result_value=$updatedValue WHERE result_id='$resultid' and name='$component';";
    $resUpdate = $con->query($sqlUpdate);

    


}

$sqlTestName = "SELECT T.name, R.status, R.result_id  From tests T, test_result R WHERE T.test_id IN(SELECT test_id FROM assigned_tests WHERE exam_id IN(SELECT exam_id FROM appointment WHERE exam_id IN(SELECT exam_id FROM test_result WHERE result_id IN( SELECT result_id FROM done_by WHERE person_id = '$id'))));";
$resTestName = $con->query($sqlTestName);

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
                    <li class="nav-item"><a href="logout.php" button class="btn btn-primary" type="button">Log Out<i class="fa fa-sign-out" style="margin-left: 5px;"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row" style="margin: 0px;">
        <div class="col">
            <div class="container">
                <div class="row" style="margin-top: 10%;">
                    <div class="col">
                        <div class="row">
                            <div class="col-xl-12">
                                <h2>Assigned tests:</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Test Name:</th>
                                <th>Status</th>
                                <th>Components</th>
                                <th>Component Value</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <form method="post" id="patient-form">

                            <tr><?php $resultid = array();
                                while($row1 = $resTestName->fetch_assoc()): array_push($resultid, $row1["result_id"]); ?>
                                <td><?php echo $row1["name"];?></td>
                                <td><?php echo $row1["status"];?></td>
                                <td><select name="comp" id="mySelect" onchange="myFunction()">
                                        <?php if($row1["status"] == "Finalized"): ?>
                                        <optgroup label="Available slots:">
                                            <option></option>
                                        </optgroup>
                                        <?php else: ?>
                                        
                                        <?php $sqlComName = 'SELECT name FROM component_result WHERE result_id = "'.$row1["result_id"].'" ;';
                                        $resComName = $con->query($sqlComName);?>
                                        <optgroup label="Available slots:">
                                        <?php
                                        foreach($resComName as $m)
                                        {
                                        ?>
                                            <option value="<?php echo $row1['result_id'].$m['name'];?>"><?php echo $m['name'];?></option>
                                        <?php
                                        }?>
                                        </optgroup>
                                        
                                        <?php endif; ?>
                                    </select></td>
                                <td id="demo"></td>
                                <td class="text-right d-xl-flex justify-content-xl-end align-items-xl-center"><input type="text" name="updateValue" style="margin-right: 10px;"><button class="btn btn-primary btn-sm" type="submit"><span>Update Value&nbsp;</span><i class="fa fa-arrow-right"></i></button></td>
                                </form>
                                <?php endwhile; ?>
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
    <?php $resultComponent = array()?>
    <?php foreach($resultid as $r){?>
    <?php $sqlComVal = 'SELECT name, result_value FROM component_result WHERE result_id = "'. $r .'";';
    $resComVal = $con->query($sqlComVal);
    while($row1 = $resComVal->fetch_assoc()):
        $resultComponent[$r][$row1["name"]] = $row1["result_value"]; 
    endwhile;?>
    <?php } ?>

    <script>
    function myFunction() {
        var passedArray = 
        <?php echo json_encode($resultComponent); ?>;
        var x = document.getElementById("mySelect").value.substring(0,11);
        var y = document.getElementById("mySelect").value.substring(11,14);
        document.getElementById("demo").innerHTML = passedArray[x][y];
    }
    </script>
</body>

</html>