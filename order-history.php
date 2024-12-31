<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Get the logged-in user ID
$user_id = $_SESSION['id'];

// Get filter date from the form if provided
$filter_date = isset($_POST['filter_date']) ? $_POST['filter_date'] : null;

// Query to fetch orders
$query = "
    SELECT 
        o.id AS order_id,
        o.created_at,
        o.total_price,
        oi.quantity,
        m.name AS menu_item,
        m.price AS item_price,
        DATE(o.created_at) AS order_date
    FROM orders o
    JOIN orderitems oi ON o.id = oi.order_id
    JOIN menuitems m ON oi.menu_item_id = m.id
    WHERE o.user_id = ?
";

if ($filter_date) {
    $query .= " AND DATE(o.created_at) = ?";
}

$query .= " ORDER BY o.created_at DESC";

$stmt = $connect->prepare($query);
if ($filter_date) {
    $stmt->bind_param('is', $user_id, $filter_date);
} else {
    $stmt->bind_param('i', $user_id);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../Css/order_history.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="dashboard.php">Dashboard</a></li>
        <li class="left"><a href="order-history.php" class="active">Order History</a></li>
        <li class="left"><a href="menu.php">Menu</a></li>
        <li class="left"><a href="notifications.php">Notifications</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <h1>Your Order History</h1>

    <!-- Filter by Date -->
    <form method="POST" action="order-history.php" class="filter-form">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" name="filter_date" id="filter_date" value="<?php echo $filter_date; ?>">
        <button type="submit" class="btn">Apply Filter</button>
        <a href="order-history.php" class="btn reset-btn">Reset</a>
    </form>

    <!-- Order History Display -->
    <div class="order-history">
        <?php 
        $current_date = null;
        $order_items = [];
        while ($row = $result->fetch_assoc()): 
            // Grouping items under the same order
            if ($current_date !== $row['order_date']) {
                // Print previous group if necessary
                if (!empty($order_items)) {
                    // Display grouped order items
                    echo "<h2 class='order-date'>" . date('F j, Y', strtotime($current_date)) . "</h2>";
                    foreach ($order_items as $order_item) {
                        echo "<div class='order-item'>";
                        echo "<span>" . $order_item['menu_item'] . " x" . $order_item['quantity'] . "</span>";
                        echo "<span>Item Price: KSH " . number_format($order_item['item_price'], 2) . "</span>";
                        echo "<span>Total: KSH " . number_format($order_item['quantity'] * $order_item['item_price'], 2) . "</span>";
                        echo "</div>";
                    }
                    // Reset items after displaying
                    $order_items = [];
                }

                // Set current date to the one of this order
                $current_date = $row['order_date'];
            }

            // Add item to the order items array
            $order_items[] = [
                'menu_item' => $row['menu_item'],
                'quantity' => $row['quantity'],
                'item_price' => $row['item_price'],
            ];
            
        endwhile;

        // Final display of items for the last group
        if (!empty($order_items)) {
            echo "<h2 class='order-date'>" . date('F j, Y', strtotime($current_date)) . "</h2>";
            foreach ($order_items as $order_item) {
                echo "<div class='order-item'>";
                echo "<span>" . $order_item['menu_item'] . " x" . $order_item['quantity'] . "</span>";
                echo "<span>Item Price: KSH " . number_format($order_item['item_price'], 2) . "</span>";
                echo "<span>Total: KSH " . number_format($order_item['quantity'] * $order_item['item_price'], 2) . "</span>";
                echo "</div>";
            }
        }
        ?>
    </div>

</body>
</html>
