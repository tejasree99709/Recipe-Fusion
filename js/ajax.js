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
