<?php
// filepath: c:\xampp\htdocs\mylibrary\library\view_booking.php
require_once "CRUDOP.php";

if (!isset($_GET['id'])) {
    die("Booking ID is required.");
}

$booking_id = $_GET['id'];

$db = new Database();
$stmt = $db->conn->prepare("
    SELECT b.id AS booking_id, b.date_borrow_from, b.date_to, b.status, br.name AS borrower_name
    FROM bookings b
    JOIN borrowers br ON b.borrow_id = br.id
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// Fetch books in the booking
$bookStmt = $db->conn->prepare("
    SELECT bb.book_id, bo.title, bb.quantity
    FROM booking_books bb
    JOIN books bo ON bb.book_id = bo.id
    WHERE bb.booking_id = ?
");
$bookStmt->execute([$booking_id]);
$books = $bookStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Booking Details</h2>
    <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['booking_id']) ?></p>
    <p><strong>Borrower:</strong> <?= htmlspecialchars($booking['borrower_name']) ?></p>
    <p><strong>Date Borrow From:</strong> <?= htmlspecialchars($booking['date_borrow_from']) ?></p>
    <p><strong>Date To:</strong> <?= htmlspecialchars($booking['date_to']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>

    <h3>Books</h3>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <strong>Book ID:</strong> <?= htmlspecialchars($book['book_id']) ?> -
                <strong>Title:</strong> <?= htmlspecialchars($book['title']) ?> -
                <strong>Quantity:</strong> <?= htmlspecialchars($book['quantity']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>