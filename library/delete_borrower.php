<?php
// filepath: c:\xampp\htdocs\mylibrary\library\delete_borrower.php
require_once "Database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $db = new Database();
    $stmt = $db->conn->prepare("DELETE FROM borrowers WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to the borrower management page
    header("Location: borrower.php?success=1");
    exit();
} else {
    // Redirect back if no ID is provided
    header("Location: borrower.php?error=1");
    exit();
}