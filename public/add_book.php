<?php
require_once "../config/db.php";
require_once "../src/functions.php";
require_login();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = intval($_POST['publication_year'] ?? 0);
    $isbn = trim($_POST['isbn'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($author)) $errors[] = "Author is required.";
    if (empty($genre)) $errors[] = "Genre is required.";
    if ($year < 1) $errors[] = "Invalid publication year.";

    // If valid, insert
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author, genre, publication_year, isbn, description)
            VALUES (:title, :author, :genre, :year, :isbn, :description)
        ");

        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':genre' => $genre,
            ':year' => $year,
            ':isbn' => $isbn,
            ':description' => $description
        ]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
</head>
<body>

<h2>Add New Book</h2>
<a href="index.php">⬅ Back</a>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST">

    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" required><br><br>

    <label>Genre:</label><br>
    <input type="text" name="genre" required><br><br>

    <label>Publication Year:</label><br>
    <input type="number" name="publication_year" required><br><br>

    <label>ISBN (optional):</label><br>
    <input type="text" name="isbn"><br><br>

    <label>Description (optional):</label><br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Add Book</button>
</form>

</body>
</html>
