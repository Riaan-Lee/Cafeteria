<?php
session_start();
include('connectDB.php'); // Include your database connection file

// Initialize message variable for error or success
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Get the username from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate inputs
    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirmPassword)) {
        if ($password === $confirmPassword) {
            // Check if the email is already registered
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "An account with that email already exists.";
            } else {
                // Hash the password before storing
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert the new user into the database
                $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')";
                $stmt = $connect->prepare($query);
                $stmt->bind_param('sss', $username, $email, $hashedPassword);
                $stmt->execute();

                // Assuming registration is successful, log the user in
                $_SESSION['email'] = $email;  // Store email in session

                // Redirect to dashboard or other page after successful registration
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $message = "Passwords do not match.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="../Css/login.css"> <!-- Use the same login CSS -->
</head>
<body>

    <div class="login-container">
        <!-- Left Half: Background Image -->
        <div class="left-section">
            <!-- Image handled via CSS background -->
        </div>

        <!-- Right Half: Registration Form -->
        <div class="right-section">
            <h2>Register</h2>

            <!-- Display message if any -->
            <?php if ($message): ?>
                <div class="form-message"><?= $message ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter Username" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

                <button type="submit">Register</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script src="../Js/login.js"></script> <!-- Optional: Link to JS file -->
</body>
</html> 