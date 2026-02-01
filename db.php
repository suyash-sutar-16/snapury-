<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // your MySQL password
$db = 'snapury_suyash'; // change to your DB name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
