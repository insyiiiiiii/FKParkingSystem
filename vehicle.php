<?php
session_start();
include 'config.php';

// Protect page: redirect to login if session not set
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get current page for active sidebar highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Get current user ID
$user_email = $_SESSION['user'];
$stmt = $conn->prepare("SELECT user_ID FROM users WHERE username = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_ID'];

// Handle vehicle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plate_number = $_POST['plate_number'];
    $vehicle_type = $_POST['vehicle_type'];

    $stmt = $conn->prepare("INSERT INTO vehicles (user_id, plate_number, vehicle_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $plate_number, $vehicle_type);

    if ($stmt->execute()) {
        $success = "Vehicle registered successfully!";
    } else {
        $error = "Failed to register vehicle.";
    }
}

// Get user’s vehicles
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$vehicles_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Vehicle</title>
<link rel="stylesheet" href="dashboard.css">
<style>
/* Highlight active sidebar link */
.sidebar a.active { background: #ffd580; color: #000; }

form input, form select, form button {
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
    margin-top: 20px;
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
    <a href="vehicle.php" class="<?= ($current_page == 'vehicle.php') ? 'active' : '' ?>">Register Vehicle</a>
    <a href="booking.php" class="<?= ($current_page == 'booking.php') ? 'active' : '' ?>">Parking Booking</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <header>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</header>

    <div class="main">
        <h2>Register Vehicle</h2>

        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <label>Plate Number</label>
            <input type="text" name="plate_number" required>

            <label>Vehicle Type</label>
            <select name="vehicle_type" required>
                <option value="">Select Type</option>
                <option value="Car">Car</option>
                <option value="SUV">SUV</option>
                <option value="Motorcycle">Motorcycle</option>
            </select>

            <button type="submit">Register Vehicle</button>
        </form>

        <h3>Your Registered Vehicles</h3>
        <table>
            <tr>
                <th>Plate Number</th>
                <th>Type</th>
            </tr>
            <?php while ($row = $vehicles_result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
