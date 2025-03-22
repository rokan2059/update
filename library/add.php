<?php

require_once "Book.php";
$book = new Book();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $barcode = $_POST['barcode'];
    $stat = $_POST['stat'];

    if ($book->addBook($title, $author, $year, $barcode, $stat)) {
        header("Location: books.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2>Add New Book</h2>
<form method="POST">
    <div class="mb-3">
        <label>Title:</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Author:</label>
        <input type="text" name="author" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Published Year:</label>
        <input type="text" name="year" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Barcode:</label>
        <input type="text" name="barcode" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Status:</label>
        <input type="text" name="stat" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Book</button>
</form>
</body>
</html>