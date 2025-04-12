<?php
// filepath: c:\xampp\htdocs\mylibrary\library\delete_book.php
require_once "CRUDOP.php";

$book = new Book();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($book->deleteBook($id)) {
        echo "<script>alert('Book deleted successfully!'); window.location.href='books.php';</script>";
    } else {
        echo "<script>alert('Failed to delete book. Please try again.'); window.location.href='books.php';</script>";
    }
}
?>