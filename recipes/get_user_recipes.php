<?php
require '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE created_by = ?");
$stmt->execute([$user_id]);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($recipes as $recipe) {
    echo "
    <div class='card mb-3'>
        <div class='row no-gutters'>
            <div class='col-md-4'>
                <img src='/RecipeIngredient/img/{$recipe['image']}' class='card-img' alt='{$recipe['title']}'>
            </div>
            <div class='col-md-8'>
                <div class='card-body'>
                    <h5 class='card-title'>{$recipe['title']}</h5>
                    <p class='card-text'>".substr($recipe['description'], 0, 100)."...</p>
                    <a href='/RecipeIngredient/recipes/recipe_details.php?id={$recipe['id']}' class='btn btn-primary'>View Recipe</a>
                    <a href='/RecipeIngredient/recipes/edit_recipe.php?id={$recipe['id']}' class='btn btn-warning'>Edit</a>
                    <a href='/RecipeIngredient/recipes/delete_recipe.php?id={$recipe['id']}' class='btn btn-danger'>Delete</a>
                </div>
            </div>
        </div>
    </div>";
}
?>
