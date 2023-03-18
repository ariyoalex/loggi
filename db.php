<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'loggi'; //Chage to your DB NAME

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


//Login, Register, Dashboard, Edit, Delete, Change Password, Forgot Password

?>