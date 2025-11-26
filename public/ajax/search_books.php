<?php
require_once "../../config/db.php";
require_once "../../src/functions.php";

require_login(); // protect via session

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

// Return ONLY <tr> rows as HTML fragment
if (count($books) === 0) {
    echo '<tr><td colspan="6">No books found.</td></tr>';
    exit;
}

foreach ($books as $b): ?>
<tr>
    <td><?= e($b['id']); ?></td>
    <td><?= e($b['title']); ?></td>
    <td><?= e($b['author']); ?></td>
    <td><?= e($b['genre']); ?></td>
    <td><?= e($b['publication_year']); ?></td>
    <td>
        <a href="../edit_book.php?id=<?= e($b['id']) ?>">✏️ Edit</a> |
        <a href="../delete_book.php?id=<?= e($b['id']) ?>"
           onclick="return confirm('Are you sure you want to delete this book?');">
            🗑 Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>
