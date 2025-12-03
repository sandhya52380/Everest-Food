<?php
session_start();
require_once __DIR__ . '/config/db.php';

$search      = trim($_GET['q']   ?? '');
$selectedCat = trim($_GET['cat'] ?? '');


$categories = $pdo->query(
    "SELECT id, name FROM categories ORDER BY name"
)->fetchAll();

$sql = "SELECT r.id,
               r.title,
               r.short_description,
               r.image,
               r.created_at
        FROM recipes r";
$params     = [];
$conditions = [];

if ($search !== '') {
    $conditions[] = "(r.title LIKE ? OR r.short_description LIKE ?)";
    $params[] = '%'.$search.'%';
    $params[] = '%'.$search.'%';
}

if ($selectedCat !== '') {
    $conditions[] = "r.id IN (
        SELECT recipe_id
        FROM recipe_category
        WHERE category_id = ?
    )";
    $params[] = (int)$selectedCat;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);


}

$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>everestfood | Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="brand">everestfood</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="book.php">Book a Table</a>
    <a href="admin/login.php">Admin</a>
    <span class="phone">📞 71332456</span>
  </nav>
</header>

<section class="hero">
  <div class="container">
    <h1>Welcome to everestfood</h1>
    <p>Browse our recipes and book a table to enjoy them.</p>
 
  </div>
</section>

<div class="container">
  <h2>Recipes</h2>

  <form method="get" class="recipe-search-bar" style="margin-bottom:20px;">
    <input
      type="text"
      name="q"
      value="<?= htmlspecialchars($search) ?>"
      placeholder="Search by name or description..."
    >

    <select name="cat">
      <option value="">All categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= (int)$cat['id'] ?>"
          <?= ($selectedCat == $cat['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="btn primary">
      Search
    </button>
  </form>

  <?php if (empty($recipes)): ?>
    <p>No recipes found for your search/filter.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($recipes as $r): ?>
        <div class="card">
          <?php if (!empty($r['image'])): ?>
            <img
              src="<?= htmlspecialchars($r['image']) ?>"
              alt="<?= htmlspecialchars($r['title']) ?>"
              style="width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:10px;"
            >
          <?php endif; ?>

          <h2><?= htmlspecialchars($r['title']) ?></h2>
          <p><?= htmlspecialchars($r['short_description'] ?? '') ?></p>
          <a href="recipe.php?id=<?= (int)$r['id'] ?>" class="btn secondary">View Recipe</a>
          <a href="book.php?recipe_id=<?= (int)$r['id'] ?>" class="btn primary" style="margin-left:8px;">
            Book Table
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<footer>
  © 2025 everestfood
</footer>

</body>
</html>
