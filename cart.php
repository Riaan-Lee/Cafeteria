<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Ensure cart session exists and initialize it if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize an empty cart if it doesn't exist
}

// Fetch cart items for the logged-in user if the cart exists
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];  // Get cart items
} else {
    $cart_items = [];
}

// Debug: Check session cart contents
// var_dump($_SESSION['cart']); // Uncomment this to check the cart contents in the session

// Handle item removal from the cart
if (isset($_GET['remove_item_id'])) {
    $remove_item_id = $_GET['remove_item_id'];
    unset($_SESSION['cart'][$remove_item_id]);  // Remove item from cart
    header("Location: cart.php");  // Refresh the page after removal
    exit;
}

// Handle quantity update (increase or decrease)
if (isset($_POST['update_cart'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    if ($new_quantity > 0) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;  // Update quantity in cart
    } else {
        unset($_SESSION['cart'][$item_id]);  // Remove item from cart if quantity is 0
    }
    header("Location: cart.php");  // Refresh the page after update
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../Css/cart.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="dashboard.php">Dashboard</a></li>
        <li class="left"><a href="order-history.php">Order History</a></li>
        <li class="left"><a href="menu.php">Menu</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <h1>Your Shopping Cart</h1>
    <div class="cart-container">
        <?php 
        if (!empty($cart_items)) {
            foreach ($cart_items as $item_id => $cart_item): 
                // Fetch the menu item details based on item_id
                $query = "SELECT * FROM menuitems WHERE id = ?";
                $stmt = $connect->prepare($query);
                $stmt->bind_param('i', $item_id);
                $stmt->execute();
                $item_result = $stmt->get_result();
                $item = $item_result->fetch_assoc();
                ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="item-image">
                    <div class="item-details">
                        <h2><?php echo $item['name']; ?></h2>
                        <p class="price">KSH <?php echo number_format($item['price'], 2); ?></p>
                        <form method="POST" action="cart.php">
                            <div class="quantity-controls">
                                <button class="decrease-btn" type="submit" name="update_cart" value="decrease">-</button>
                                <input type="number" name="quantity" value="<?php echo $cart_item['quantity']; ?>" min="1" class="quantity-input">
                                <button class="increase-btn" type="submit" name="update_cart" value="increase">+</button>
                            </div>
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                        </form>
                        <p>Total: KSH <?php echo number_format($cart_item['quantity'] * $item['price'], 2); ?></p>
                        <a href="cart.php?remove_item_id=<?php echo $item_id; ?>" class="remove-btn">Remove</a>
                    </div>
                </div>
                <?php 
                $total_price += $cart_item['quantity'] * $item['price']; 
            endforeach; 
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>

    <!-- Cart Summary -->
    <div class="cart-summary">
        <h2>Order Summary</h2>
        <p>Total: <span class="total-price">KSH <?php echo number_format($total_price, 2); ?></span></p>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>

</body>
</html>
