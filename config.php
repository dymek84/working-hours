<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */ 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'dymek');
define('DB_NAME', 'web');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//API_KEY_GOOGLE = AIzaSyDrr6OP635R6i8c6pxbq4e29nyt6rOMNRM
//API_KEY_GITHUB = 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



$servername = "";
$username = "root";
$password = "dymek";

try {
  $conn = new PDO("mysql:host=$servername;dbname=web", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
