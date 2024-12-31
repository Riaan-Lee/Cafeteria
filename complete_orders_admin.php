<?php
session_start();
include('connectDB.php');

// Fetch Completed Orders
$query = "SELECT o.id AS order_id, o.total_price, o.created_at, u.name AS customer_name
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.status = 'Complete'";
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

<ul id="navigation">
    <div class="left">
        <li><a href="admin_summary.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
    </div>
    <div class="right">
        <li><a href="logout.php">Logout</a></li>
    </div>
</ul>

    <!-- Page Content -->
    <h1>Completed Orders</h1>
    <div class="order-container">
        <h2>Orders Completed</h2>
        <?php while ($order = $result->fetch_assoc()): ?>
            <div class="order">
                <div class="order-header">
                    <h3>Order #<?php echo $order['order_id']; ?> - <?php echo $order['customer_name']; ?></h3>
                    <p>KSH <?php echo number_format($order['total_price'], 2); ?></p>
                </div>
                <div class="order-items">
                    <?php
                    $items_query = "SELECT m.name AS item_name, oi.quantity, oi.price
                                    FROM order_items oi
                                    JOIN menuitems m ON oi.menuitem_id = m.id
                                    WHERE oi.order_id = ?";
                    $stmt = $connect->prepare($items_query);
                    $stmt->bind_param('i', $order['order_id']);
                    $stmt->execute();
                    $items_result = $stmt->get_result();
                    while ($item = $items_result->fetch_assoc()): ?>
                        <div class="order-item">
                            <span><?php echo $item['item_name']; ?> x<?php echo $item['quantity']; ?></span>
                            <span>KSH <?php echo number_format($item['price'], 2); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
