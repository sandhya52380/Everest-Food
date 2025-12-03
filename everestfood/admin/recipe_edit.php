<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("Invalid recipe.");

$error = "";
$success = "";

// Fetch existing
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
$recipe = $stmt->fetch();
if (!$recipe) die("Recipe not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $short   = trim($_POST['short_description'] ?? '');
    $ing     = trim($_POST['ingredients'] ?? '');
    $steps   = trim($_POST['steps'] ?? '');

    if ($title === '' || $ing === '' || $steps === '') {
        $error = "Title, ingredients and steps are required.";
    } else {
        $update = $pdo->prepare(
            "UPDATE recipes
             SET title = ?, short_description = ?, ingredients = ?, steps = ?
             WHERE id = ?"
        );
        $update->execute([$title, $short, $ing, $steps, $id]);
        $success = "Recipe updated successfully.";

        // Refresh data
        $stmt->execute([$id]);
        $recipe = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Recipe | everestfood</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
  <div class="brand">everestfood – Admin</div>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="recipes.php">Recipes</a>
    <a href="bookings.php">Bookings</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <h1>Edit Recipe</h1>

  <?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post">
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>

    <label>Short description (optional)</label>
    <input type="text" name="short_description" value="<?= htmlspecialchars($recipe['short_description'] ?? '') ?>">

    <label>Ingredients</label>
    <textarea name="ingredients" rows="5" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea>

    <label>Steps</label>
    <textarea name="steps" rows="6" required><?= htmlspecialchars($recipe['steps']) ?></textarea>

    <button type="submit" class="btn primary" style="margin-top:12px;">Update</button>
  </form>
</div>

<footer>
  © 2025 everestfood – Admin
</footer>

</body>
</html>
