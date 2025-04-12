<?php
// filepath: c:\xampp\htdocs\mylibrary\library\bookings.php
session_start();
require_once "CRUDOP.php";

class Booking {
    public $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getBooks() {
        $stmt = $this->db->conn->prepare("SELECT id, title, onhand_quantity FROM books WHERE onhand_quantity > 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBorrowers() {
        $stmt = $this->db->conn->prepare("SELECT id, name FROM borrowers ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookQuantity($book_id, $quantity) {
        $stmt = $this->db->conn->prepare("UPDATE books SET onhand_quantity = onhand_quantity + ? WHERE id = ?");
        return $stmt->execute([$quantity, $book_id]);
    }

    public function addBooking($date_borrow_from, $date_to, $status, $borrow_id, $cart) {
        try {
            $this->db->conn->beginTransaction();

            // Insert booking details
            $stmt = $this->db->conn->prepare("INSERT INTO bookings (date_borrow_from, date_to, status, borrow_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$date_borrow_from, $date_to, $status, $borrow_id]);

            $booking_id = $this->db->conn->lastInsertId();

            // Insert books into booking_books table and update quantities
            $bookStmt = $this->db->conn->prepare("INSERT INTO booking_books (booking_id, book_id, quantity) VALUES (?, ?, ?)");
            $updateBookStmt = $this->db->conn->prepare("UPDATE books SET onhand_quantity = onhand_quantity - ? WHERE id = ?");

            foreach ($cart as $book_id => $quantity) {
                // Check stock availability
                $bookCheck = $this->db->conn->prepare("SELECT onhand_quantity FROM books WHERE id = ?");
                $bookCheck->execute([$book_id]);
                $book = $bookCheck->fetch(PDO::FETCH_ASSOC);

                if (!$book || $book['onhand_quantity'] < $quantity) {
                    throw new Exception("Insufficient quantity for book ID $book_id.");
                }

                // Update book quantity and insert into booking_books
                $updateBookStmt->execute([$quantity, $book_id]);
                $bookStmt->execute([$booking_id, $book_id, $quantity]);
            }

            $this->db->conn->commit();

            // Clear the cart after successful booking
            $_SESSION['cart'] = [];

            return $booking_id;
        } catch (Exception $e) {
            $this->db->conn->rollBack();
            return "Error: " . $e->getMessage();
        }
    }
}

$booking = new Booking();
$error = "";

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a book to the cart
if (isset($_POST['add_to_cart'])) {
    $book_id = $_POST['book_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity <= 0) {
        $error = "Please enter a valid quantity.";
    } else {
        $book = $booking->db->conn->prepare("SELECT onhand_quantity FROM books WHERE id = ?");
        $book->execute([$book_id]);
        $bookData = $book->fetch(PDO::FETCH_ASSOC);

        if ($bookData && $bookData['onhand_quantity'] >= $quantity) {
            // Reduce the available quantity in the database
            $updateResult = $booking->updateBookQuantity($book_id, -$quantity);

            if ($updateResult) {
                // Add the book to the cart
                if (isset($_SESSION['cart'][$book_id])) {
                    $_SESSION['cart'][$book_id] += $quantity;
                } else {
                    $_SESSION['cart'][$book_id] = $quantity;
                }
            } else {
                $error = "Failed to update book quantity.";
            }
        } else {
            $error = "The selected book does not exist or does not have enough stock.";
        }
    }
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $remove_id = $_POST['book_id'];

    if (isset($_SESSION['cart'][$remove_id])) {
        $quantity = $_SESSION['cart'][$remove_id];

        // Restore the available quantity in the database
        $updateResult = $booking->updateBookQuantity($remove_id, $quantity);

        if ($updateResult) {
            unset($_SESSION['cart'][$remove_id]); // Remove the book from the cart
        } else {
            $error = "Failed to update book quantity.";
        }
    }
}

// Handle completing the booking
if (isset($_POST['add_booking'])) {
    $date_borrow_from = $_POST['date_borrow_from'];
    $date_to = $_POST['date_to'];
    $status = $_POST['status'];
    $borrow_id = $_POST['borrow_id'];
    $cart = $_SESSION['cart'];

    if (empty($cart)) {
        $error = "Please add at least one book to the cart.";
    } else {
        $result = $booking->addBooking($date_borrow_from, $date_to, $status, $borrow_id, $cart);
        if (is_numeric($result)) {
            $_SESSION['cart'] = []; // Clear the cart after successful booking
            header("Location: bookings.php?success=1&booking_id=$result");
            exit();
        } else {
            $error = $result; // Display the error returned by addBooking
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookings - Library Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Library Dashboard</a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="books.php">Books Info</a></li>
                <li class="nav-item"><a class="nav-link" href="borrower.php">Borrowers</a></li>
                <li class="nav-item"><a class="nav-link active" href="bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="logs.php">Logs</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">
            Booking finalized successfully! Your cart has been cleared.<br>
            <strong>Booking ID:</strong> <?= htmlspecialchars($_GET['booking_id']) ?>
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Bookings</h2>

    <!-- Add to Cart Form -->
    <div class="card mb-4">
        <div class="card-header">Add Books to Cart</div>
        <div class="card-body">
            <form method="POST" action="bookings.php">
                <div class="mb-3">
                    <label for="book_id" class="form-label">Select Book:</label>
                    <select name="book_id" id="book_id" class="form-control" required>
                        <?php
                        $books = $booking->getBooks();
                        if (!empty($books)) {
                            foreach ($books as $book) {
                                echo "<option value='{$book['id']}'>{$book['title']} (Available: {$book['onhand_quantity']})</option>";
                            }
                        } else {
                            echo "<option value=''>No books available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                </div>
                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Display Cart -->
    <div class="card mb-4">
        <div class="card-header">Cart</div>
        <div class="card-body">
            <?php if (empty($_SESSION['cart'])): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php
                    foreach ($_SESSION['cart'] as $book_id => $quantity):
                        $book = $booking->db->conn->prepare("SELECT title FROM books WHERE id = ?");
                        $book->execute([$book_id]);
                        $data = $book->fetch(PDO::FETCH_ASSOC);

                        if ($data): // Check if the query returned a valid result
                    ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($data['title']) ?> (Qty: <?= $quantity ?>)
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="book_id" value="<?= $book_id ?>">
                                    <button type="submit" name="remove_from_cart" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </li>
                    <?php
                        else:
                            // Remove invalid book from the cart
                            unset($_SESSION['cart'][$book_id]);
                            echo "<li class='list-group-item text-danger'>Error: Book not found (ID: $book_id). It has been removed from your cart.</li>";
                        endif;
                    endforeach;
                    ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Finalize Booking Form -->
    <div class="card mb-4">
        <div class="card-header">Finalize Booking</div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="bookings.php">
                <div class="mb-3">
                    <label for="date_borrow_from" class="form-label">Date Borrow From:</label>
                    <input type="date" name="date_borrow_from" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="date_to" class="form-label">Date To:</label>
                    <input type="date" name="date_to" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="borrow_id" class="form-label">Select Borrower:</label>
                    <select name="borrow_id" class="form-control" required>
                        <?php
                        $borrowers = $booking->getBorrowers();
                        foreach ($borrowers as $borrower) {
                            echo "<option value='{$borrower['id']}'>{$borrower['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="add_booking" class="btn btn-success w-100">Complete Booking</button>
            </form>
        </div>
    </div>
</div>

<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>