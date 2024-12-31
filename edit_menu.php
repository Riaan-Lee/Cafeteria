<?php
session_start();
include('connectDB.php');

// Fetch all menu items
$query = "SELECT id, name, description, price, category, availability, image_url, is_pick_of_the_day FROM menuitems";
$menu_items = $connect->query($query);

// Handle Create or Update Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new menu item
            $stmt = $connect->prepare("INSERT INTO menuitems (name, description, price, category, availability, image_url, is_pick_of_the_day) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                'ssdsisi',
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['category'],
                $_POST['availability'],
                $_POST['image_url'],
                $_POST['is_pick_of_the_day']
            );
            $stmt->execute();
        } elseif ($_POST['action'] === 'update') {
            // Update existing menu item
            $stmt = $connect->prepare("UPDATE menuitems SET name = ?, description = ?, price = ?, category = ?, availability = ?, image_url = ?, is_pick_of_the_day = ? WHERE id = ?");
            $stmt->bind_param(
                'ssdsisii',
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['category'],
                $_POST['availability'],
                $_POST['image_url'],
                $_POST['is_pick_of_the_day'],
                $_POST['id']
            );
            $stmt->execute();
        }
    }
    header("Location: edit_menu.php");
    exit();
}

// Handle Delete Operations
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $stmt = $connect->prepare("DELETE FROM menuitems WHERE id = ?");
    $stmt->bind_param('i', $_GET['delete_id']);
    $stmt->execute();
    header("Location: edit_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="../Css/edit_menu.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
    <div class="left">
    <li class="left"><a href="staff_dashboard.php" >Dashboard</a></li>
        <li class="left"><a href="pending_orders.php">Pending Orders</a></li>
        <li class="left" ><a href="pending_pickup.php" >Pending Pickup</a></li>
        <li class="left"><a href="complete_orders.php" >Complete Orders</a></li>
        <li class="right"><a href="edit_menu.php" class="active">Edit Menu</a></li>
        <li class="right"><a href="reports.php">Reports</a></li>
    </div>
    <div class="right">
        <li><a href="logout.php">Logout</a></li>
    </div>
</ul>

    <!-- Page Content -->
    <h1>Edit Menu</h1>

    <!-- Add New Menu Item -->
    <div class="menu-form">
        <h2>Add New Item</h2>
        <form method="POST" action="edit_menu.php">
            <input type="hidden" name="action" value="add">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Description</label>
            <textarea name="description" required></textarea>
            <label>Price (KSH)</label>
            <input type="number" name="price" step="0.01" required>
            <label>Category</label>
            <input type="text" name="category" required>
            <label>Availability</label>
            <select name="availability">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>
            <label>Image URL</label>
            <input type="url" name="image_url" required>
            <label>Pick of the Day</label>
            <select name="is_pick_of_the_day">
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
            </select>
            <button type="submit" class="btn">Add Item</button>
        </form>
    </div>

    <!-- Existing Menu Items -->
    <div class="menu-container">
        <h2>Existing Menu Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Availability</th>
                    <th>Image URL</th>
                    <th>Pick of the Day</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $menu_items->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="edit_menu.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <td><input type="text" name="name" value="<?php echo $item['name']; ?>" required></td>
                            <td><textarea name="description" required><?php echo $item['description']; ?></textarea></td>
                            <td><input type="number" name="price" step="0.01" value="<?php echo $item['price']; ?>" required></td>
                            <td><input type="text" name="category" value="<?php echo $item['category']; ?>" required></td>
                            <td>
                                <select name="availability">
                                    <option value="1" <?php echo $item['availability'] ? 'selected' : ''; ?>>Available</option>
                                    <option value="0" <?php echo !$item['availability'] ? 'selected' : ''; ?>>Not Available</option>
                                </select>
                            </td>
                            <td><input type="url" name="image_url" value="<?php echo $item['image_url']; ?>" required></td>
                            <td>
                                <select name="is_pick_of_the_day">
                                    <option value="1" <?php echo $item['is_pick_of_the_day'] ? 'selected' : ''; ?>>Yes</option>
                                    <option value="0" <?php echo !$item['is_pick_of_the_day'] ? 'selected' : ''; ?>>No</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn">Update</button>
                                <a href="edit_menu.php?delete_id=<?php echo $item['id']; ?>" class="btn delete-btn">Delete</a>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
