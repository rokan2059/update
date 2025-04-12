<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "library_db"; // Updated database name

$conn = new mysqli($servername, $username, $password, $db_name, 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "";
?>