<?php
session_start();
include('connectDB.php'); // Include database connection

// Fetch data for Pending Orders, Pending Pickup, and Complete Orders
$pending_orders_query = "SELECT * FROM orders WHERE status = 'Pending'";
$pending_pickup_query = "SELECT * FROM orders WHERE status = 'Pickup'";
$complete_orders_query = "SELECT * FROM orders WHERE status = 'Complete'";

$pending_orders = $connect->query($pending_orders_query);
$pending_pickup = $connect->query($pending_pickup_query);
$complete_orders = $connect->query($complete_orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../Css/staff_dashboard.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
    <div class="left">
    <li class="left"><a href="staff_dashboard.php" class="active">Dashboard</a></li>
        <li class="left"><a href="pending_orders.php">Pending Orders</a></li>
        <li class="left"><a href="pending_pickup.php">Pending Pickup</a></li>
        <li class="left"><a href="complete_orders.php">Complete Orders</a></li>
        <li class="right"><a href="edit_menu.php">Edit Menu</a></li>
        <li class="right"><a href="reports.php">Reports</a></li>
    </div>
    <div class="right">
        <li><a href="logout.php">Logout</a></li>
    </div>
</ul>

    <!-- Dashboard Content -->
    <h1>Welcome to the Staff Dashboard</h1>
    <div class="dashboard-container">
        <div class="dashboard-section">
            <h2>Pending Orders</h2>
            <p>Total: <?php echo $pending_orders->num_rows; ?></p>
            <a href="pending_orders.php" class="btn">View Details</a>
        </div>
        <div class="dashboard-section">
            <h2>Pending Pickup</h2>
            <p>Total: <?php echo $pending_pickup->num_rows; ?></p>
            <a href="pending_pickup.php" class="btn">View Details</a>
        </div>
        <div class="dashboard-section">
            <h2>Complete Orders</h2>
            <p>Total: <?php echo $complete_orders->num_rows; ?></p>
            <a href="complete_orders.php" class="btn">View Details</a>
        </div>
    </div>

</body>
</html>
