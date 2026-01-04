<?php
session_start();
include 'config.php';

$error = "";
$logout_message = "";

// Show logout message after logout
if (isset($_GET['logout']) && $_GET['logout'] == 1 && $_SERVER['REQUEST_METHOD'] != 'POST') {
    $logout_message = "You have successfully logged out.";
}

// Handle login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "SELECT * FROM users WHERE username = '$email' AND password = '$password' AND role = '$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role'];

        // Role-based redirection
        switch ($_SESSION['role']) {
            case 'Admin':
                header("Location: admin_dashboard.php");
                break;
            case 'Student':
                header("Location: student_dashboard.php");
                break;
            case 'Safety Staff':
                header("Location: safety_dashboard.php");
                break;
            default:
                $error = "Invalid role assigned to this account.";
        }
        exit();
    } else {
        $error = "Incorrect username, password, or role!";
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

/* Login form */
.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Labels above inputs */
.login-form label {
    display: block;
    width: 300px;
    text-align: left;
    font-weight: bold;
    margin-top: 15px;
    margin-bottom: 5px;
}

/* Inputs, select, and button */
.login-form input,
.login-form select,
.login-form button {
    width: 300px;
    padding: 10px;
    margin: 5px 0;
}

/* Password toggle container */
.password-container {
    position: relative;
}
.password-container .eye {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    user-select: none;
}

/* Button */
.login-form button {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}
.login-form button:hover {
    background-color: #0056b3;
}

/* White box */
.login-box {
    background: white;
    width: 360px;
    padding: 25px;
    margin: 40px auto;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
}
</style>
</head>
<body>

<h2 style="text-align:center; margin-top:40px;">FKOM PARKING MANAGEMENT SYSTEM</h2>

<!-- Logout message -->
<?php if ($logout_message != "") { ?>
    <p id="logout-message" class="message success"><?= $logout_message ?></p>
<?php } ?>

<!-- Error message -->
<?php if ($error != "") { ?>
    <p class="message error"><?= $error ?></p>
<?php } ?>

<!-- Login form -->
<div class="login-box">
    <div class="login-form">
        <form method="POST" action="login.php">

            <label for="email">Username</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <span id="togglePassword" class="eye">👁</span>
            </div>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
                <option value="Admin">Admin</option>
                <option value="Student">Student</option>
                <option value="Safety Staff">Safety Staff</option>
            </select>

            <button type="submit" name="login">Login</button>

        </form>
    </div>
</div>

<footer>
    <p class="bottom">&copy; 2025 FK. All rights reserved.</p>
</footer>

<script>
// Auto hide logout message
setTimeout(function () {
    var msg = document.getElementById('logout-message');
    if (msg) msg.style.display = 'none';
}, 3000);

// Password toggle
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

// Initial state
togglePassword.textContent = '⌣'; // hidden by default

togglePassword.addEventListener('click', () => {
    if (password.type === 'password') {
        password.type = 'text';      // unhide password
        togglePassword.textContent ='👁' ; // closed eye when visible
    } else {
        password.type = 'password';  // hide password
        togglePassword.textContent = '⌣'; // open eye when hidden
    }
});
</script>


</body>
</html>
