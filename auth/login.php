<?php
require '../includes/db.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../profile.php');
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<?php
include '../includes/header.php';
?>
<!--<?php if ($message): ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
<?php endif; ?>
<form action="login.php" method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .container-fluid {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(154, 20, 127);
        }
        .left-container {
            background: url('https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center;
            background-size: cover;
            height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h2 {
            font-weight: 600;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .social-icons a {
            margin: 0 10px;
            font-size: 18px;
            color: rgb(154, 20, 127);
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }
        .social-icons a:hover {
            background: rgb(154, 20, 127);
            color: white;
        }
        .form-control {
            border-radius: 8px;
        }
        .login-btn {
            background: rgb(154, 20, 127);
            color: white;
            border-radius: 8px;
            padding: 10px;
        }
        .login-btn:hover {
            background: rgb(120, 10, 100);
        }
.navbar {
    background-color: rgb(154, 20, 127) !important; /* Header background color */
    padding: 15px;
}
.navbar-brand {
    color: #ffffff !important; /* White text for brand */
    font-size: 1.8rem;
    font-weight: bold;
}

/* Navbar Links */
.navbar-nav .nav-item .nav-link {
    color: #ffffff !important; /* White text */
    font-size: 1.1rem;
    font-weight: bold;
    padding: 10px 15px;
}

        @media (max-width: 768px) {
            .left-container {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row w-100">
        <div class="col-md-6 left-container"></div>
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="login-container">
                <h2>Login</h2>

                <!-- Social Login Icons -->
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <input type="checkbox" id="rememberMe">
                            <label for="rememberMe">Remember Me</label>
                    <button type="submit" class="btn login-btn w-100 mt-3">Login</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html> 
