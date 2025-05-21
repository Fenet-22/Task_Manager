<?php
session_start();
include "db.php"; // Make sure this path is correct

// ✅ Redirect to login if user not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// ✅ Handle delete
if (isset($_GET["id"])) {
    $task_id = $_GET["id"];

    // Prepare statement
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting task.";
    }
} else {
    echo "Invalid request.";
}
?>
