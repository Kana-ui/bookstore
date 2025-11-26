<?php
require_once "../config/db.php";
require_once "../src/functions.php";

require_login();

$errors = [];
$values = [
    'title'             => '',
    'author'            => '',
    'genre'             => '',
    'publication_year'  => '',
    'isbn'              => '',
    'description'       => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $values['title']            = post('title');
    $values['author']           = post('author');
    $values['genre']            = post('genre');
    $values['publication_year'] = trim($_POST['publication_year'] ?? '');
    $values['isbn']             = post('isbn');
    $values['description']      = post('description');

    $year = (int) $values['publication_year'];

    // Validation
    if ($values['title'] === '')  $errors[] = "Title is required.";
    if ($values['author'] === '') $errors[] = "Author is required.";
    if ($values['genre'] === '')  $errors[] = "Genre is required.";
    if ($year < 1)                $errors[] = "Invalid publication year.";

    // If valid, insert
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author, genre, publication_year, isbn, description)
            VALUES (:title, :author, :genre, :year, :isbn, :description)
        ");

        $stmt->execute([
            ':title'       => $values['title'],
            ':author'      => $values['author'],
            ':genre'       => $values['genre'],
            ':year'        => $year,
            ':isbn'        => $values['isbn'],
            ':description' => $values['description'],
        ]);

        header("Location: index.php");
        exit;
    }
}

// Render Twig template
render_template('add_book.html.twig', [
    'errors' => $errors,
    'values' => $values,
]);
