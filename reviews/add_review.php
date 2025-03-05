<?php
require '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "You must be logged in to submit a review."]);
    exit();
}

$recipe_id = $_POST['recipe_id'];
$user_id = $_SESSION['user_id'];
$rating = $_POST['rating'];
$comment = trim($_POST['comment']);

if ($rating < 1 || $rating > 5) {
    echo json_encode(["error" => "Invalid rating value."]);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO reviews (recipe_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->execute([$recipe_id, $user_id, $rating, $comment]);

echo json_encode(["success" => "Review submitted successfully!"]);
?>
