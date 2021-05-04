<?php

include("config.php");
// Initialize the session
session_start();

//If the request method is post, register the user using the form values.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Register($con);
}

$con->close();

function Register($con)
{
    //Check if common values received successfully
    if (!array_key_exists("person_id", $_POST) ||
        !array_key_exists("password", $_POST) ||
        !array_key_exists("name", $_POST) ||
        !array_key_exists("surname", $_POST) ||
        !array_key_exists("sex", $_POST) ||
        !array_key_exists("phone", $_POST) ||
        !array_key_exists("email", $_POST) ||
        !array_key_exists("type", $_POST)
    ) {
        echo "<script type='text/javascript'>alert('Could not receive form values correctly!');</script>";
        return;
    }

    $sanitizedBirthDate = "";
    $sanitizedWeight = "";
    $sanitizedHeight = "";
    $sanitizedBloodType = "";
    $sanitizedTitle = "";

    if ($_POST["type"] == "patient") {
        if (array_key_exists("birth_date", $_POST) &&
            array_key_exists("weight", $_POST) &&
            array_key_exists("height", $_POST) &&
            array_key_exists("blood_type", $_POST)
        ){
            $sanitizedBirthDate = htmlspecialchars($_POST["birth_date"]);
            $sanitizedWeight = htmlspecialchars($_POST["weight"]);
            $sanitizedHeight = htmlspecialchars($_POST["height"]);
            $sanitizedBloodType = htmlspecialchars($_POST["blood_type"]);
        }
        else {
            echo "<script type='text/javascript'>alert('Could not receive form values correctly!');</script>";
            return;
        }
    } else if ($_POST["type"] == "doctor") {
        if (array_key_exists("title", $_POST))
        {
            $sanitizedTitle = htmlspecialchars($_POST["title"]);
        }
        else {
            echo "<script type='text/javascript'>alert('Could not receive form values correctly!');</script>";
            return;
        }
    } else if ($_POST["type"] == "laboratorian") {
        //No fields in laboratorian
    } else if ($_POST["type"] == "pharmacist") {
        //No fields in laboratorian
    }

    //Sanitize the inputs
    $sanitizedId = htmlspecialchars($_POST["person_id"]);
    $sanitizedName = htmlspecialchars($_POST["name"]);
    $sanitizedSurname = htmlspecialchars($_POST["surname"]);
    $sanitizedSex = htmlspecialchars($_POST["sex"]);
    $sanitizedPhone = htmlspecialchars($_POST["phone"]);
    $sanitizedEmail = htmlspecialchars($_POST["email"]);
    $sanitizedPassword = htmlspecialchars($_POST["password"]);
    $sanitizedType = htmlspecialchars($_POST["type"]);

    //Error messaage response variable
    $errorMessage = "'Something went wrong'";
    $responseMessage = "'The user " . $sanitizedId . " created succesfully.'";


    $sqlPerson = "INSERT INTO persons VALUES ('$sanitizedId' , '$sanitizedName', '$sanitizedSurname','$sanitizedSex','$sanitizedPhone','$sanitizedEmail','$sanitizedPassword');";
    $sqlDoctor = "INSERT INTO doctors VALUES ('$sanitizedId','$sanitizedTitle');";   
    $sqlPatient = "INSERT INTO patients VALUES ('$sanitizedId','$sanitizedBirthDate','$sanitizedWeight','$sanitizedHeight','$sanitizedBloodType');";
    $sqlLab = "INSERT INTO laboratorians VALUES ('$sanitizedId');";   
    $sqlPha = "INSERT INTO pharmacists VALUES ('$sanitizedId');";   


    if ($con->query($sqlPerson)){
        switch ($sanitizedType){
            case "patient":
                if($con->query($sqlPatient)){
                    echo "<script type='text/javascript'>alert($responseMessage);</script>";
                }
                else{
                    echo $con->connect_error;
                }
                break;
            case "doctor":
                if($con->query($sqlDoctor)){
                    echo "<script type='text/javascript'>alert($responseMessage);</script>";
                }else{
                    echo $con->connect_error;
                }
                break;
            case "laboratorian":
                if($con->query($sqlLab)){
                    echo "<script type='text/javascript'>alert($responseMessage);</script>";
                }else{
                    echo $con->connect_error;
                }
                break;
            case "pharmacist":
                if($con->query($sqlPha)){
                    echo "<script type='text/javascript'>alert($responseMessage);</script>";
                }else{
                    echo $con->connect_error;
                }
                break;
        }
        
        return;
    } else {
        die("Connection failed: " . $con->connect_error);
        //echo "<script type='text/javascript'>alert('Register failed for some internal issue.');</script>";
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
            <h3 style="color: var(--blue);font-weight: bold;font-style: normal;text-align: center;">REGISTER</h3>
        </div>
        <div class="form-group"><input name="person_id" id="person_id" class="form-control" type="text"
                                       placeholder="Person ID"></div>
        <div class="form-group"><input name="name" id="name" class="form-control" type="text"
                                       placeholder="Name">
        </div>
        <div class="form-group"><input name="surname" id="surname" class="form-control" type="text"
                                       placeholder="Surname">
        </div>
        <div class="form-group"><input name="phone" id="phone" class="form-control" type="text"
                                       placeholder="Phone">
        </div>
        <div class="form-group"><input name="email" id="email" class="form-control" type="text"
                                       placeholder="E-mail"></div>
        <div class="form-group"><input name="password" id="password" class="form-control" type="password"
                                       placeholder="Password">
        </div>
        <label for="sex">Sex: </label>
        <select name="sex">
                      <option value="male">Male</option>
                      <option value="female">Female</option>
        </select><br>
        <label for="type">Pick a user type: </label><br>
        <input type="radio" id="patient" name="type" value="patient">
        <label for="patient">Patient</label><br>
        <input type="radio" id="doctor" name="type" value="doctor">
        <label for="doctor">Doctor</label><br>
        <input type="radio" id="laboratorian" name="type" value="laboratorian">
        <label for="laboratorian">Laboratorian</label><br>
        <input type="radio" id="pharmacist" name="type" value="pharmacist">
        <label for="pharmacist">Pharmacist</label><br>

        <div style="display: none;" id="pati"> 
            <div class="form-group"><input name="height" id="height" class="form-control" type="text"
                                        placeholder="Height">
            </div>
            <div class="form-group"><input name="weight" id="weight" class="form-control" type="text"
                                        placeholder="Weight">
            </div>
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date">
            <label for="blood_type">Blood Type: </label>
            <select name="blood_type">
                        <option value="0-">0 RH -</option>
                        <option value="0+">0 RH +</option>
                        <option value="a-">A RH -</option>
                        <option value="a+">A RH +</option>
                        <option value="b-">B RH -</option>
                        <option value="b+">B RH +</option>
                        <option value="ab-">AB RH -</option>
                        <option value="ab+">AB RH +</option>
            </select><br>
        </div>
        <div style="display: none;" id="doc"> 
            <label for="title">Title: </label>
            <select name="title">
                        <option value="intern">Intern</option>
                        <option value="practitioner">General Practitioner</option>
                        <option value="specialist">Specialist</option>
                        <option value="operator ">Operator Doctor</option>
                        <option value="aprof">Assistant Professor</option>
                        <option value="prof">Professor</option>
            </select><br>
        </div>


        <div class="form-group">
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit" style="background: var(--blue);"><i
                            class="fa fa-sign-in" style="margin-left: 5px;"></i></button>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    //Form fields
    const usernameInput = document.getElementById("person_id");
    const nameInput = document.getElementById("name");
    const surnameInput = document.getElementById("surname");
    const phoneInput = document.getElementById("phone");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const heightInput = document.getElementById("height");
    const weightInput = document.getElementById("weight");
    const type = document.getElementsByName("type");
    const loginForm = document.getElementById('login-form')

    //Intercept submit event and check if both fields are filled else alert user to fill both.
    loginForm.addEventListener('submit', event => {
        if (usernameInput.value === "" || passwordInput.value === "" || emailInput.value === ""|| phoneInput.value === ""|| surnameInput.value === ""|| nameInput.value === "") {
            alert("Please fill the all input fields.");
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
        if(type == "patient"){
            if(heightInput.value === "" || weightInput.value === "") {
                alert("Please fill the all input fields.");
                event.preventDefault();
            }

        }
    });

    document.getElementById('patient').addEventListener('change', function(){
        document.getElementById('pati').style.display = this.checked ? 'block' : 'none';
        document.getElementById('doc').style.display = this.checked ? 'none' : 'block';
    });
    document.getElementById('doctor').addEventListener('change', function(){
        document.getElementById('pati').style.display = this.checked ? 'none' : 'block';
        document.getElementById('doc').style.display = this.checked ? 'block' : 'none';
    });
    document.getElementById('laboratorian').addEventListener('change', function(){
        document.getElementById('pati').style.display = this.checked ? 'none' : 'block';
        document.getElementById('doc').style.display = this.checked ? 'none' : 'block';
    });
    document.getElementById('pharmacist').addEventListener('change', function(){
        document.getElementById('pati').style.display = this.checked ? 'none' : 'block';
        document.getElementById('doc').style.display = this.checked ? 'none' : 'block';
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>