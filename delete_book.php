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

$query = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();

header("Location: books.php");
exit();
?>
