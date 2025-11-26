<?php
require_once "../config/db.php";
require_once "../src/functions.php";

$errors = [];
$captchaQuestion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = post('username');
    $password = $_POST['password'] ?? '';
    $captchaInput = $_POST['captcha'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
    }

    // CAPTCHA check
    if (!validate_captcha($captchaInput)) {
        $errors[] = "CAPTCHA answer is incorrect.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    }

    // After processing POST, always create a fresh captcha for redisplay
    $captchaQuestion = generate_captcha_question();

} else {
    // First page load (GET) – create captcha
    $captchaQuestion = generate_captcha_question();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>
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


<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>CAPTCHA: What is <?= e($captchaQuestion) ?> ?</label><br>
    <input type="number" name="captcha" required><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>
