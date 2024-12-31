<?php
session_start();
include('connectDB.php'); // Include database connection

// Debugging: Check if the form is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_complete'])) {
    $order_id = $_POST['order_id'];

    // Debug: Output the order_id to check if it is being passed correctly
    echo "Order ID: " . $order_id;

    // Prepare the query to update the status
    $update_query = "UPDATE orders SET status = 'Completed' WHERE id = ?";
    $stmt = $connect->prepare($update_query);

    if ($stmt === false) {
        echo "Error preparing the statement: " . $connect->error;
    }

    // Bind the parameter and execute the query
    $stmt->bind_param('i', $order_id);
    if ($stmt->execute()) {
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Order marked as complete');</script>";
        } else {
            // Handle if no rows were updated (e.g., the status is already 'Complete')
            echo "<script>alert('Failed to update order status. The order may already be completed.');</script>";
        }
    } else {
        // Handle any errors during execution
        echo "Error executing query: " . $stmt->error;
    }
}

// Fetch Pending Pickup Orders
$query = "SELECT o.id AS order_id, o.total_price, o.created_at, u.name AS customer_name
          FROM orders o
          JOIN users u ON o.user_id = u.id
          WHERE o.status = 'Pickup'";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Pickup Orders</title>
    <link rel="stylesheet" href="../Css/pending.css">
</head>
<body>
    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li class="left"><a href="staff_dashboard.php">Dashboard</a></li>
            <li class="left"><a href="pending_orders.php">Pending Orders</a></li>
            <li class="left"><a href="pending_pickup.php" class="active">Pending Pickup</a></li>
            <li class="left"><a href="complete_orders.php">Complete Orders</a></li>
            <li class="right"><a href="edit_menu.php">Edit Menu</a></li>
            <li class="right"><a href="reports.php">Reports</a></li>
        </div>
        <div class="right">
            <li><a href="logout.php">Logout</a></li>
        </div>
    </ul>

    <!-- Page Content -->
    <div class="gallerycontainer">
        <h1>Pending Pickup Orders</h1>
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
                        // Fetch the order items for this order
                        $items_query = "
                            SELECT 
                                m.name AS item_name, 
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
                                <?php echo $item['item_name']; ?> x<?php echo $item['quantity']; ?> - KSH <?php echo number_format($item['total_item_price'], 2); ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                    <!-- Mark as Complete Button -->
                    <form method="POST" action="pending_pickup.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="mark_complete" class="btn">Mark as Complete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
