<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | everestfood</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
  <div class="brand">everestfood – Admin</div>
  <nav>
    <a href="../index.php">Public Site</a>
    <a href="recipes.php">Recipes</a>
    <a href="bookings.php">Bookings</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <h1>Dashboard</h1>
  <p>Welcome, admin.</p>

  <div class="grid">
    <div class="card">
      <h2>Recipes</h2>
      <p>View, add, edit, and delete recipes.</p>
      <a href="recipes.php" class="btn primary">Manage Recipes</a>
    </div>
    <div class="card">
      <h2>Bookings</h2>
      <p>See all table reservation requests.</p>
      <a href="bookings.php" class="btn primary">View Bookings</a>
    </div>
  </div>
</div>

<footer>
  © 2025 everestfood – Admin
</footer>

</body>
</html>
