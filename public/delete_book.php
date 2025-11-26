<?php
require_once "../config/db.php";
require_once "../src/functions.php";
require_login();
// Get book id from query string
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid book ID.");
}

// Optional: check if book exists first
$stmt = $pdo->prepare("SELECT id FROM books WHERE id = :id");
$stmt->execute([':id' => $id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found.");
}

// Delete the book
$deleteStmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
$deleteStmt->execute([':id' => $id]);

// Redirect back to list
header("Location: index.php");
exit;
