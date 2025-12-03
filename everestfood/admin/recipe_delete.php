<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: recipes.php");
exit;
