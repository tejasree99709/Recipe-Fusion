<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<?php include 'includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center p-3 bg-light">
    <div>
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    <div>
        <a href="index.php" class="btn btn-success">Recipes</a> 
        <a href="home.html" class="btn btn-danger">Logout</a> 
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs" id="recipeTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="view-recipes-tab" data-toggle="tab" href="#view-recipes" role="tab">View Recipes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="add-recipe-tab" data-toggle="tab" href="#add-recipe" role="tab">Add Recipe</a>
  </li>
</ul>

<div class="tab-content" id="recipeTabsContent">
  <div class="tab-pane fade show active" id="view-recipes" role="tabpanel">
    <h3>Your Recipes</h3>
    <div id="user-recipes" class="mt-3">
      <!-- User recipes will be dynamically loaded here -->
    </div>
  </div>
  <div class="tab-pane fade" id="add-recipe" role="tabpanel">
    <h3>Add Recipe</h3>
    <form action="recipes/add_recipe.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Recipe Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control" required></textarea>
      </div>
      <div class="form-group">
        <label for="instructions">Ingredients</label>
        <textarea name="instructions" class="form-control" required></textarea>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <textarea name="category" class="form-control" required></textarea>
      </div>
      <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="video_url">Video URL</label>
        <input type="text" name="video_url" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Add Recipe</button>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    loadUserRecipes();

    function loadUserRecipes() {
        $.ajax({
            url: 'recipes/get_user_recipes.php',
            method: 'GET',
            success: function(response) {
                $('#user-recipes').html(response);
            }
        });
    }
});
</script>
