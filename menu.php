<?php
session_start();
include('connectDB.php'); // Include the database connection file

// Ensure cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize the cart if it doesn't exist
}

// Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];  // Get the item ID from the form
    $quantity = $_POST['quantity'];  // Get the quantity from the form

    // Fetch item details from the database
    $query = "SELECT * FROM menuitems WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        // Check if the item is already in the cart
        if (isset($_SESSION['cart'][$item_id])) {
            // If item is already in cart, just update the quantity
            $_SESSION['cart'][$item_id]['quantity'] += $quantity;
        } else {
            // If item is not in cart, add it
            $_SESSION['cart'][$item_id] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $quantity,
                'image_url' => $item['image_url']
            ];
        }
    }
}

// Fetch menu items from the database
$query = "SELECT * FROM menuitems";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../Css/menu.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="order-history.php">Order History</a></li>
            <li><a href="menu.php" class="active">Menu</a></li>
            <li><a href="notifications.php">Notifications</a></li>
        </div>
        <div class="right">
            <li><a href="logout.php">Logout</a></li>
        </div>
    </ul>

    <!-- Cart Button -->
    <div id="cart-btn">
        <a href="cart.php" class="cart-link">
            <div class="cart-icon-container">
                <img src="https://cdn-icons-png.flaticon.com/512/34/34568.png" alt="Cart Icon">
                <p>View Cart</p>
                <span id="cart-count">
                    <?php
                    // Display the number of items in the cart
                    echo count($_SESSION['cart'] ?? []);
                    ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Menu Gallery Section -->
    <div class="menu-gallery">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="menu-item">
                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                <h2><?php echo $item['name']; ?></h2>
                <p><?php echo $item['description']; ?></p>
                <p class="price">KSH <?php echo number_format($item['price'], 2); ?></p>
                <form method="POST" action="menu.php">
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                    <input type="hidden" name="item_price" value="<?php echo $item['price']; ?>">
                    <input type="number" name="quantity" value="1" min="1">
                    <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast hidden"></div>

</body>
</html>
