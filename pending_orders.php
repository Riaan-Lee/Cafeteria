<?php
session_start();
include('connectDB.php'); // Include database connection

// Check database connection
if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Update Order Status if Mark as Pickup is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_pickup'])) {
    $order_id = $_POST['order_id'];

    // Prepare the query to update the order status to 'Pickup'
    $update_query = "UPDATE orders SET status = 'Pickup' WHERE id = ?";
    $stmt = $connect->prepare($update_query);
    
    if ($stmt === false) {
        // If the preparation fails, show an error message
        echo "Error preparing the query: " . $connect->error;
    }

    // Bind the order_id to the query and execute it
    $stmt->bind_param('i', $order_id);
    
    if ($stmt->execute()) {
        // Check if the status was updated successfully
        if ($stmt->affected_rows > 0) {
            // Order marked as Pickup successfully
            echo "<script>alert('Order marked as Pickup');</script>";

            // Fetch the user_id associated with the order
            $user_query = "SELECT user_id FROM orders WHERE id = ?";
            $user_stmt = $connect->prepare($user_query);
            $user_stmt->bind_param('i', $order_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user = $user_result->fetch_assoc();
            $user_id = $user['user_id'];

            // Insert a notification about the pickup status
            $notification_message = "Your order is ready for pickup.";
            $notification_status = 'Unread'; // Set the notification status to 'Unread'

            // Insert notification into the notifications table
            $notification_query = "INSERT INTO notifications (user_id, order_id, message, status) VALUES (?, ?, ?, ?)";
            $stmt = $connect->prepare($notification_query);
            $stmt->bind_param('iiss', $user_id, $order_id, $notification_message, $notification_status);
            $stmt->execute();
        } else {
            echo "<script>alert('Failed to mark order as Pickup');</script>";
        }
    } else {
        echo "Error executing the query: " . $stmt->error;
    }
}

// Fetch Pending Orders
$query = "SELECT o.id AS order_id, o.total_price, o.created_at, u.name AS customer_name
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.status = 'Pending'";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="../Css/pending.css">
</head>
<body>
    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li><a href="staff_dashboard.php">Dashboard</a></li>
            <li><a href="pending_orders.php" class="active">Pending Orders</a></li>
            <li><a href="pending_pickup.php">Pending Pickup</a></li>
            <li><a href="complete_orders.php">Complete Orders</a></li>
            <li><a href="edit_menu.php">Edit Menu</a></li>
            <li><a href="reports.php">Reports</a></li>
        </div>
        <div class="right">
            <li><a href="logout.php">Logout</a></li>
        </div>
    </ul>

    <!-- Page Content -->
    <div class="gallerycontainer">
        <h1>Pending Orders</h1>
        <hr>
        <div class="gallery">
            <?php while ($order = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <h3>Order #<?php echo $order['order_id']; ?></h3>
                    <p><strong>Customer:</strong> <?php echo $order['customer_name']; ?></p>
                    <p><strong>Total Price:</strong> KSH <?php echo number_format($order['total_price'], 2); ?></p>
                    <p><strong>Created At:</strong> <?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?></p>
                    <hr>
                    <h4>Items:</h4>
                    <ul>
                        <?php
                        $items_query = "
                            SELECT 
                                m.name AS menu_item, 
                                oi.quantity, 
                                m.price AS item_price, 
                                (oi.quantity * m.price) AS total_item_price
                            FROM orderitems oi
                            JOIN menuitems m ON oi.menu_item_id = m.id
                            WHERE oi.order_id = ?";
                        $stmt = $connect->prepare($items_query);
                        $stmt->bind_param('i', $order['order_id']);
                        $stmt->execute();
                        $items_result = $stmt->get_result();
                        while ($item = $items_result->fetch_assoc()): ?>
                            <li>
                                <?php echo $item['menu_item']; ?> x<?php echo $item['quantity']; ?> - KSH <?php echo number_format($item['total_item_price'], 2); ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                    <!-- Mark as Pickup Button -->
                    <form method="POST" action="pending_orders.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="mark_pickup" class="btn">Mark as Pickup</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
