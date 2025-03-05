<?php
require '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
header('Location: ../profile.php');
exit();
?>
