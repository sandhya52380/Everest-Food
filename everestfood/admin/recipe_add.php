<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';

$error   = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $short = trim($_POST['short_description'] ?? '');
    $ing   = trim($_POST['ingredients'] ?? '');
    $steps = trim($_POST['steps'] ?? '');

    if ($title === '' || $ing === '' || $steps === '') {
        $error = "Title, ingredients and steps are required.";
    } else {
        
        $stmt = $pdo->prepare(
            "INSERT INTO recipes (title, short_description, ingredients, steps)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$title, $short, $ing, $steps]);

        $recipe_id = $pdo->lastInsertId();

        if (!empty($_POST['categories']) && is_array($_POST['categories'])) {
            $insertCat = $pdo->prepare(
                "INSERT INTO recipe_category (recipe_id, category_id)
                 VALUES (?, ?)"
            );
            foreach ($_POST['categories'] as $c) {
                $insertCat->execute([$recipe_id, (int)$c]);
            }
        }

        $success = "Recipe added successfully.";

        
        $title = $short = $ing = $steps = '';
    }
}


$categories = $pdo->query(
    "SELECT id, name FROM categories ORDER BY name"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Recipe | everestfood</title>
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
  <h1>Add Recipe</h1>

  <?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

 <form method="post" enctype="multipart/form-data">
<label>Short description (optional)</label>
<input type="text" name="short_description"
       value="<?= htmlspecialchars($short ?? '') ?>">


<label>Ingredients</label>
<textarea name="ingredients" rows="5" required><?= htmlspecialchars($ing ?? '') ?></textarea>

    <label>Title</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($title ?? '') ?>" required>





    <label>Steps</label>
    <textarea name="steps" rows="6" required><?= htmlspecialchars($steps ?? '') ?></textarea>

    <label>Categories</label>
    <div style="display:flex; flex-wrap:wrap; gap:15px; background:#f9fafb; padding:15px; border-radius:10px; border:1px solid #ddd;">
      <?php foreach ($categories as $cat): ?>
        <label style="display:flex; align-items:center; gap:6px;">
          <input type="checkbox" name="categories[]"
                 value="<?= (int)$cat['id'] ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </label>
      <?php endforeach; ?>
    </div>

    <button type="submit" class="btn primary" style="margin-top:16px;">Save</button>
  </form>
</div>

<footer>
  © 2025 everestfood – Admin
</footer>

</body>
</html>
