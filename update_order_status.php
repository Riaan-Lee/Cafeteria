<?php
session_start();
include('connectDB.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $message = '';

    // Fetch the customer linked to the order
    $query = "SELECT user_id FROM orders WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $user_id = $order['user_id'];

        // Update order status
        $update_query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($update_query);
        $stmt->bind_param('si', $status, $order_id);

        if ($stmt->execute()) {
            // Create notification
            if ($status === 'Pickup') {
                $message = "Your order #$order_id is ready for pickup.";
            } elseif ($status === 'Pending') {
                $message = "Order #$order_id is no longer ready for pickup. Please wait for further updates.";
            }

            $notification_query = "INSERT INTO notifications (user_id, order_id, message) VALUES (?, ?, ?)";
            $stmt = $connect->prepare($notification_query);
            $stmt->bind_param('iis', $user_id, $order_id, $message);
            $stmt->execute();
        }
    }

    // Redirect back to the pending orders page
    header("Location: pending_orders.php");
    exit;
}
?>
