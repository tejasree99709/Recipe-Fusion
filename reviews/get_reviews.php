<?php
require '../includes/db.php';

$recipe_id = $_GET['recipe_id'] ?? '';
$rating_filter = $_GET['rating'] ?? '';
$query = "SELECT * FROM reviews WHERE recipe_id = :recipe_id";
$params = ['recipe_id' => $recipe_id];

if (!empty($rating_filter)) {
    $query .= " AND rating = :rating";
    $params['rating'] = $rating_filter;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($reviews) > 0) {
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<strong>Rating: {$review['rating']}⭐</strong><br>";
        echo "<p>" . htmlspecialchars($review['comment']) . "</p>";
        echo "<hr>";
        echo "</div>";
    }
} else {
    echo "<p>No reviews found.</p>";
}
?>
