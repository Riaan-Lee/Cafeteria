<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Ensure user_id exists in session
if (!isset($_SESSION['id'])) {
    // Redirect to login page if no user is logged in
    header("Location: login.php");
    exit;
}

// Fetch notifications for the logged-in user (only Unread ones)
$user_id = $_SESSION['id']; // Get the logged-in user's ID

// Query to fetch only unread notifications
$query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'Unread' ORDER BY created_at DESC";
$stmt = $connect->prepare($query);

// Check if the query is prepared successfully
if ($stmt === false) {
    die("Error preparing the query: " . $connect->error);
}

// Bind the parameter and execute the query
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Delete notifications (mark them as read) when 'Mark All as Read' is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    // Update the status of all notifications for the logged-in user
    $update_query = "UPDATE notifications SET status = 'Read' WHERE user_id = ? AND status = 'Unread'";
    $update_stmt = $connect->prepare($update_query);
    
    if ($update_stmt === false) {
        die("Error preparing the update query: " . $connect->error);
    }

    $update_stmt->bind_param('i', $user_id);
    $update_stmt->execute();

    // Redirect to notifications page after clearing
    header("Location: notifications.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../Css/notifications.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <li class="left"><a href="dashboard.php">Dashboard</a></li>
        <li class="left"><a href="order-history.php">Order History</a></li>
        <li class="left"><a href="menu.php">Menu</a></li>
        <li class="left"><a href="notifications.php" class="active">Notifications</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>

    <h1>Notifications</h1>

    <div class="notifications-container">
        <!-- Mark all as Read button -->
        <form method="POST" action="notifications.php">
            <button type="submit" name="mark_read" class="btn">Clear All Notifications</button>
        </form>

        <!-- Display notifications -->
        <?php 
        // Check if there are unread notifications
        if ($result->num_rows > 0) {
            while ($notification = $result->fetch_assoc()): ?>
                <div class="notification <?php echo $notification['status']; ?>">
                    <p><?php echo $notification['message']; ?></p>
                    <span class="timestamp"><?php echo date('F j, Y, g:i A', strtotime($notification['created_at'])); ?></span>
                </div>
            <?php endwhile;
        } else {
            echo "<p>No unread notifications available.</p>";
        }
        ?>
    </div>

</body>
</html>
