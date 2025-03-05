$(document).ready(function() {
    loadRecipes();
    $(document).on('click', '.category-filter', function(e) {
        e.preventDefault();

        $('.category-filter').removeClass('active'); 
        $(this).addClass('active'); 

        const category = $(this).data('category'); 
        const search = $('#search-bar').val(); 

        loadRecipes(category, search); 
    });
    $('#search-bar').on('input', function() {
        const search = $(this).val();
        const category = $('.category-filter.active').data('category') || '';
        loadRecipes(category, search);
    });
    $('#clear-filters').on('click', function() {
        $('.category-filter').removeClass('active'); 
        $('#search-bar').val(''); 
        loadRecipes(); 
    });
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const category = $('.category-filter.active').data('category') || '';
        const search = $('#search-bar').val();
        loadRecipes(category, search, page);
    });
});
