<?php
require_once "../config/db.php";
require_once "../src/functions.php";

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = post('title');
    $author = post('author');
    $genre = post('genre');
    $year = (int) ($_POST['publication_year'] ?? 0);
    $isbn = post('isbn');
    $description = post('description');

    if ($title === '') $errors[] = "Title is required.";
    if ($author === '') $errors[] = "Author is required.";
    if ($genre === '') $errors[] = "Genre is required.";
    if ($year < 1) $errors[] = "Invalid publication year.";

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
            ':title' => $title,
            ':author' => $author,
            ':genre' => $genre,
            ':year' => $year,
            ':isbn' => $isbn,
            ':description' => $description,
            ':id' => $id
        ]);

        header("Location: index.php");
        exit;
    } else {
        // repopulate form with submitted values
        $book['title'] = $title;
        $book['author'] = $author;
        $book['genre'] = $genre;
        $book['publication_year'] = $year;
        $book['isbn'] = $isbn;
        $book['description'] = $description;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
</head>
<body>

<h2>Edit Book</h2>
<a href="index.php">⬅ Back</a>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST">

    <label>Title:</label><br>
    <input type="text" name="title" value="<?= e($book['title']) ?>" required><br><br>

    <label>Author:</label><br>
    <input type="text" name="author" value="<?= e($book['author']) ?>" required><br><br>

    <label>Genre:</label><br>
    <input type="text" name="genre" value="<?= e($book['genre']) ?>" required><br><br>

    <label>Publication Year:</label><br>
    <input type="number" name="publication_year" value="<?= e($book['publication_year']) ?>" required><br><br>

    <label>ISBN (optional):</label><br>
    <input type="text" name="isbn" value="<?= e($book['isbn']) ?>"><br><br>

    <label>Description (optional):</label><br>
    <textarea name="description"><?= e($book['description']) ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
