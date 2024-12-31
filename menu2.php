<?php
session_start();
include('connectDB.php');

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
    <script src="../Js/menu.js" defer></script>
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="home.php">Home</a></li>
        <li class="left"><a href="menu2.php" class="active">Menu</a></li>
        <li class="right"><a href="login.php" >Login</a></li>

    </ul>

    <!-- Cart Button -->
    <div id="cart-btn">
        <a href="cart.php" class="cart-link">
            <div class="cart-icon-container">
                <img src="https://cdn-icons-png.flaticon.com/512/34/34568.png" alt="Cart Icon">
                <p>View Cart</p>
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
                <button class="cart-btn" data-id="<?php echo $item['id']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast hidden"></div>

</body>
</html>
