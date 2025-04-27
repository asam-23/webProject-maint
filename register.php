<?php
session_start(); 

if (isset($_SESSION['user_id'])) {
    header("Location: books.php"); 
    exit();
}

include 'db_connection.php'; 

$error_message = ""; 
$success_message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username already exists!";
    } else {
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password); 
        $stmt->execute();

        $success_message = "Registration successful! You can now <a href='login.php'>login here</a>";
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
            var passwordPattern = /^(?=.*[0-9]).{8,}$/; 

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
<body>
    <div class="form-container">
        <h2>Create a new Account</h2>

        <!-- عرض رسالة الخطأ إذا كانت موجودة -->
        <?php if ($error_message): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" onsubmit="return validatePassword()">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password (8 characters with numbers)</label>
                <input type="password" id="password" name="password" required>
                <div id="password-error" class="error-message" style="display: none;"></div>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p>If you already have an account, <a href="login.php">login here--></a>.</p>
    </div>
</body>
</html>
