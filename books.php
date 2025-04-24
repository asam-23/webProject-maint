<?php
session_start(); 
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $pdf_link = $_POST['pdf_link'];
    $user_id = $_SESSION['user_id'];

// التحقق من الرابط
if (!empty($pdf_link)) {
    if (!filter_var($pdf_link, FILTER_VALIDATE_URL) || !preg_match('/\.pdf$/i', $pdf_link)) {
        echo "<script>alert('Please enter a valid PDF link ending with .pdf'); window.location.href='books.php';</script>";
        exit();
    }
}

    
$image_name = $_FILES['cover_image']['name'];
$image_tmp = $_FILES['cover_image']['tmp_name'];
$image_path = "uploads/" . basename($image_name);


if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}
move_uploaded_file($image_tmp, $image_path);

$query = "INSERT INTO books (title, author, genre, published_year, cover_image, pdf_link, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssi", $title, $author, $genre, $published_year, $image_name, $pdf_link, $user_id);
    $stmt->execute();

    header("Location: books.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container-books">
        <h1>Manage Your Books</h1><br><br>
        <h2>- Add a New Book</h2><br>
        <form action="books.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Book Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="input-group">
                <label for="genre">Genre (Type of book )</label>
                <input type="text" id="genre" name="genre">
            </div>
            <div class="input-group">
                <label for="published_year">Published Year</label>
                <input type="number" id="published_year" name="published_year" min="1900" max="2024">
            </div>
            <div class="input-group">
        <label for="cover_image">Book Cover</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
        </div>
        <div class="input-group">
        <label for="pdf_link">PDF Link</label>
        <input type="text" id="pdf_link" name="pdf_link">
        </div>
            <button type="submit" class="btn">Add Book</button><br><br>
        </form>

        <h2>- Books List</h2>
        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Cover image</th>
                    <th>PDF Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['genre']) ?></td>
                    <td><?= htmlspecialchars($row['published_year']) ?></td>
                    <td><?= htmlspecialchars($row['cover_image']) ?></td>
                    <td>
    <?php if (!empty($row['pdf_link'])): ?>
        <a href="<?= htmlspecialchars($row['pdf_link']) ?>" target="_blank" style="padding:5px 10px; background-color:#f7941e; color:white; border-radius:6px; text-decoration:none;">Download</a>
    <?php else: ?>
        No PDF
    <?php endif; ?>
</td>

                    <td>
                        <a href="edit_book.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_book.php?id=<?= $row['id'] ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table><br><br>
        <a href="logout.php" class="logout-btn">LOGOUT </a>
        <a href="index.php" class="logout-btn" style="text-decoration: none; margin-left: 20px;"></i>HOME</a>
    </div>
</body>
</html>
