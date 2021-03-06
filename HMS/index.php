<?php
include("config.php");
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to logout
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: logout.php");
    exit;
}

//If the request method is post, log in the user using the form values.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Login($con);
}
$con->close();

function Login($con){
    //Check if username and password received successfully
    if (!array_key_exists("person_id", $_POST) || !array_key_exists("password", $_POST)) {
        echo "<script type='text/javascript'>alert('Could not receive person_id and/or password!');</script>";
        return;
    }

    //Sanitize the inputs
    $sanitizedPersonID = htmlspecialchars($_POST["person_id"]);
    $sanitizedPassword = htmlspecialchars($_POST["password"]);

    //Error messaage response variable
    $responseMessage = "'The user " . $sanitizedPersonID . " does not exist in database.'";

    // Perform query
    if ($result = $con->query("SELECT password FROM Persons where LOWER(person_id)=LOWER ('" . $sanitizedPersonID . "') LIMIT 1;")) {
        if ($result->num_rows <= 0) {
            echo "<script type='text/javascript'>alert($responseMessage);</script>";
            return;
        }
        $password = $result->fetch_assoc();
        if ($password["password"] == $sanitizedPassword) {


            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["person_id"] = $sanitizedPersonID;
            $_SESSION["newmessage"] = false;
            $_SESSION["message"] = "";
            $_SESSION["type"] = $_POST["type"];
            
            $sqlDoctor = "SELECT D.person_id FROM doctors D WHERE D.person_id = " . $_SESSION["person_id"] . "";
            $sqlPatient = "SELECT P.person_id FROM patients P WHERE P.person_id = " . $_SESSION["person_id"] . "";
            $sqlLab = "SELECT L.person_id FROM laboratorians L WHERE L.person_id = " . $_SESSION["person_id"] . "";
            $sqlPha = "SELECT E.person_id FROM pharmacists E WHERE E.person_id = " . $_SESSION["person_id"] . "";
            $responseMessage2 = "'The user " . $sanitizedPersonID . " does not exist in " . $_SESSION["type"] . " database.'";

            
            switch ($_SESSION["type"]){
                case "patient":
                    if($result2 = $con->query($sqlPatient)){
                        if ($result2->num_rows <= 0) {
                            echo "<script type='text/javascript'>alert($responseMessage2);</script>";
                            return;
                        }else{
                            header("location: patient.php");
                            break;
                        }  
                    }
                case "doctor":
                    if($result2 = $con->query($sqlDoctor)){
                        if ($result2->num_rows <= 0) {
                            echo "<script type='text/javascript'>alert($responseMessage2);</script>";
                            return;
                        }else{
                            header("location: doctor.php");
                            break;
                        }  
                    }
                case "laboratorian":
                    if($result2 = $con->query($sqlLab)){
                        if ($result2->num_rows <= 0) {
                            echo "<script type='text/javascript'>alert($responseMessage2);</script>";
                            return;
                        }else{
                            header("location: laboratorian.php");
                            break;
                        }  
                    }
                case "pharmacist":
                    if($result2 = $con->query($sqlPha)){
                        if ($result2->num_rows <= 0) {
                            echo "<script type='text/javascript'>alert($responseMessage2);</script>";
                            return;
                        }else{
                            header("location: pharmacist.php");
                            break;
                        }  
                    }
                
            }
            
        } else {
            $responseMessage = "'Password for user " . $sanitizedPersonID . " does not match.'";
            echo "<script type='text/javascript'>alert($responseMessage);</script>";

        }
        $result->free_result();
        return;
    } else {
        echo "<script type='text/javascript'>alert('Login failed for some internal issue.');</script>";
        return;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Hospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<nav class="navbar navbar-light navbar-expand-md">
    <div class="container-fluid"><a class="navbar-brand" href="index.php"
                                    style="font-weight: bold;color: var(--blue);">
            Hospital Management System</a>
    </div>
</nav>
<div class="login-clean">
    <form method="post" id="login-form">
        <h2 class="sr-only">Login Form</h2>
        <div class="illustration">
            <h3 style="color: var(--blue);font-weight: bold;font-style: normal;text-align: center;">LOG IN</h3>
        </div>
        <div class="form-group"><input name="person_id" id="person_id" class="form-control" type="text"
                                       placeholder="Person ID"></div>
        <div class="form-group"><input name="password" id="password" class="form-control" type="password"
                                       placeholder="Password">
        </div>
        <input type="radio" id="patient" name="type" value="patient">
        <label for="patient">Patient</label><br>
        <input type="radio" id="doctor" name="type" value="doctor">
        <label for="doctor">Doctor</label><br>
        <input type="radio" id="laboratorian" name="type" value="laboratorian">
        <label for="laboratorian">Laboratorian</label><br>
        <input type="radio" id="pharmacist" name="type" value="pharmacist">
        <label for="pharmacist">Pharmacist</label><br>
        <p>Don't have an account? <a href="register.php">Sign up</a>.</p>
        <div class="form-group">
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit" style="background: var(--blue);">Log In<i
                            class="fa fa-sign-in" style="margin-left: 5px;"></i></button>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    //Form fields
    const usernameInput = document.getElementById("person_id");
    const passwordInput = document.getElementById("password");
    const loginForm = document.getElementById('login-form');
    const type = document.getElementsByName('type');

    //Intercept submit event and check if both fields are filled else alert user to fill both.
    loginForm.addEventListener('submit', event => {
        if (usernameInput.value === "" || passwordInput.value === "") {
            alert("Please fill both input fields.");
            event.preventDefault();
        }
        flag = false;
        for(i = 0; i < type.length; i++){
            if(type[i].checked == true){
                flag = true;
                break;
            }
        }
        if(flag == false){
            alert("Please select a user type.");
            event.preventDefault();
        }
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>