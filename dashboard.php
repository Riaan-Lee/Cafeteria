<?php
// Start the session to maintain user info
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

include('connectDB.php'); // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../Css/dashboard.css">
</head>
<body>

    <!-- Navigation Bar for Dashboard -->
    <ul id="navigation">
        <li class="left"><a href="dashboard.php" class="active">Dashboard</a></li>
        <li class="left"><a href="order-history.php">Order History</a></li>
        <li class="left"><a href="menu.php">Menu</a></li>
        <li class="left"><a href="notifications.php">Notifications</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <!-- Customer Dashboard Section -->
    <section id="dashboard">
        <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2> <!-- Displaying the user's name -->
        <p>Select an option to continue:</p>

        <!-- Order History Preview -->
        <div class="dashboardItem">
            <a href="order-history.php">
                <img src="https://cdn-icons-png.flaticon.com/512/5220/5220625.png" alt="Order History">
                <h3>Order History</h3>
            </a>
            <p>View your previous orders and track past purchases.</p>
        </div>

        <!-- Notifications Preview -->
        <div class="dashboardItem">
            <a href="notifications.php">
                <img src="https://icons-for-free.com/iff/png/512/notification+one+notification+tiwtter+icon-1320195954673938461.png" alt="Notifications">
                <h3>Notifications</h3>
            </a>
            <p>Check for updates, messages, and alerts.</p>
        </div>

        <!-- Menu Preview -->
        <div class="dashboardItem">
            <a href="menu.php">
                <img src="https://cdn-icons-png.freepik.com/256/16216/16216423.png?semt=ais_hybrid" alt="Menu">
                <h3>Menu</h3>
            </a>
            <p>Browse our menu and place a new order.</p>
        </div>
    </section>

</body>
</html>
