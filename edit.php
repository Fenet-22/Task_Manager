<?php
// Enable debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    $conn = new PDO("mysql:host=localhost;dbname=task_manager", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Handle GET request - Fetch task data
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $taskId = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $taskId]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            die("Error: Task not found.");
        }
    } catch (PDOException $e) {
        die("Error fetching task: " . $e->getMessage());
    }
}

// Handle POST request - Update task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $taskId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (empty($title) || empty($description)) {
        die("Error: Title and description cannot be empty.");
    }

    try {
        $stmt = $conn->prepare("UPDATE tasks SET title = :title, description = :description, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['title' => $title, 'description' => $description, 'id' => $taskId]);

        header('Location: index.php'); // Redirect after update
        exit;
    } catch (PDOException $e) {
        die("Error updating task: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="edit-container">
        <div class="edit-form">
            <h2>Edit Task</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
                
                <div class="input-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
                </div>
                
                <div class="input-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($task['description']) ?></textarea>
                </div>
                
                <button type="submit" class="btn-save">Update Task</button>
            </form>
        </div>
    </div>
</body>
</html>
