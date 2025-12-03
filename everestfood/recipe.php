<?php
session_start();
require_once __DIR__ . '/config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id, title, short_description, ingredients, steps, image, created_at
     FROM recipes
     WHERE id = ?"
);
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    header('Location: index.php');
    exit;
}

// Turn "flour, sugar, milk" into a list
$ingredientItems = array_filter(
    array_map('trim', explode(',', $recipe['ingredients'] ?? ''))
);

// Turn multi-line steps into a list
$stepLines = array_filter(
    array_map('trim', preg_split('/\r\n|\r|\n/', $recipe['steps'] ?? ''))
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($recipe['title']) ?> | everestfood</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="brand">everestfood</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="book.php">Book a Table</a>
    <a href="admin/login.php">Admin</a>
  </nav>
</header>

<div class="recipe-page">
  <div class="recipe-card">

    <?php if (!empty($recipe['image'])): ?>
      <img
        src="<?= htmlspecialchars($recipe['image']) ?>"
        alt="<?= htmlspecialchars($recipe['title']) ?>"
      >
    <?php endif; ?>

    <h1><?= htmlspecialchars($recipe['title']) ?></h1>

    <?php if (!empty($recipe['short_description'])): ?>
      <p class="recipe-subtitle">
        <strong><?= htmlspecialchars($recipe['short_description']) ?></strong>
      </p>
    <?php endif; ?>

    <p class="recipe-meta">
      Added on <?= htmlspecialchars(date('d M Y', strtotime($recipe['created_at']))) ?>
    </p>

    <div class="recipe-sections">
      <section>
        <div class="recipe-section-title">Ingredients</div>
        <?php if ($ingredientItems): ?>
          <ul class="recipe-ingredients-list">
            <?php foreach ($ingredientItems as $ing): ?>
              <li><?= htmlspecialchars($ing) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No ingredients listed.</p>
        <?php endif; ?>
      </section>

      <section>
        <div class="recipe-section-title">Steps</div>
        <?php if ($stepLines): ?>
          <ul class="recipe-steps-list">
            <?php foreach ($stepLines as $line): ?>
              <li><?= htmlspecialchars($line) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No steps listed.</p>
        <?php endif; ?>
      </section>
    </div>

    <div class="recipe-actions">
      <a href="book.php?recipe_id=<?= (int)$recipe['id'] ?>" class="btn primary">
        Book a Table
      </a>
    </div>

  </div>
</div>

<footer>
  © 2025 everestfood
</footer>

</body>
</html>
