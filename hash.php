<?php
include('connectDB.php');

$query = "SELECT id, password FROM users";
$result = $connect->query($query);

while ($user = $result->fetch_assoc()) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $update_query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $connect->prepare($update_query);
    $stmt->bind_param('si', $hashed_password, $user['id']);
    $stmt->execute();
}

echo "Passwords have been hashed.";
?>
