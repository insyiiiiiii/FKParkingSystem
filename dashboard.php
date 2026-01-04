<?php
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Only allow Student
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Student') {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard - FKOM Parking System</title>
<link rel="stylesheet" href="dashboard.css">
<style>
    .sidebar a.active { background: #ffd580; color: #000; }
</style>
</head>
<body>

<div class="sidebar">
    <img src="logo-umpsa.png" alt="UMPSA Logo">
    <a href="vehicle.php" class="<?= ($current_page == 'vehicle.php') ? 'active' : '' ?>">Register Vehicle</a>
    <a href="my_vehicle.php" class="<?= ($current_page == 'my_vehicle.php') ? 'active' : '' ?>">My Vehicles</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="main-content">
    <header>Welcome, <?= htmlspecialchars($_SESSION['user']); ?> 👋</header>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Registered Vehicles</h3>
            <p>2 Vehicles</p>
        </div>
        <div class="card">
            <h3>Approval Status</h3>
            <p>1 Approved, 1 Pending</p>
        </div>
        <div class="card">
            <h3>Parking Areas</h3>
            <p>View Available Zones</p>
        </div>
        <div class="card">
            <h3>Notifications</h3>
            <p>1 New Update</p>
        </div>
    </div>
</div>

</body>
</html>
