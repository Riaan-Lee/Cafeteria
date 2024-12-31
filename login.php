<?php
session_start();
include('connectDB.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: manage_users.php");
                        exit;
                    case 'staff':
                        header("Location: staff_dashboard.php");
                        exit;
                    case 'customer':
                        header("Location: dashboard.php");
                        exit;
                    default:
                        $message = "Unknown role.";
                }
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No account found with that email.";
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
    <title>Login Page</title>
    <link rel="stylesheet" href="../Css/login.css"> <!-- Relative path to CSS file -->
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Full Page Layout */
body, html {
    height: 100%;
    font-family: 'Helvetica Neue', sans-serif;
}

/* Main Container */
.login-container {
    display: flex;
    height: 100vh;
    width: 100%;
}

/* Left Section: Background Image */
.left-section {
    flex: 1;
    background-image: url("Home page photo.jpg"); /* Updated to relative path */
    background-size: cover;
    background-position: center; /* Ensure proper centering */
    background-repeat: no-repeat;
}

/* Right Section: Login Form */
.right-section {
    flex: 1;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background-color: #f9f9f9;
}

.right-section h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

form label {
    margin-bottom: 5px;
    font-size: 14px;
    color: #666;
}

form input {
    margin-bottom: 15px;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    padding: 10px;
    font-size: 16px;
    color: white;
    background-color: #0056b3; /* Strathmore Blue */
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

form button:hover {
    background-color: #003f8a;
}

p {
    margin-top: 10px;
    font-size: 14px;
    color: #333;
}

p a {
    color: #0056b3;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Half: Background Image -->
        <div class="left-section">
            <!-- Image handled via CSS background -->
        </div>

        <!-- Right Half: Login Form -->
        <div class="right-section">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
                
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p>Forgot your password? <a href="#">Reset it here</a></p>
        </div>
    </div>
    <script src="../Js/login.js"></script>
</body>
</html>