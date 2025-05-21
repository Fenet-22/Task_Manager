<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Popup Styling */
        .popup {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            z-index: 1000; /* Ensure popup is on top */
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }

        .popup button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .popup button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>Task Manager</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="add_task.php">Add Task</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header>
                <h1>Welcome Back, User!</h1>
            </header>

            <section class="tasks">
                <h2>Your Tasks</h2>
                <ul>
                    <?php while ($task = $result->fetch_assoc()): ?>
                        <li class="task-card">
                            <div class="task-info">
                                <strong><?= htmlspecialchars($task["title"]) ?></strong>
                                <p class="status <?= strtolower($task["status"]) ?>"><?= htmlspecialchars($task["status"]) ?></p>
                            </div>
                            <div class="task-actions">
                                <a href="edit.php?id=<?= $task["id"] ?>" class="btn-edit">✏ Edit</a>
                                <a href="complete_task.php?id=<?= $task["id"] ?>" class="btn-complete">✔ Complete</a>
                                <a href="#" onclick="showDeletePopup(<?= $task['id'] ?>)" class="btn-delete">🗑 Delete</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        </div>
    </div>

    <!-- Delete Confirmation Popup -->
    <div id="deletePopup" class="popup">
        <div class="popup-content">
            <p>Are you sure you want to delete this task?</p>
            <button id="confirmDeleteBtn">Yes</button>
            <button onclick="closePopup()">No</button>
        </div>
    </div>

    <script>
        function showDeletePopup(taskId) {
            let popup = document.getElementById("deletePopup");
            let confirmBtn = document.getElementById('confirmDeleteBtn');

            if (popup && confirmBtn) {
                confirmBtn.setAttribute('data-task-id', taskId);
                popup.style.display = "block";
            } else {
                console.error("Popup or Confirm Button not found!");
            }
        }

        function closePopup() {
            document.getElementById("deletePopup").style.display = "none";
        }

        window.onload = function() {
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                var taskId = this.getAttribute('data-task-id');

                // Redirect to delete the task
                window.location.href = "delete_task.php?id=" + taskId;
            });
        };
    </script>

</body>
</html>
