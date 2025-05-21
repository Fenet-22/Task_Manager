<?php
// Database connection
$conn = new PDO("mysql:host=localhost;dbname=task_manager", 'root', '');

// Check if the task ID is provided
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Query to update the start time of the task
    $query = "UPDATE tasks SET start_time = CURRENT_TIMESTAMP WHERE id = :taskId";
    $stmt = $conn->prepare($query);
    
    // Bind the task ID to the query
    $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        echo "Task started successfully!";
    } else {
        echo "Failed to start the task.";
    }
} else {
    echo "No task ID provided.";
}
?>
