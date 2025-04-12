<?php
// filepath: c:\xampp\htdocs\mylibrary\library\add_book.php
require_once "CRUDOP.php";

$book = new Book();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publish_year = $_POST['publish_year'];
    $barcode = $_POST['barcode'];
    $onhand_quantity = $_POST['onhand_quantity'];

    try {
        if ($book->addBook($title, $author, $publish_year, $barcode, $onhand_quantity)) {
            $book->addLog(null, $title, $onhand_quantity, 'Incoming'); // Log the incoming book
            echo "<script>alert('Book added successfully!'); window.location.href='books.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
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
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Library Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="books.php">Books Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="borrower.php">Borrowers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bookings.php">Bookings</a> <!-- Added Bookings Button -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logs.php">Logs</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../registered/welcome.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="text-center">Add New Book</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author:</label>
            <input type="text" name="author" id="author" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="publish_year" class="form-label">Publish Year:</label>
            <input type="number" name="publish_year" id="publish_year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode:</label>
            <input type="text" name="barcode" id="barcode" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="onhand_quantity" class="form-label">Onhand Quantity:</label>
            <input type="number" name="onhand_quantity" id="onhand_quantity" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
</div>

<!-- Footer -->
<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>