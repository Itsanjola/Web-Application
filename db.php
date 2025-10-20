<?php
$host = "localhost";
$user = "root"; // default XAMPP
$pass = "";     // default empty
$dbname = "mani";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>