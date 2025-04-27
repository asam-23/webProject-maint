<?php
session_start(); 

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: books.php"); 
    exit();
}

include 'db_connection.php'; 

$error_message = ""; 
$success_message = ""; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password']; 

    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username already exists!";
    } else {
        // Insert new user (note: password hashing recommended)
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password); 
        $stmt->execute();

        $success_message = "Registration successful! You can now <a href='login.php' style='color: #007bff; text-decoration: none;'>login here</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var errorMessage = document.getElementById("password-error");
            var passwordPattern = /^(?=.*[0-9]).{8,}$/; // Must contain numbers and be at least 8 characters

            if (!passwordPattern.test(password)) {
                errorMessage.style.display = "block";
                errorMessage.innerText = "Password must be at least 8 characters long and contain numbers.";
                return false;
            } else {
                errorMessage.style.display = "none";
                return true;
            }
        }
    </script>
</head>
<body style="background-color: #f8f9fa; font-family: 'Arial', sans-serif; margin: 0; padding: 20px;">

    <div class="form-container" style="max-width: 400px; margin: auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Create a New Account</h2>

        <!-- Display error or success messages -->
        <?php if ($error_message): ?>
            <div class="error-message" style="color: #dc3545; background-color: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 5px;"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message" style="color: #28a745; background-color: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 5px;"><?= $success_message ?></div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST" onsubmit="return validatePassword()">
            <div class="input-group" style="margin-bottom: 15px;">
                <label for="username" style="display: block; margin-bottom: 5px; color: #555;">Username</label>
                <input type="text" id="username" name="username" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="input-group" style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px; color: #555;">Password (8+ characters, with numbers)</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <div id="password-error" class="error-message" style="display: none; color: #dc3545; margin-top: 5px;"></div>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Register</button>
        </form>

        <p style="text-align: center; margin-top: 15px; color: #555;">Already have an account? <a href="login.php" style="color: #007bff; text-decoration: none;">Login here</a>.</p>
    </div>

</body>
</html>
