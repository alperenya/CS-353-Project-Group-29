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

if ($result = $con->query("CREATE TABLE pharmacists (person_id CHAR(11) PRIMARY KEY, FOREIGN KEY (person_id) references persons(person_id)) ENGINE=INNODB;" )) {
//if ($result = $con->query("INSERT INTO persons VALUES ('12345678900', 'Alperen', 'Yalcin', 'Male', '5000000000', 'alperen@email.com', 'alperen' );")){
//if ($result = $con->query("INSERT INTO doctors VALUES ('12345678900', 'aile hekimi' );")){    
    echo "alpere";
} else {
    echo "agacım";
    echo  $con->connect_error;
    return;
}


$con->close();


?>