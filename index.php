<?php
require 'includes/db.php';
session_start();
$categories = $pdo->query("SELECT DISTINCT category FROM recipes")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center p-3 bg-light">
    <h2>Recipe Categories</h2>
    <div>
        <a href="profile.php" class="btn btn-success me-2">Profile</a>
        <a href="home.html" class="btn btn-danger">Logout</a>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <h3>Categories</h3>
        <ul class="list-group" id="category-list">
            <?php foreach ($categories as $category): ?>
                <li class="list-group-item">
                    <a href="#" class="category-filter" data-category="<?php echo htmlspecialchars($category['category']); ?>">
                        <?php echo htmlspecialchars($category['category']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <button id="clear-filters" class="btn btn-secondary mt-2">Clear</button>
    </div>
    <div class="col-md-9">
        <h2>All Recipes</h2>
        <div class="form-group">
            <input type="text" id="search-bar" class="form-control" placeholder="Search for recipes...">
        </div>

        <div class="row" id="recipe-list">
        </div>
    </div>
</div>

<style>
#category-list {
    padding: 0;
}

#category-list .list-group-item {
    background-color: white;
    color: rgb(154,20,127);
    border: 1px solid #ddd;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}
#category-list .list-group-item:hover {
    background-color: #f8f9fa;
}
#category-list .list-group-item.active {
    background-color: rgb(250, 241, 249) !important;
    color: rgb(154,20,127) !important;
    font-weight: bold;
    border: 2px solid rgb(154,20,127);
}
#category-list .category-filter {
    text-decoration: none;
    color: rgb(154,20,127);
    display: block;
    width: 100%;
    padding: 10px;
    font-weight: bold;
}
</style>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    loadRecipes();

    function loadRecipes(category = '', search = '', rating = '', page = 1) {
        $.ajax({
            url: 'recipes/get_recipes.php',
            method: 'GET',
            data: { category: category, search: search, rating: rating, page: page },
            success: function(response) {
                $('#recipe-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error loading recipes:", error);
            }
        });
    }
    $('#search-bar').on('input', function() {
        const search = $(this).val();
        const category = $('.category-filter.active').data('category') || '';
        const rating = $('#rating-filter').val();
        loadRecipes(category, search, rating);
    });
    $(document).on('click', '.category-filter', function(e) {
        e.preventDefault();
        $('.category-filter').removeClass('active');
        $(this).parent().addClass('active');
        const category = $(this).data('category');
        const search = $('#search-bar').val();
        const rating = $('#rating-filter').val();
        loadRecipes(category, search, rating);
    });
    $('#clear-filters').on('click', function() {
        $('.category-filter').removeClass('active');
        $('#search-bar').val('');
        $('#rating-filter').val('');
        loadRecipes();
    });
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const category = $('.category-filter.active').data('category') || '';
        const search = $('#search-bar').val();
        const rating = $('#rating-filter').val();
        loadRecipes(category, search, rating, page);
    });
});
</script>
