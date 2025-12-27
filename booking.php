<?php
session_start();
include 'config.php';

// Protect page: redirect to login if session not set
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user'];
$stmt = $conn->prepare("SELECT user_ID FROM users WHERE username = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_ID']; // fixed column name

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $details = $_POST['details'];

    $stmt = $conn->prepare(
        "INSERT INTO bookings (user_id, booking_date, booking_time, details)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("isss", $user_id, $date, $time, $details);

    if ($stmt->execute()) {
        $success = "Booking successful!";
    } else {
        $error = "Booking failed!";
    }
}

$bookings = $conn->prepare(
    "SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC"
);
$bookings->bind_param("i", $user_id);
$bookings->execute();
$bookings_result = $bookings->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Parking Booking</title>
<link rel="stylesheet" href="dashboard.css">

<style>
/* Highlight active page in sidebar */
.sidebar a.active {
    background: #ffd580;
    color: #000;
}

form input, form textarea, form button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
}

form button {
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="logo-umpsa.png" alt="UMPSA Logo">
    <a href="registration_vehicle.php">Register Vehicle</a>
    <a href="booking.php" class="active">Parking Booking</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <header>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</header>

    <div class="main">
        <h2>Parking Booking</h2>

        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <label>Date</label>
            <input type="date" name="date" required>

            <label>Time</label>
            <input type="time" name="time" required>

            <label>Details</label>
            <textarea name="details" rows="3"></textarea>

            <button type="submit">Book Parking</button>
        </form>

        <h3>Your Bookings</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Details</th>
            </tr>

            <?php while ($row = $bookings_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['booking_date']; ?></td>
                <td><?= $row['booking_time']; ?></td>
                <td><?= $row['details']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
