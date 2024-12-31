<?php
session_start();
include('connectDB.php');

// Fetch Total Sales and Revenue
$total_sales_query = "SELECT COUNT(*) as total_sales, SUM(total_price) as total_revenue FROM orders WHERE status = 'Complete'";
$total_sales_result = $connect->query($total_sales_query);
$sales_data = $total_sales_result->fetch_assoc();

// Fetch Most and Least Sold Items
$most_sold_query = "SELECT mi.name, SUM(oi.quantity) as total_quantity 
                    FROM orderitems oi 
                    JOIN menuitems mi ON oi.menu_item_id = mi.id 
                    GROUP BY oi.menu_item_id 
                    ORDER BY total_quantity DESC 
                    LIMIT 1";
$least_sold_query = "SELECT mi.name, SUM(oi.quantity) as total_quantity 
                     FROM orderitems oi 
                     JOIN menuitems mi ON oi.menu_item_id = mi.id 
                     GROUP BY oi.menu_item_id 
                     ORDER BY total_quantity ASC 
                     LIMIT 1";
$most_sold = $connect->query($most_sold_query)->fetch_assoc();
$least_sold = $connect->query($least_sold_query)->fetch_assoc();

// Fetch Stats Per Item
$item_stats_query = "SELECT mi.name, SUM(oi.quantity) as total_sold, mi.price, 
                     (SUM(oi.quantity) * mi.price) as total_revenue 
                     FROM orderitems oi 
                     JOIN menuitems mi ON oi.menu_item_id = mi.id 
                     GROUP BY oi.menu_item_id 
                     ORDER BY total_sold DESC";
$item_stats = $connect->query($item_stats_query);

// Fetch Stats Per Category
$category_stats_query = "SELECT mi.category, SUM(oi.quantity) as total_sold, 
                         SUM(oi.quantity * mi.price) as total_revenue 
                         FROM orderitems oi 
                         JOIN menuitems mi ON oi.menu_item_id = mi.id 
                         GROUP BY mi.category 
                         ORDER BY total_sold DESC";
$category_stats = $connect->query($category_stats_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
        <li class="right"><a href="edit_menu.php" >Edit Menu</a></li>
        <li class="right"><a href="reports.php" class="active">Reports</a></li>
    </div>
    <div class="right">
        <li><a href="logout.php">Logout</a></li>
    </div>
</ul>
    <h1>Sales and Revenue Reports</h1>
    <div class="menu-container">
        <h2>Total Sales and Revenue</h2>
        <p>Total Sales: <strong><?php echo $sales_data['total_sales']; ?></strong></p>
        <p>Total Revenue: <strong>KSH <?php echo number_format($sales_data['total_revenue'], 2); ?></strong></p>
    </div>

    <div class="menu-container">
        <h2>Most and Least Sold Items</h2>
        <p>Most Sold Item: <strong><?php echo $most_sold['name']; ?> (<?php echo $most_sold['total_quantity']; ?> sold)</strong></p>
        <p>Least Sold Item: <strong><?php echo $least_sold['name']; ?> (<?php echo $least_sold['total_quantity']; ?> sold)</strong></p>
    </div>

    <div class="menu-container">
        <h2>Statistics Per Item</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Total Sold</th>
                    <th>Price (KSH)</th>
                    <th>Total Revenue (KSH)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $item_stats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['total_sold']; ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo number_format($row['total_revenue'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="menu-container">
        <h2>Statistics Per Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Sold</th>
                    <th>Total Revenue (KSH)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $category_stats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['total_sold']; ?></td>
                    <td><?php echo number_format($row['total_revenue'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
