<?php
session_start();
include('connectDB.php');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "message" => "Please log in to add items to the cart."]));
}

$user_id = $_SESSION['user_id'];
$menu_item_id = $_POST['menuitem_id'];

// Check if the item is already in the cart
$query = "SELECT * FROM cart WHERE user_id = ? AND menuitem_id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('ii', $user_id, $menu_item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Item is already in the cart."]);
    exit;
}

// Add the item to the cart
$query = "INSERT INTO cart (user_id, menuitem_id, quantity) VALUES (?, ?, 1)";
$stmt = $connect->prepare($query);
$stmt->bind_param('ii', $user_id, $menu_item_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Item added to cart."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add item to cart."]);
}
?>
