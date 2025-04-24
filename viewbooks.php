<?php
session_start(); 
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

$query = "SELECT * FROM books WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Books</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="form-container-books">
        <h1>Available Books</h1><br>
        <div class="book-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-card">
                <?php if (!empty($row['cover_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['cover_image']) ?>" alt="Book Cover">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($row['title']) ?></h2>
                    <p><strong>Author:</strong> <?= htmlspecialchars($row['author']) ?></p>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($row['genre']) ?></p>
                    <p><strong>Year:</strong> <?= htmlspecialchars($row['published_year']) ?></p><br>
                    <?php if (!empty($row['pdf_link'])): ?>
                    <a href="<?= htmlspecialchars($row['pdf_link']) ?>" target="_blank" class="logout-btn">Download PDF</a>
                    <?php endif; ?>
                    </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
