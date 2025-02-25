<?php
$host = "localhost"; // Change if needed
$user = "root"; // Change if your database has a different username
$password = ""; // Change if your database has a password
$database = "user_db"; // Change to your actual database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
 