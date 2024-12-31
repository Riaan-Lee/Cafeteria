<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Strathmore Cafeteria</title>
    <link rel="stylesheet" href="../Css/homepage.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul id="navigation">
        <div class="left">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="menu2.php">Menu</a></li>
            <li><a href="contact.php">Contact</a></li>
        </div>
        <div class="right">
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </div>
    </ul>

    <!-- Welcome Banner Section -->
    <section id="welcome-banner">
        <h1>Welcome to Strathmore Cafeteria</h1>
        <p>Fueling minds with delicious food since 2024</p>
        <button class="btn"><a href="menu.php" style="text-decoration: none;">Explore the Menu</a></button>
    </section>

    <!-- Impressive Image Section -->
    <section id="image-gallery">
        <div class="gallery-item">
            <img src="lasanga photo.jpg" alt="Strathmore Cafeteria Image 1">
            <p>Fresh and Healthy Meals</p>
        </div>
        <div class="gallery-item">
            <img src="black head chef photo.jpg" alt="Strathmore Cafeteria Image 2">
            <p>Our Passionate Staff Serving You</p>
        </div>
        <div class="gallery-item">
            <img src="meals photo.jpg" alt="Strathmore Cafeteria Image 3">
            <p>Exciting Meal Offers Every Day</p>
        </div>
    </section>

    <!-- Brief About Section -->
    <section id="about-us">
        <h2>About Strathmore Cafeteria</h2>
        <p>
            Welcome to the Strathmore Cafeteria! We pride ourselves in offering a wide variety of freshly prepared meals that meet the nutritional needs of students, faculty, and staff. Our dedicated kitchen team ensures that each meal is made with quality ingredients, cooked with love, and served with a smile. We believe that great food enhances productivity, and we are committed to fueling minds with every meal.
        </p>
        <p>
            Whether you're looking for a quick bite between classes or a hearty meal to get you through the day, Strathmore Cafeteria is here to serve you. Our menu includes everything from traditional local delicacies to international cuisines, catering to all tastes and dietary preferences.
        </p>
    </section>

    <!-- Meet the Staff Section -->
    <section id="meet-the-staff">
        <h2>Meet Our Wonderful Staff</h2>
        <p>
            Our staff members are the heart of Strathmore Cafeteria. From chefs to customer service, every team member is dedicated to ensuring you enjoy every meal and experience. Our head chef, Chef James, brings over 15 years of experience in the culinary arts, specializing in African and European fusion cuisines. Whether you're ordering a quick snack or a full-course meal, our team is always ready to assist with a friendly smile.
        </p>
    </section>

    <!-- Call to Action -->
    <section id="cta">
        <h2>Join Us at Strathmore Cafeteria</h2>
        <p>Come for the food, stay for the experience. Join us today!</p>
        <button class="btn"><a href="login.php">Login to Order</a></button>
        <button class="btn"><a href="register.php">Register Now</a></button>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Strathmore Cafeteria. All rights reserved.</p>
    </footer>

</body>
</html>
