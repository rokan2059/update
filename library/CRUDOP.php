<?php
require_once "database.php";

class Book {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addBook($title, $author, $publish_year, $barcode, $onhand_quantity) {
        $stmt = $this->db->conn->prepare("INSERT INTO books (title, author, publish_year, barcode, onhand_quantity) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $publish_year, $barcode, $onhand_quantity]);
    }

    public function getBooks() {
        $stmt = $this->db->conn->prepare("SELECT * FROM books ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBook($id, $title, $author, $publish_year, $barcode, $onhand_quantity) {
        $stmt = $this->db->conn->prepare("UPDATE books SET title = ?, author = ?, publish_year = ?, barcode = ?, onhand_quantity = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $publish_year, $barcode, $onhand_quantity, $id]);
    }

    public function deleteBook($id) {
        $stmt = $this->db->conn->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Add the addLog method here
    public function addLog($bookings_id, $title, $book_quantity, $type) {
        $stmt = $this->db->conn->prepare("INSERT INTO logs (bookings_id, title, book_quantity, type) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$bookings_id, $title, $book_quantity, $type]);
    }
}