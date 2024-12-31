<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Fetch all orders
$query = "
    SELECT 
        o.id AS order_id, 
        u.name AS customer_name, 
        o.status, 
        o.total_price, 
        o.created_at, 
        GROUP_CONCAT(CONCAT(oi.quantity, ' x ', m.name) SEPARATOR '<br>') AS order_items
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN orderitems oi ON o.id = oi.order_id
    JOIN menuitems m ON oi.menu_item_id = m.id
    GROUP BY o.id
    ORDER BY o.created_at DESC
";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
    <link rel="stylesheet" href="../Css/pending.css">
</head>
<body>

<ul id="navigation">
    <div class="left">
        <li><a href="admin_summary.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
    </div>
    <div class="right">
        <li><a href="logout.php">Logout</a></li>
    </div>
</ul>


    <!-- Page Title -->
    <h1>All Orders</h1>

    <!-- Orders Table -->
    <div class="menu-container">
        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo $order['order_items']; ?></td>
                        <td>KSH <?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
