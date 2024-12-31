<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Fetch system summary data
// Total Users
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $connect->query($user_query);
$user_count = $user_result->fetch_assoc()['total_users'];

// Total Orders
$order_query = "SELECT COUNT(*) AS total_orders FROM orders";
$order_result = $connect->query($order_query);
$order_count = $order_result->fetch_assoc()['total_orders'];

// Completed Orders
$completed_order_query = "SELECT COUNT(*) AS total_completed_orders FROM orders WHERE status = 'Complete'";
$completed_order_result = $connect->query($completed_order_query);
$completed_order_count = $completed_order_result->fetch_assoc()['total_completed_orders'];

// Total Revenue
$revenue_query = "SELECT SUM(total_price) AS total_revenue FROM orders WHERE status = 'Complete'";
$revenue_result = $connect->query($revenue_query);
$total_revenue = $revenue_result->fetch_assoc()['total_revenue'];

// Top Selling Item
$top_item_query = "SELECT m.name, SUM(oi.quantity) AS total_sold FROM orderitems oi JOIN menuitems m ON oi.menu_item_id = m.id GROUP BY m.name ORDER BY total_sold DESC LIMIT 1";
$top_item_result = $connect->query($top_item_query);
$top_item = $top_item_result->fetch_assoc();

// Active Staff
$staff_query = "SELECT COUNT(*) AS total_staff FROM users WHERE role = 'staff'";
$staff_result = $connect->query($staff_query);
$staff_count = $staff_result->fetch_assoc()['total_staff'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Summary</title>
    <link rel="stylesheet" href="../Css/admin1.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="admin_summary.php"  class="active">Dashboard</a></li>
        <li class="left"><a href="manage_users.php">Manage Users</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <h1>System Overview</h1>

    <!-- Dashboard Summary Cards -->
    <div class="dashboard-summary">
        <!-- Users Card -->
        <div class="summary-card">
            <h3>Total Users</h3>
            <p><?php echo $user_count; ?></p>
            <button class="expand-btn" onclick="toggleDetails('user-details')">Expand</button>
            <div class="details" id="user-details">
                <p>View user list or manage user accounts.</p>
                <a href="manage_users.php" class="btn">Manage Users</a>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="summary-card">
            <h3>Total Orders</h3>
            <p><?php echo $order_count; ?></p>
            <button class="expand-btn" onclick="toggleDetails('order-details')">Expand</button>
            <div class="details" id="order-details">
                <p>Total orders made by customers.</p>
                <a href="view_all_orders.php" class="btn">View Orders</a>
            </div>
        </div>

        <!-- Completed Orders Card -->
        <div class="summary-card">
            <h3>Completed Orders</h3>
            <p><?php echo $completed_order_count; ?></p>
            <button class="expand-btn" onclick="toggleDetails('completed-orders-details')">Expand</button>
            <div class="details" id="completed-orders-details">
                <p>Total completed orders processed.</p>
                <a href="complete_orders_admin.php" class="btn">View Completed Orders</a>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <p>KSH <?php echo number_format($total_revenue, 2); ?></p>
            <button class="expand-btn" onclick="toggleDetails('revenue-details')">Expand</button>
            <div class="details" id="revenue-details">
                <p>Total revenue generated from completed orders.</p>
            </div>
        </div>

        <!-- Top Selling Item -->
        <div class="summary-card">
            <h3>Top Selling Item</h3>
            <p><?php echo $top_item['name']; ?> (<?php echo $top_item['total_sold']; ?> sold)</p>
            <button class="expand-btn" onclick="toggleDetails('top-item-details')">Expand</button>
            <div class="details" id="top-item-details">
                <p>Details of the most popular menu item.</p>
                <a href="menu.php" class="btn">View Menu</a>
            </div>
        </div>

        <!-- Active Staff Card -->
        <div class="summary-card">
            <h3>Active Staff</h3>
            <p><?php echo $staff_count; ?></p>
            <button class="expand-btn" onclick="toggleDetails('staff-details')">Expand</button>
            <div class="details" id="staff-details">
                <p>Total active staff members in the system.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for expanding details -->
    <script src="../Js/admin.js"></script>

</body>
</html>
