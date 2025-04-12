<?php
// filepath: c:\xampp\htdocs\mylibrary\library\add_borrower.php
require_once "CRUDOP.php";

class Borrower {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addBorrower($borrower_id, $name, $type, $phone_number) {
        $stmt = $this->db->conn->prepare("INSERT INTO borrowers (borrower_id, name, type, phone_number) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$borrower_id, $name, $type, $phone_number]);
    }
}

$borrower = new Borrower();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrower_id = $_POST['borrower_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $phone_number = $_POST['phone_number'];

    if ($borrower->addBorrower($borrower_id, $name, $type, $phone_number)) {
        echo "<script>alert('Borrower added successfully!'); window.location.href='borrower.php';</script>";
    } else {
        echo "<script>alert('Failed to add borrower. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Borrower</title>
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
                    <a class="nav-link" href="books.php">Books Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="borrower.php">Borrowers</a>
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
    <h2 class="text-center">Add New Borrower</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="borrower_id" class="form-label">Borrower ID:</label>
            <input type="text" name="borrower_id" id="borrower_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type:</label>
            <select name="type" id="type" class="form-control" required>
                <option value="Student">Student</option>
                <option value="Teacher">Teacher</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Borrower</button>
    </form>
</div>

<!-- Footer -->
<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>