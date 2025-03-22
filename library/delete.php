<?php

require_once "Book.php";
$book = new Book();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $book->deleteBook($_POST['id']);
}

header("Location: index.php");
exit();

