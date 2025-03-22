<?php

require_once "Book.php";
$book = new Book();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$bookDetails = $book->getBookById($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $barcode = $_POST['barcode'];
    $stat = $_POST['stat'];

    if ($book->updateBook($id, $title, $author, $year,$barcode,$stat)) {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2 class="mb-4">Edit Book</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="mb-3">
        <label class="form-label">Title:</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($bookDetails['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Author:</label>
        <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($bookDetails['author']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Year Published:</label>
        <input type="text" name="year" class="form-control" value="<?= htmlspecialchars($bookDetails['published_year']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Barcode:</label>
        <input type="text" name="barcode" class="form-control" value="<?= htmlspecialchars($bookDetails['barcode']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status:</label>
        <input type="text" name="stat" class="form-control" value="<?= htmlspecialchars($bookDetails['stat'])?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Book</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<!-- Footer -->
<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>
</body>
</html>
