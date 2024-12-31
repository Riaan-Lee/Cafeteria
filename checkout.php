<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Check if cart exists in session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: menu.php"); // Redirect to menu if cart is empty
    exit;
}

// Get the logged-in user's ID from session
$user_id = $_SESSION['id'];

// Calculate total price from cart items
$total_price = 0;
foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    $total_price += $cart_item['quantity'] * $cart_item['price'];
}

// Insert the order into the orders table
$query = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Pending')";
$stmt = $connect->prepare($query);
$stmt->bind_param('id', $user_id, $total_price);
$stmt->execute();

// Get the order ID
$order_id = $stmt->insert_id;

// Insert the order items into the orderitems table
foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    // Ensure you use the correct column name for the menu item in the orderitems table
    $query = "INSERT INTO orderitems (order_id, menu_item_id, quantity, subtotal) VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('iiii', $order_id, $item_id, $cart_item['quantity'], $cart_item['price']);
    $stmt->execute();
}

// Insert a notification about the new order
$notification_message = "Your order has been placed and is currently pending.";
$notification_status = 'Unread'; // Set the status of the notification as 'Unread'

$notification_query = "INSERT INTO notifications (user_id, order_id, message, status) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($notification_query);
$stmt->bind_param('iiss', $user_id, $order_id, $notification_message, $notification_status);
$stmt->execute();

// Clear the cart after the order is placed
$_SESSION['cart'] = [];

// Redirect to order history page
header("Location: order-history.php");
exit;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../Css/checkout.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="order-history.php">Order History</a></li>
            <li><a href="menu.php">Menu</a></li>
        </div>
        <div class="right">
            <li><a href="logout.php">Logout</a></li>
        </div>
    </ul>

    <h1>Checkout</h1>

    <div class="checkout-container">
        <h2>Your Cart</h2>
        <div class="cart-items">
            <?php foreach ($_SESSION['cart'] as $item_id => $cart_item): ?>
                <div class="cart-item">
                    <p><strong><?php echo $cart_item['name']; ?></strong></p>
                    <p>Quantity: <?php echo $cart_item['quantity']; ?></p>
                    <p>Price: KSH <?php echo number_format($cart_item['price'], 2); ?></p>
                    <p>Total: KSH <?php echo number_format($cart_item['quantity'] * $cart_item['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Total Price: KSH <?php echo number_format($total_price, 2); ?></h3>
            <form method="POST" action="checkout.php">
                <button type="submit" class="btn">Place Order</button>
            </form>
        </div>
    </div>

</body>
</html>
