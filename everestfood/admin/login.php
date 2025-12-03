<?php
session_start();
require_once __DIR__ . '/../config/admin_config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | everestfood</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
  <div class="brand">everestfood – Admin</div>
  <nav>
    <a href="../index.php">Public Site</a>
  </nav>
</header>

<div class="container" style="max-width:400px;">
  <h1>Admin Login</h1>

  <?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" class="btn primary" style="margin-top:12px;">Login</button>
  </form>
</div>

<footer>
  © 2025 everestfood – Admin
</footer>

</body>
</html>
