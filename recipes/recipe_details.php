<?php
require '../includes/db.php';
session_start();
$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT recipes.*, users.name FROM recipes JOIN users ON recipes.created_by = users.id WHERE recipes.id = ?');
$stmt->execute([$id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$recipe) {
    header('Location: ../home.php');
    exit();
}
$stmt = $pdo->prepare('SELECT * FROM ingredients WHERE recipe_id = ?');
$stmt->execute([$id]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user_logged_in = isset($_SESSION['user_id']);
$can_review = $user_logged_in && $_SESSION['user_id'] != $recipe['created_by'];
?>
<?php include '../includes/header.php'; ?>
<h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
<img src="/RecipeIngredient/img/<?php echo htmlspecialchars($recipe['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
<p class="mt-4"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
<h3>Ingredients</h3>
<p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
<?php if (!empty($recipe['video_url'])): ?>
    <h3>Recipe Video</h3>
    <?php 
    $videoUrl = htmlspecialchars($recipe['video_url']);
    if (strpos($videoUrl, 'youtube.com/watch') !== false) {
        $videoUrl = preg_replace("/\?v=([a-zA-Z0-9_-]+)/", "/embed/$1", $videoUrl);
    } elseif (strpos($videoUrl, 'youtu.be') !== false) {
        $videoUrl = str_replace("youtu.be/", "www.youtube.com/embed/", $videoUrl);
    }
    ?>
    <?php if (strpos($videoUrl, 'youtube.com/embed') !== false): ?>
        <iframe width="560" height="315" 
            src="<?php echo $videoUrl; ?>" 
            frameborder="0" allowfullscreen>
        </iframe>
    <?php elseif (strpos($videoUrl, '.mp4') !== false): ?>
        <video width="560" height="315" controls>
            <source src="<?php echo $videoUrl; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <p><a href="<?php echo $videoUrl; ?>" target="_blank">Watch Video</a></p>
    <?php endif; ?>
<?php endif; ?>
<h3>Reviews</h3>
<div id="reviews">
</div>
<?php if ($user_logged_in): ?>
    <?php if ($can_review): ?>
    <form id="review-form">
        <div class="form-group">
            <label for="rating">Rating</label>
            <select id="rating" class="form-control">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea id="comment" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
    <?php else: ?>
    <p>You cannot review your own recipe.</p>
    <?php endif; ?>
<?php else: ?>
    <p>You must be <a href="/RecipeIngredient/auth/login.php">logged in</a> or <a href="/RecipeIngredient/auth/register.php">register</a> to be able to review this recipe.</p>
<?php endif; ?>
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Review Submitted</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Your review has been submitted successfully.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
$(document).ready(function() {
    // Load the reviews for the recipe
    loadReviews();

    function loadReviews() {
        let ratingFilter = $('#filter-rating').val(); // Get selected rating

        $.ajax({
            url: '/RecipeIngredient/reviews/get_reviews.php',
            method: 'GET',
            data: {
                recipe_id: <?php echo $recipe['id']; ?>,
                rating: ratingFilter  // Pass selected rating filter
            },
            success: function(response) {
                $('#reviews').html(response);
            }
        });
    }
    $('#filter-rating').change(function() {
        loadReviews(); // Reload reviews when filter changes
    });
    $('#review-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '/RecipeIngredient/reviews/add_review.php',
            method: 'POST',
            data: {
                recipe_id: <?php echo $recipe['id']; ?>,
                rating: $('#rating').val(),
                comment: $('#comment').val()
            },
            success: function(response) {
                $('#comment').val('');
                loadReviews(); // Reload reviews after adding
                $('#feedbackModal').modal('show');
            }
        });
    });
});

</script>
