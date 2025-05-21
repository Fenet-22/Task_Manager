<?php
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Database connection
    $conn = new PDO("mysql:host=localhost;dbname=task_manager", 'root', '');

    // Get the start time for calculation
    $stmt = $conn->prepare("SELECT start_time FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task && $task['start_time']) {
        $startTime = new DateTime($task['start_time']);
        $endTime = new DateTime();

        // Calculate the time difference
        $interval = $startTime->diff($endTime);
        $totalTime = $interval->format('%H:%I:%S');

        // Update the task with end time and total time spent
        $stmt = $conn->prepare("UPDATE tasks SET end_time = NOW(), total_time = ? , status = 'completed' WHERE id = ?");
        $stmt->execute([$totalTime, $taskId]);
    }

    // Redirect back to the task list
    header('Location: index.php');
    exit;
}
?>
