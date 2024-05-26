<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "btu_todo_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
