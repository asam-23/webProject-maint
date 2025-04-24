<?php
session_start();
require_once 'config/db_connection.php';
require_once 'utils/helpers.php';

class BookManager {
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    public function validateSession() {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }
        $this->user_id = $_SESSION['user_id'];
    }

    public function handleBookSubmission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            $data = $this->sanitizeInput($_POST);
            $file = $_FILES['cover_image'] ?? null;

            if (!$this->validateInput($data, $file)) {
                throw new Exception('Invalid input data or PDF link');
            }

            $image_path = $this->handleFileUpload($file);
            $this->saveBook($data, $image_path);
            redirect('books.php');
        } catch (Exception $e) {
            displayError($e->getMessage());
        }
    }

    private function sanitizeInput($data) {
        return [
            'title' => trim($data['title'] ?? ''),
            'author' => trim($data['author'] ?? ''),
            'genre' => trim($data['genre'] ?? ''),
            'published_year' => (int)($data['published_year'] ?? 0),
            'pdf_link' => trim($data['pdf_link'] ?? '')
        ];
    }

    private function validateInput($data, $file) {
        if (empty($data['title']) || empty($data['author']) || !$file) {
            return false;
        }

        if (!empty($data['pdf_link']) && 
            (!filter_var($data['pdf_link'], FILTER_VALIDATE_URL) || 
             !preg_match('/\.pdf$/i', $data['pdf_link']))) {
            return false;
        }

        return true;
    }

    private function handleFileUpload($file) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($file['name']);
        $image_path = $upload_dir . $image_name;

        if (!move_uploaded_file($file['tmp_name'], $image_path)) {
            throw new Exception('Failed to upload cover image');
        }

        return $image_name;
    }

    private function saveBook($data, $image_name) {
        $query = "INSERT INTO books (title, author, genre, published_year, cover_image, pdf_link, user_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssssssi",
            $data['title'],
            $data['author'],
            $data['genre'],
            $data['published_year'],
            $image_name,
            $data['pdf_link'],
            $this->user_id
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to save book');
        }
    }

    public function getUserBooks() {
        $query = "SELECT * FROM books WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}

$bookManager = new BookManager($conn, $_SESSION['user_id'] ?? null);
$bookManager->validateSession();
$bookManager->handleBookSubmission();
$books = $bookManager->getUserBooks();
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
        <h1>Manage Your Books</h1>
        <br><br>
        <h2>Add a New Book</h2>
        <br>
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
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre">
            </div>
            <div class="input-group">
                <label for="published_year">Published Year</label>
                <input type="number" id="published_year" name="published_year" min="1900" max="2025">
            </div>
            <div class="input-group">
                <label for="cover_image">Book Cover</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>
            <div class="input-group">
                <label for="pdf_link">PDF Link</label>
                <input type="text" id="pdf_link" name="pdf_link">
            </div>
            <button type="submit" class="btn">Add Book</button>
        </form>

        <br><br>
        <h2>Books List</h2>
        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Cover Image</th>
                    <th>PDF Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $books->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['genre']) ?></td>
                        <td><?= htmlspecialchars($row['published_year']) ?></td>
                        <td><?= htmlspecialchars($row['cover_image']) ?></td>
                        <td>
                            <?php if (!empty($row['pdf_link'])): ?>
                                <a href="<?= htmlspecialchars($row['pdf_link']) ?>" 
                                   target="_blank" 
                                   style="padding:5px 10px; background-color:#f7941e; color:white; border-radius:6px; text-decoration:none;">
                                    Download
                                </a>
                            <?php else: ?>
                                No PDF
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_book.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="delete_book.php?id=<?= $row['id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br><br>
        <a href="logout.php" class="logout-btn">Logout</a>
        <a href="index.php" class="logout-btn" style="margin-left: 20px;">Home</a>
    </div>
</body>
</html>