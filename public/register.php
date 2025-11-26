<?php
require_once "../config/db.php";
require_once "../src/functions.php";

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Basic validation
    if ($username === '') {
        $errors[] = "Username is required.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $password2) {
        $errors[] = "Passwords do not match.";
    }

    if (!in_array($role, ['admin', 'user'], true)) {
        $role = 'user';
    }

    // Check if username already exists
    if (empty($errors)) {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $checkStmt->execute([':username' => $username]);

        if ($checkStmt->fetch()) {
            $errors[] = "Username is already taken.";
        }
    }

    // If no errors, create user
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $pdo->prepare("
            INSERT INTO users (username, password_hash, role)
            VALUES (:username, :password_hash, :role)
        ");

        $insertStmt->execute([
            ':username'      => $username,
            ':password_hash' => $passwordHash,
            ':role'          => $role
        ]);

        $success = "User registered successfully. You can now log in.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
</head>
<body>

<h2>Register New User</h2>
<a href="index.php">⬅ Back to Home</a>
<hr>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= e($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="password2" required><br><br>

    <label>Role:</label><br>
    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
