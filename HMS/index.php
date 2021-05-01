<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to management page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: management.php");
    exit;
}

//Required parameters for connection
$servername = "localhost";
$username = "root";
$password = "";
$db = "HMS";

//Connect database using credentials
$con = new mysqli($servername, $username, $password, $db);

//Check if connection is successfull
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

//If the request method is post, log in the user using the form values.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Login($con);
}
$con->close();

function Login($con){
    //Check if username and password received successfully
    if (!array_key_exists("username", $_POST) || !array_key_exists("password", $_POST)) {
        echo "<script type='text/javascript'>alert('Could not receive username and/or password!');</script>";
        return;
    }

    //Sanitize the inputs
    $sanitizedUsername = htmlspecialchars($_POST["username"]);
    $sanitizedPassword = htmlspecialchars($_POST["password"]);

    //Error messaage response variable
    $responseMessage = "'The user " . $sanitizedUsername . " does not exist in database.'";

    // Perform query
    if ($result = $con->query("SELECT sid FROM student where LOWER(sname)=LOWER ('" . $sanitizedUsername . "') LIMIT 1;")) {
        if ($result->num_rows <= 0) {
            echo "<script type='text/javascript'>alert($responseMessage);</script>";
            return;
        }
        $sid = $result->fetch_assoc();
        if ($sid["sid"] == $sanitizedPassword) {
            // Password is correct, start a new session
            session_start();

            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $sanitizedPassword;
            $_SESSION["username"] = $sanitizedUsername;
            $_SESSION["newmessage"] = false;
            $_SESSION["message"] = "";

            //Close connection to database and redirect user to management page.
            $con->close();
            header("Location: management.php");
        } else {
            $responseMessage = "'Password for user " . $sanitizedUsername . " does not match.'";
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
    <title>Internship</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .login-clean{background:#f1f7fc;padding:80px 0}
        .login-clean form{max-width:320px;width:90%;margin:0 auto;background-color:#fff;padding:40px;border-radius:4px;color:#505e6c;box-shadow:1px 1px 5px rgba(0,0,0,.1)}
        .login-clean .illustration{text-align:center;padding:0 0 20px;font-size:100px;color:#f4476b}
        .login-clean form .form-control{background:#f7f9fc;border:none;border-bottom:1px solid #dfe7f1;border-radius:0;box-shadow:none;outline:0;color:inherit;text-indent:8px;height:42px}
        .login-clean form .btn-primary{background:#f4476b;border:none;border-radius:4px;padding:11px;box-shadow:none;margin-top:26px;text-shadow:none;outline:0!important}
        .login-clean form .btn-primary:active,.login-clean form .btn-primary:hover{background:#eb3b60}
        .login-clean form .btn-primary:active{transform:translateY(1px)}
        .login-clean form .forgot{display:block;text-align:center;font-size:12px;color:#6f7a85;opacity:.9;text-decoration:none}
        .login-clean form .forgot:active,.login-clean form .forgot:hover{opacity:1;text-decoration:none}
    </style>
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
        <div class="form-group"><input name="username" id="username" class="form-control" type="text"
                                       placeholder="Username"></div>
        <div class="form-group"><input name="password" id="password" class="form-control" type="password"
                                       placeholder="ID">
        </div>
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
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const loginForm = document.getElementById('login-form')

    //Intercept submit event and check if both fields are filled else alert user to fill both.
    loginForm.addEventListener('submit', event => {
        if (usernameInput.value === "" || passwordInput.value === "") {
            alert("Please fill both input fields.");
            event.preventDefault();
        }
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>