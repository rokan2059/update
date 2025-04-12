<?php
// filepath: c:\xampp\htdocs\mylibrary\library\edit_book.php
require_once "CRUDOP.php";

$book = new Book();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $books = $book->getBooks();
    $currentBook = null;

    foreach ($books as $b) {
        if ($b['id'] == $id) {
            $currentBook = $b;
            break;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publish_year = $_POST['publish_year'];
    $barcode = $_POST['barcode'];
    $onhand_quantity = $_POST['onhand_quantity'];

    if ($book->updateBook($id, $title, $author, $publish_year, $barcode, $onhand_quantity)) {
        echo "<script>alert('Book updated successfully!'); window.location.href='books.php';</script>";
    } else {
        echo "<script>alert('Failed to update book. Please try again.');</script>";
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
    <h2>Edit Book</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $currentBook['id'] ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= $currentBook['title'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author:</label>
            <input type="text" name="author" id="author" class="form-control" value="<?= $currentBook['author'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="publish_year" class="form-label">Publish Year:</label>
            <input type="number" name="publish_year" id="publish_year" class="form-control" value="<?= $currentBook['publish_year'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode:</label>
            <input type="text" name="barcode" id="barcode" class="form-control" value="<?= $currentBook['barcode'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="onhand_quantity" class="form-label">Onhand Quantity:</label>
            <input type="number" name="onhand_quantity" id="onhand_quantity" class="form-control" value="<?= $currentBook['onhand_quantity'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Book</button>
    </form>
</body>
</html>