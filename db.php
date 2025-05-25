<?php
// db.php
$host = 'localhost';
$user = 'blogdb';
$pass = 'Mamimitu6@';
$db = 'blog';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>


