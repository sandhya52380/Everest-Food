<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query(
    "SELECT id, title, short_description, created_at
     FROM recipes
     ORDER BY created_at DESC"
);
$recipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Recipes | everestfood</title>
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
  <h1>Recipes</h1>
  <p><a href="recipe_add.php" class="btn primary">Add New Recipe</a></p>

  <?php if (empty($recipes)): ?>
    <p>No recipes yet.</p>
  <?php else: ?>
    <table border="0" cellpadding="6" cellspacing="0" style="width:100%; background:#fff; border-radius:8px;">
      <thead>
        <tr style="background:#eee;">
          <th align="left">Title</th>
          <th align="left">Description</th>
          <th align="left">Created</th>
          <th align="left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recipes as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['short_description'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['created_at']) ?></td>
            <td>
              <a href="../recipe.php?id=<?= (int)$r['id'] ?>" class="btn secondary">View</a>
              <a href="recipe_edit.php?id=<?= (int)$r['id'] ?>" class="btn secondary">Edit</a>
              <a href="recipe_delete.php?id=<?= (int)$r['id'] ?>"
                 class="btn danger"
                 onclick="return confirm('Delete this recipe?');">
                Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<footer>
  © 2025 everestfood – Admin
</footer>

</body>
</html>
