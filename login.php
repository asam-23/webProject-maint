<?php
session_start(); // Start a new session or resume existing

require_once 'db_connection.php'; // Include database connection

$error_message = ""; // Initialize error message variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get and sanitize user input
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Prepare SQL query to select user
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $username); // Bind username parameter
            $stmt->execute(); // Execute query
            $result = $stmt->get_result(); // Get query result

            // Check if exactly one user is found
            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc(); // Fetch user data

                // Compare passwords (direct comparison here; hashing is recommended)
                if ($user && $user['password'] === $password) {
                    // Store user info in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Redirect to books page
                    header("Location: books.php");
                    exit();
                } else {
                    // Password does not match
                    $error_message = "Invalid username or password.";
                }
            } else {
                // Username not found
                $error_message = "Invalid username or password.";
            }
        } else {
            // SQL preparation failed
            $error_message = "Something went wrong. Please try again later.";
        }
    } else {
        // Missing input fields
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        
        <!-- Display error message if exists -->
        <?php if (!
