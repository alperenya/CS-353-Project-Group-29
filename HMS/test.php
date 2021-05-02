<?php
// Initialize the session
session_start();


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

//if ($result = $con->query("CREATE TABLE persons (person_id CHAR(11) PRIMARY KEY, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(20) NOT NULL, sex VARCHAR(20) NOT NULL, phone VARCHAR(50) NOT NULL, email VARCHAR(20) NOT NULL, password VARCHAR(50) NOT NULL) ENGINE=INNODB;" )) {
if ($result = $con->query("INSERT INTO persons VALUES ('12345678900', 'Alperen', 'Yalcin', 'Male', '5000000000', 'alperen@email.com', 'alperen' );")){
    echo "alpere";
} else {
    echo "agacım";
    echo  $con->connect_error;
    return;
}


$con->close();


?>