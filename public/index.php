<?php
require_once "../config/db.php";
require_once "../src/functions.php";

require_login();

// Read search filters (GET)
$titleFilter = isset($_GET['title']) ? trim($_GET['title']) : '';
$genreFilter = isset($_GET['genre']) ? trim($_GET['genre']) : '';
$yearFilter  = isset($_GET['year']) ? (int) $_GET['year'] : 0;

$sql = "SELECT * FROM books";
$conditions = [];
$params = [];

if ($titleFilter !== '') {
    $conditions[] = "title LIKE :title";
    $params[':title'] = '%' . $titleFilter . '%';
}

if ($genreFilter !== '') {
    $conditions[] = "genre LIKE :genre";
    $params[':genre'] = '%' . $genreFilter . '%';
}

if ($yearFilter > 0) {
    $conditions[] = "publication_year = :year";
    $params[':year'] = $yearFilter;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

// Render via Twig
render_template('home.html.twig', [
    'books'       => $books,
    'titleFilter' => $titleFilter,
    'genreFilter' => $genreFilter,
    'yearFilter'  => $yearFilter,
    'username'    => current_username(),
]);
