<?php
session_start();
include "db.php";

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Check if user exists in the users table
$user_check_sql = "SELECT COUNT(*) FROM users WHERE id = ?";
$user_check_stmt = $conn->prepare($user_check_sql);
$user_check_stmt->bind_param("i", $user_id);
$user_check_stmt->execute();
$user_check_stmt->bind_result($user_exists);
$user_check_stmt->fetch();
$user_check_stmt->close();

if ($user_exists == 0) {
    echo "Error: User does not exist.";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $priority = isset($_POST["priority"]) ? trim($_POST["priority"]) : "Medium";
    $deadline = isset($_POST["deadline"]) ? trim($_POST["deadline"]) : NULL;

    // Validate form data
    if (empty($title) || empty($description)) {
        echo "Error: Title and description cannot be empty!";
    } else {
        // Prepare SQL query to insert a new task
        $sql = "INSERT INTO tasks (user_id, title, description, priority, deadline, status) 
                VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Query preparation failed: " . $conn->error);
        }

        // Bind parameters to the query
        $stmt->bind_param("issss", $user_id, $title, $description, $priority, $deadline);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the dashboard if task is added successfully
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!-- HTML form for task submission -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="add_task.css">
</head>
<body>
    <div class="container">
        <h2>Add New Task</h2>
        <form method="POST">
            <div>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div>
                <label for="priority">Priority</label>
                <select name="priority" id="priority">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div>
                <label for="deadline">Deadline</label>
                <input type="date" name="deadline" id="deadline">
            </div>
            <button type="submit">Submit Task</button>
        </form>
    </div>
</body>
</html>
