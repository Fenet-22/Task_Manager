<?php
if (isset($_GET['id']) && isset($_GET['status'])) {
    $taskId = $_GET['id'];
    $status = $_GET['status'];

    // Database connection
    $conn = new PDO("mysql:host=localhost;dbname=task_manager", 'root', '');

    // Update the task status
    $stmt = $conn->prepare("UPDATE tasks SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $taskId]);

    // Redirect back to the task list
    header('Location: index.php');
    exit;
}
?>
