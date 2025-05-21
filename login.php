<?php
session_start();
include "db.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"]; // Changed username to email
    $password = $_POST["password"];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If the user exists and the password is correct
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"]; // Store user id in session
        header("Location: dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        echo "<p class='error'>Invalid credentials</p>"; // Error message for invalid login
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="register.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
