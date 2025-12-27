<?php
session_start();
include 'config.php';

$error = "";
$logout_message = "";

// Only show logout message if redirected from logout.php and not submitting login form
if (isset($_GET['logout']) && $_GET['logout'] == 1 && $_SERVER['REQUEST_METHOD'] != 'POST') {
    $logout_message = "You have successfully logged out.";
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Incorrect username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FKOM Parking System</title>
<link rel="stylesheet" href="style3.css">
<style>
    /* Messages */
    .message {
        text-align: center;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        width: 90%;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    .success { background-color: #d4edda; color: #155724; }
    .error { background-color: #f8d7da; color: #721c24; }

    /* Form styling */
    .login-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
    }
    .login-form label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
        text-align: left;
        width: 300px;
    }
    .login-form input, .login-form button {
        width: 300px;
        padding: 10px;
        margin: 5px 0;
    }
    .login-form button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }
    .login-form button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>

<h2 style="text-align:center;">FKOM Parking Management System</h2>

<!-- LOGOUT MESSAGE -->
<?php if ($logout_message != "") { ?>
    <p id="logout-message" class="message success"><?= $logout_message ?></p>
<?php } ?>

<!-- ERROR MESSAGE -->
<?php if ($error != "") { ?>
    <p class="message error"><?= $error ?></p>
<?php } ?>

<!-- LOGIN FORM -->
<div class="login-form">
    <form method="POST" action="login.php">
        
        <label for="email">Username:</label>
        <input type="text" id="email" name="email" placeholder="Enter Email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>
        
        <button type="submit" name="login">Login</button>
    </form>
</div>

<footer style="text-align:center; margin-top:50px;">
    <p class="bottom">&copy; 2025 FK. All rights reserved.</p>
</footer>

<!-- Auto hide logout message after 3 seconds -->
<script>
    setTimeout(function() {
        var msg = document.getElementById('logout-message');
        if (msg) msg.style.display = 'none';
    }, 3000);
</script>

</body>
</html>
