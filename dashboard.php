<?php
session_start();

// Prevent back button caching
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Protect page: redirect to login if session not set
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FKOM Parking System</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Highlight active sidebar link */
        .sidebar a.active {
            background: #ffd580;
            color: #000;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="logo-umpsa.png" alt="UMPSA Logo">

        <a href="vehicle.php" class="<?= ($current_page == 'vehicle.php') ? 'active' : '' ?>">Register Vehicle</a>
        <a href="booking.php" class="<?= ($current_page == 'booking.php') ? 'active' : '' ?>">Parking Booking</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</header>

        <div class="dashboard-cards">
            <div class="card">
                <h3>Registered Vehicles</h3>
                <p>12 Vehicles</p>
            </div>
            <div class="card">
                <h3>Active Bookings</h3>
                <p>5 Bookings</p>
            </div>
            <div class="card">
                <h3>Available Slots</h3>
                <p>8 Slots</p>
            </div>
            <div class="card">
                <h3>System Notifications</h3>
                <p>2 New</p>
            </div>
        </div>
    </div>

</body>
</html>
