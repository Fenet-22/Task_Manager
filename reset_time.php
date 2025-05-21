<?php
include 'db_connection.php'; // Make sure to include your DB connection

// Check if the task ID is provided
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    try {
        // Prepare the SQL query to reset the times
        $query = "UPDATE tasks 
                  SET start_time = NULL, 
                      end_time = NULL, 
                      total_time = '00:00:00' 
                  WHERE id = :task_id";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to the index page (or wherever you need)
        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Task ID not provided!";
}
?>
