<?php
// filepath: c:\xampp\htdocs\mylibrary\library\books.php
require_once "CRUDOP.php";

$book = new Book();
$books = $book->getBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Info</title>
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
    <h2 class="text-center">Books Info</h2>
    <a href="add_book.php" class="btn btn-primary mb-3">Add New Book</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publish Year</th>
                <th>Barcode</th>
                <th>Onhand Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($books)) {
                echo "<tr><td colspan='7' class='text-center'>No books found</td></tr>";
            } else {
                foreach ($books as $b) {
                    echo "<tr>
                        <td>{$b['id']}</td>
                        <td>{$b['title']}</td>
                        <td>{$b['author']}</td>
                        <td>{$b['publish_year']}</td>
                        <td>{$b['barcode']}</td>
                        <td>{$b['onhand_quantity']}</td>
                        <td>
                            <a href='edit_book.php?id={$b['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_book.php?id={$b['id']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>