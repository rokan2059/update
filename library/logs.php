<?php
// filepath: c:\xampp\htdocs\mylibrary\library\logs.php
require_once "CRUDOP.php";

class Logs {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getLogs($type = null) {
        if ($type) {
            $stmt = $this->db->conn->prepare("SELECT * FROM logs WHERE type = ? ORDER BY date DESC");
            $stmt->execute([$type]);
        } else {
            $stmt = $this->db->conn->prepare("SELECT * FROM logs ORDER BY date DESC");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$type = isset($_GET['type']) ? $_GET['type'] : null;
$logs = new Logs();
$logEntries = $logs->getLogs($type);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
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
                    <a class="nav-link" href="borrower.php">Borrowers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bookings.php">Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="logs.php">Logs</a>
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
    <h2 class="text-center">Logs</h2>

    <!-- Filter Form -->
    <div class="mb-3">
        <form method="GET" class="d-flex justify-content-end">
            <select name="type" class="form-select w-auto me-2">
                <option value="">All Transactions</option>
                <option value="Incoming" <?= isset($_GET['type']) && $_GET['type'] === 'Incoming' ? 'selected' : '' ?>>Incoming</option>
                <option value="Outgoing" <?= isset($_GET['type']) && $_GET['type'] === 'Outgoing' ? 'selected' : '' ?>>Outgoing</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <!-- Logs Table -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Booking ID</th>
                <th>Title</th>
                <th>Quantity</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($logEntries)) {
                echo "<tr><td colspan='6' class='text-center'>No logs found</td></tr>";
            } else {
                foreach ($logEntries as $log) {
                    // Add a label for the transaction type
                    $typeLabel = $log['type'] === 'Incoming' ? 
                        "<span class='badge bg-success'>Incoming</span>" : 
                        "<span class='badge bg-danger'>Outgoing</span>";

                    // Display the logs with enhanced details
                    echo "<tr>
                        <td>{$log['id']}</td>
                        <td>" . ($log['bookings_id'] ? "<a href='view_booking.php?id={$log['bookings_id']}'>#{$log['bookings_id']}</a>" : "N/A") . "</td>
                        <td>{$log['title']}</td>
                        <td>{$log['book_quantity']}</td>
                        <td>{$typeLabel}</td>
                        <td>{$log['date']}</td>
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