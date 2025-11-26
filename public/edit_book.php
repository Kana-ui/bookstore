<?php
require_once "../config/db.php";
require_once "../src/functions.php";

require_login();

$errors = [];

// Get book id
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid book ID.");
}

// Fetch existing book
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute([':id' => $id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read & sanitise input
    $title       = post('title');
    $author      = post('author');
    $genre       = post('genre');
    $yearRaw     = $_POST['publication_year'] ?? '';
    $isbn        = post('isbn');
    $description = post('description');

    $year = (int) $yearRaw;

    // Validation
    if ($title === '')  $errors[] = "Title is required.";
    if ($author === '') $errors[] = "Author is required.";
    if ($genre === '')  $errors[] = "Genre is required.";
    if ($year < 1)      $errors[] = "Invalid publication year.";

    if (empty($errors)) {
        $updateStmt = $pdo->prepare("
            UPDATE books
            SET title = :title,
                author = :author,
                genre = :genre,
                publication_year = :year,
                isbn = :isbn,
                description = :description
            WHERE id = :id
        ");

        $updateStmt->execute([
            ':title'       => $title,
            ':author'      => $author,
            ':genre'       => $genre,
            ':year'        => $year,
            ':isbn'        => $isbn,
            ':description' => $description,
            ':id'          => $id
        ]);

        header("Location: index.php");
        exit;
    } else {
        // Update local $book for redisplay in form
        $book['title']            = $title;
        $book['author']           = $author;
        $book['genre']            = $genre;
        $book['publication_year'] = $year;
        $book['isbn']             = $isbn;
        $book['description']      = $description;
    }
}

// Render Twig template
render_template('edit_book.html.twig', [
    'book'   => $book,
    'errors' => $errors,
]);
