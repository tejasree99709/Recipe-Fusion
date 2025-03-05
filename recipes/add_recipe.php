<?php
require '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'])) : '';
    $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : '';
    $instructions = isset($_POST['instructions']) ? trim(htmlspecialchars($_POST['instructions'])) : '';
    $category = isset($_POST['category']) ? trim(htmlspecialchars($_POST['category'])) : '';
    $video_url = isset($_POST['video_url']) ? trim(htmlspecialchars($_POST['video_url'])) : '';

    $image = $_FILES['image']['name'];
    $target = "../img/" . basename($image);
    if (empty($category)) {
        echo "Category field is required.";
        exit();
    }
    if (!is_dir('../img')) {
        mkdir('../img', 0775, true);
    }
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $pdo->prepare("INSERT INTO recipes (title, description, instructions, category, image, video_url, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $instructions, $category, $image, $video_url, $_SESSION['user_id']])) {
            header('Location: ../profile.php');
            exit();
        } else {
            echo "Failed to insert recipe into the database.";
        }
    } else {
        echo "Failed to upload image. Error code: " . $_FILES['image']['error'];
        var_dump($_FILES);
    }
}
?>
