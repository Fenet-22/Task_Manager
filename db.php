<?php
$host = "sql200.infinityfree.com";
$user = "if0_39006842"; // Change if using a different database user
$pass = "t8ILXB7JdT";
$dbname = "if0_39006842_task_manager";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
