<?php
session_start(); 
include 'db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

$query = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $pdf_link = $_POST['pdf_link'];

    // حفظ اسم الصورة القديم لو المستخدم ما رفع صورة جديدة
    $cover_image = $book['cover_image'];

    // هل المستخدم رفع صورة جديدة؟
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["cover_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

  
        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFilePath)) {
            $cover_image = $fileName;
        }
    }

    // تحديث الكتاب مع اسم الصورة
    $query = "UPDATE books SET title = ?, author = ?, genre = ?, published_year = ?, cover_image = ?, pdf_link = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $title, $author, $genre, $published_year, $cover_image, $pdf_link, $book_id);    
    $stmt->execute();

    header("Location: books.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container-books">
        <h1>Edit Book</h1>

        <form action="edit_book.php?id=<?= $book['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Book Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            <div class="input-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($book['genre']) ?>">
            </div>
            <div class="input-group">
                <label for="published_year">Published Year</label>
                <input type="number" id="published_year" name="published_year" value="<?= htmlspecialchars($book['published_year']) ?>" min="1900" max="2024"><br><br>

                <div class="input-group">
        <label for="cover_image">Upload Book Cover</label>
             <input type="file" id="cover_image" name="cover_image" accept="image/*">
                </div>
                <div class="input-group">
        <label for="pdf_link">PDF Link</label>
        <input type="text" id="pdf_link" name="pdf_link" value="<?= htmlspecialchars($book['pdf_link']) ?>">
         </div>
            <button type="submit" class="btn">Update Book</button>
            <a href="books.php" style="background-color: #f7941e;color: white; border: none;padding: 9.5px 20px; border-radius: 12px;cursor: pointer;font-weight: bold;font-size: 16px;transition: background-color 0.3s ease;text-decoration: none; margin-left: 10px;">Back To Book List</a>
        </form>
    </div>
</body>
</html>
