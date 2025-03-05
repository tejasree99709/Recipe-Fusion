<?php
require '../includes/db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $city = !empty($_POST['city']) ? $_POST['city'] : NULL;
    $gender = ($_POST['gender'] !== 'Select Gender') ? $_POST['gender'] : NULL;
    $bio = !empty($_POST['bio']) ? $_POST['bio'] : NULL;
    
    $profile_image = NULL;
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES['profile_image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $profile_image = $file_name;
        } else {
            $message = "Error uploading profile image.";
        }
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $message = "Email already exists.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, city, gender, bio, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password, $city, $gender, $bio, $profile_image])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $name;
            header('Location: ../auth/login.php');
            exit();
        } else {
            $message = "Error creating account. Please try again.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="container-box d-flex">
        <div class="form-container">
            <h2 class="mb-4"><strong>SIGN UP</strong></h2>
            <form action="register.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option>Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <button type="submit" class="btn signup-btn">Sign Up</button>
            </form>
        </div>
        <div class="image-container"></div>
    </div>
</div>

</body>
</html>

<style>
body {
    background-color: rgb(154, 20, 127);
    font-family: Arial, sans-serif;
}
.container-box {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    overflow: hidden;
    display: flex;
    width: 90%;
}
.form-container {
    width: 50%;
    padding: 40px;
}
.image-container {
    width: 50%;
    background: url('https://images.pexels.com/photos/12955709/pexels-photo-12955709.jpeg?auto=compress&cs=tinysrgb&w=800&lazy=load') no-repeat center center;
    background-size: cover;
    border-radius: 0 10px 10px 0;
}
h2 {
    color: #333;
    text-align: left;
}
.btn.signup-btn {
    background: rgb(255, 140, 0);
    color: white;
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s ease-in-out;
    border: none;
}
.btn.signup-btn:hover {
    background: rgb(220, 120, 0);
}
@media (max-width: 768px) {
    .container-box {
        flex-direction: column;
    }
    .image-container {
        display: none;
    }
    .form-container {
        width: 100%;
    }
}
</style>
