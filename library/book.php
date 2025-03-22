<?php

require_once "Database.php";

class Book
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Create (Add Book)
    public function addBook($title, $author, $year, $barcode, $stat)
    {
        $stmt = $this->db->conn->prepare("INSERT INTO books (title, author, published_year, barcode, stat) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $year, $barcode, $stat]);
    }

    // Read (Get All Books)
    public function getBooks()
    {
        $stmt = $this->db->conn->prepare("SELECT * FROM books ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Single Book by ID
    public function getBookById($id)
    {
        $stmt = $this->db->conn->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update (Edit Book)
    public function updateBook($id, $title, $author, $year, $barcode, $stat)
    {
        $stmt = $this->db->conn->prepare("UPDATE books SET title = ?, author = ?, published_year = ?, barcode = ?, stat = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $year, $barcode, $stat, $id]);
    }

    // Delete (Remove Book)
    public function deleteBook($id)
    {
        $stmt = $this->db->conn->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }

        public function searchBooks($keyword) {
        $stmt = $this->db->conn->prepare("SELECT * FROM books 
                                      WHERE title LIKE ? 
                                         OR author LIKE ? 
                                         OR published_year LIKE ?
                                         OR barcode LIKE ?
                                         OR stat LIKE ?
                                      ORDER BY id DESC");
        $search = "%$keyword%";
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

