<?php
require_once "../config/db.php";
require_once "../src/functions.php";

// Fetch all books
$stmt = $pdo->prepare("SELECT * FROM books ORDER BY created_at DESC");
$stmt->execute();
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

<h1>Bookstore</h1>

<a href="add_book.php">Add New Book</a>
<hr>

<?php if (count($books) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($books as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['id']); ?></td>
                <td><?= htmlspecialchars($b['title']); ?></td>
                <td><?= htmlspecialchars($b['author']); ?></td>
                <td><?= htmlspecialchars($b['genre']); ?></td>
                <td><?= htmlspecialchars($b['publication_year']); ?></td>

                <td>
                    <a href="edit_book.php?id=<?= $b['id'] ?>">Edit</a> |
                    <a href="delete_book.php?id=<?= $b['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this book?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php else: ?>
    <p>No books found.</p>
<?php endif; ?>

</body>
</html>
