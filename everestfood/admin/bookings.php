<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query(
    "SELECT b.*, r.title AS recipe_title
     FROM bookings b
     LEFT JOIN recipes r ON b.recipe_id = r.id
     ORDER BY b.created_at DESC"
);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bookings | everestfood Admin</title>
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
  <h1>Bookings</h1>

  <?php if (empty($bookings)): ?>
    <p>No bookings yet.</p>
  <?php else: ?>
    <table border="0" cellpadding="6" cellspacing="0" style="width:100%; background:#fff; border-radius:8px;">
      <thead>
        <tr style="background:#eee;">
          <th align="left">Name</th>
          <th align="left">Email</th>
          <th align="left">Date & Time</th>
          <th align="left">Guests</th>
          <th align="left">Recipe</th>
          <th align="left">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= htmlspecialchars($b['name']) ?></td>
            <td><?= htmlspecialchars($b['email']) ?></td>
            <td><?= htmlspecialchars($b['booking_date'] . ' ' . $b['booking_time']) ?></td>
            <td><?= (int)$b['guests'] ?></td>
            <td><?= htmlspecialchars($b['recipe_title'] ?? '—') ?></td>
            <td><?= htmlspecialchars($b['status']) ?></td>
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
