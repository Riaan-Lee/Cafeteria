<?php
session_start();
include('connectDB.php');

// Fetch Completed Orders
$query = "SELECT o.id AS order_id, o.total_price, o.created_at, u.name AS customer_name
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.status = 'Completed'";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Orders</title>
    <link rel="stylesheet" href="../Css/pending.css">
</head>
<body>
    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li class="left"><a href="staff_dashboard.php">Dashboard</a></li>
            <li class="left"><a href="pending_orders.php">Pending Orders</a></li>
            <li class="left"><a href="pending_pickup.php">Pending Pickup</a></li>
            <li class="left"><a href="complete_orders.php" class="active">Complete Orders</a></li>
            <li class="right"><a href="edit_menu.php">Edit Menu</a></li>
            <li class="right"><a href="reports.php">Reports</a></li>
        </div>
        <div class="right">
            <li><a href="logout.php">Logout</a></li>
        </div>
    </ul>

    <!-- Page Content -->
    <div class="gallerycontainer">
        <h1>Completed Orders</h1>
        <hr>
        <div class="gallery">
            <?php while ($order = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <h3>Order #<?php echo $order['order_id']; ?></h3>
                    <p><strong>Customer:</strong> <?php echo $order['customer_name']; ?></p>
                    <p><strong>Total Price:</strong> KSH <?php echo number_format($order['total_price'], 2); ?></p>
                    <p><strong>Completed At:</strong> <?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?></p>
                    <hr>
                    <h4>Items:</h4>
                    <ul>
                        <?php
                        // Fetch order items and prices from the menuitems table
                        $items_query = "
                            SELECT m.name AS item_name, oi.quantity, m.price AS item_price, 
                            (oi.quantity * m.price) AS total_item_price
                            FROM orderitems oi
                            JOIN menuitems m ON oi.menu_item_id = m.id
                            WHERE oi.order_id = ?
                        ";
                        $stmt = $connect->prepare($items_query);
                        $stmt->bind_param('i', $order['order_id']);
                        $stmt->execute();
                        $items_result = $stmt->get_result();

                        // Loop through items and display them
                        while ($item = $items_result->fetch_assoc()): ?>
                            <li>
                                <?php echo $item['item_name']; ?> x<?php echo $item['quantity']; ?> 
                                - KSH <?php echo number_format($item['total_item_price'], 2); ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
