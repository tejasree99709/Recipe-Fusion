<?php
require '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category']; // Get category
    $video_url = $_POST['video_url']; // Get video URL
    $image = $_FILES['image']['name'];
    $target = "../img/" . basename($image);
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $stmt = $pdo->prepare("UPDATE recipes SET title = ?, description = ?, instructions = ?, category = ?, image = ?, video_url = ? WHERE id = ? AND created_by = ?");
        $stmt->execute([$title, $description, $instructions, $category, $image, $video_url, $id, $_SESSION['user_id']]);
    } else {
        // Prepare and execute the SQL statement to update the recipe without changing the image
        $stmt = $pdo->prepare("UPDATE recipes SET title = ?, description = ?, instructions = ?, category = ?, video_url = ? WHERE id = ? AND created_by = ?");
        $stmt->execute([$title, $description, $instructions, $category, $video_url, $id, $_SESSION['user_id']]);
    }
    header('Location: ../profile.php');
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$recipe = $stmt->fetch();
if (!$recipe) {
    header('Location: ../profile.php');
    exit();
}
?>

<?php include '../includes/header.php'; ?>
<h2>Edit Recipe</h2>
<form action="edit_recipe.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">
    <div class="form-group">
        <label for="title">Recipe Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="instructions">Ingredients</label>
        <textarea name="instructions" class="form-control" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <textarea name="category" class="form-control" required></textarea>
      </div>
    <div class="form-group">
        <label for="video_url">Video URL</label>
        <input type="text" name="video_url" class="form-control" value="<?php echo htmlspecialchars($recipe['video_url']); ?>">
        <small class="form-text text-muted">Enter a YouTube or MP4 video URL.</small>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-warning">Update Recipe</button>
</form>

<?php include '../includes/footer.php'; ?>
