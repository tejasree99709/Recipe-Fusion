<?php
require '../includes/db.php';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$query = "SELECT recipes.*, users.name FROM recipes 
          JOIN users ON recipes.created_by = users.id WHERE 1=1";

if (!empty($category)) {
    $query .= " AND recipes.category = :category";
}
if (!empty($search)) {
    $query .= " AND (recipes.title LIKE :search OR recipes.description LIKE :search)";
}

$query .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
if (!empty($category)) {
    $stmt->bindParam(':category', $category);
}
if (!empty($search)) {
    $search = "%$search%";
    $stmt->bindParam(':search', $search);
}
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($recipes as $recipe) {
    $recipeUrl = "/RecipeIngredient/recipes/recipe_details.php?id={$recipe['id']}";
    echo "
    <div class='col-md-4'>
        <div class='card mb-4'>
            <img src='/RecipeIngredient/img/{$recipe['image']}' class='card-img-top' alt='{$recipe['title']}'>
            <div class='card-body'>
                <h5 class='card-title'>{$recipe['title']}</h5>
                <p class='card-text'>By: {$recipe['name']}</p>
                <p class='card-text'>".substr($recipe['description'], 0, 100)."...</p>
                <a href='{$recipeUrl}' class='btn btn-primary'>View Recipe</a>
                <button class='btn btn-secondary' onclick='shareRecipe(\"{$recipeUrl}\")'>Share</button>
            </div>
        </div>
    </div>";
}
?>
<div id="shareModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Select a user to share</h3>
        <ul id="userList"></ul>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 10;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    width: 300px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
}

.close {
    color: red;
    float: right;
    font-size: 24px;
    cursor: pointer;
}

ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    padding: 10px;
    background: #f1f1f1;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
}

ul li:hover {
    background: #ddd;
}
</style>

<script>
function shareRecipe(url) {
    fetch('fetch_users.php') 
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(users => {
            let userList = document.getElementById("userList");
            userList.innerHTML = ""; // Clear previous content

            if (users.length === 0) {
                userList.innerHTML = "<li>No users available</li>";
            } else {
                users.forEach(user => {
                    let li = document.createElement("li");
                    li.textContent = user.username;
                    li.onclick = function() { shareWithUser(user.username, url); };
                    userList.appendChild(li);
                });
            }

            document.getElementById("shareModal").style.display = "block";
        })
        .catch(error => console.error('Error fetching users:', error));
}

function closeModal() {
    document.getElementById("shareModal").style.display = "none";
}

function shareWithUser(username, url) {
    alert("Recipe shared with " + username + "! \nLink: " + window.location.origin + url);
    closeModal();
}
</script>
