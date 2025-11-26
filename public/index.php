<?php
require_once "../config/db.php";
require_once "../src/functions.php";

require_login();

// Read search filters (GET for initial load)
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookstore - Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>📚 Bookstore</h1>

<p>
    Logged in as: <?= e(current_username()) ?> |
    <a href="logout.php">Logout</a>
</p>

<a href="add_book.php">➕ Add New Book</a>
<hr>

<h3>Search Books (AJAX live search)</h3>
<form method="get" action="index.php">
    <label>Title contains:</label>
    <input type="text" name="title" value="<?= e($titleFilter) ?>">

    <label>Genre contains:</label>
    <input type="text" name="genre" value="<?= e($genreFilter) ?>">

    <label>Year equals:</label>
    <input type="number" name="year" value="<?= $yearFilter > 0 ? e((string)$yearFilter) : '' ?>">

    <button type="submit">Search (normal)</button>
    <a href="index.php">Reset</a>
</form>

<hr>

<?php if (count($books) > 0): ?>
    <p>Found <?= count($books) ?> book(s).</p>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
        <tbody id="books-body">
        <?php foreach ($books as $b): ?>
            <tr>
                <td><?= e($b['id']); ?></td>
                <td><?= e($b['title']); ?></td>
                <td><?= e($b['author']); ?></td>
                <td><?= e($b['genre']); ?></td>
                <td><?= e($b['publication_year']); ?></td>
                <td>
                    <a href="edit_book.php?id=<?= e($b['id']) ?>">✏️ Edit</a> |
                    <a href="delete_book.php?id=<?= e($b['id']) ?>"
                       onclick="return confirm('Are you sure you want to delete this book?');">
                        🗑 Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>No books found.</p>
<?php endif; ?>

<script src="../assets/js/search.js"></script>
</body>
</html>
