<?php
session_start();
include 'db_connection.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's books
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
<body style="background-color: #f4f6f8; font-family: 'Arial', sans-serif; margin: 0; padding: 20px;">

    <div class="form-container-books" style="max-width: 1200px; margin: auto;">
        <h1 style="text-align: center; color: #333;">Available Books</h1><br>

        <div class="book-list" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-card" style="background: #fff; padding: 20px; width: 250px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <?php if (!empty($row['cover_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['cover_image']) ?>" alt="Book Cover" style="width: 100%; height: 300px; object-fit: cover; border-radius: 8px;">
                    <?php endif; ?>

                    <h2 style="font-size: 20px; color: #333; margin: 15px 0 10px;"><?= htmlspecialchars($row['title']) ?></h2>
                    <p style="margin: 5px 0;"><strong>Author:</strong> <?= htmlspecialchars($row['author']) ?></p>
                    <p style="margin: 5px 0;"><strong>Genre:</strong> <?= htmlspecialchars($row['genre']) ?></p>
                    <p style="margin: 5px 0;"><strong>Year:</strong> <?= htmlspecialchars($row['published_year']) ?></p><br>

                    <?php if (!empty($row['pdf_link'])): ?>
                        <a href="<?= htmlspecialchars($row['pdf_link']) ?>" target="_blank" class="logout-btn" style="display: inline-block; margin-top: 10px; padding: 10px 15px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none;">Download PDF</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
