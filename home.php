<?php
session_start();
include "bd.php"; // your database connection

// check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBite - Fast Food Delivery</title>
    <link rel="stylesheet" href="stylec.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
	
</head>
<body class="index-page">
<header class="ue-header">
    <div class="ue-top">
        <span id="hamburger"><b>☰</b></span>
        <h1>QuickBite</h1>

        <?php if($isLoggedIn): ?>
            <a href="profile.php" class="login-btn">My Account</a>
        <?php else: ?>
            <a href="loggin.php" id="loginBtn" class="login-btn">Login</a>
        <?php endif; ?>

    </div>
</header>

<nav id="sideMenu">
    <div class="menu-profile">
        <button id="darkToggle">🌙 Dark mode</button>
    </div>

    <a href="home.php">🏠 Home</a>
    <a href="menu.php">📋 Menu</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="profile.php">👤 Profile</a>
    <a href="payment.php">💳 payment</a>
	 <a href="orders.php">💳 orders</a>
    <a href="#">❓ Help</a>
    <a href="#">🎁 Invite Friend</a>

    <?php if($isLoggedIn): ?>
        <a href="logout.php">🚪 Sign Out</a>
    <?php endif; ?>
</nav>

<div id="menuOverlay"></div>

<section class="hero">
    <div class="hero-content">
        <h1>Delicious Food<br>Delivered in Minutes!</h1>
        <p>Hungry? Order from your favorite restaurants now.</p>
        <a href="menu.php" class="btn">Order Now</a>
    </div>
</section>

<section class="categories container">
    <h2>Popular Categories</h2>
    <div class="category-grid">
        <div class="category-card">
            <img src="https://media.istockphoto.com/id/184946701/photo/pizza.jpg?s=612x612&w=0&k=20&c=97rc0VIi-s3mn4xe4xDy9S-XJ_Ohbn92XaEMaiID_eY=" alt="Pizza">
            <h3>Pizza</h3>
        </div>
        <div class="category-card">
            <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80" alt="Burger">
            <h3>Burgers</h3>
        </div>
        <div class="category-card">
          <img src="https://i.ytimg.com/vi/_ZfR-gHrGrw/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLBZKJ4EbqabbFBgfHvQQ2jtcHodOQ" alt="Pasta">
            <h3>Pasta</h3>
        </div>
        <div class="category-card">
            <img src="https://irepo.primecp.com/1401/56/215695/Chocolate-Fantasy-OR_Category-CategoryPageDefault_ID-941759.jpg?v=941759" alt="Dessert">
            <h3>Desserts</h3>
        </div>
    </div>
</section>

<section class="offers container">
    <h2>Special Offers</h2>
    <table class="offer-table">
        <tr><th>Deal</th><th>Description</th><th>Price</th><th></th></tr>
        <tr><td>Family Combo</td><td>2 Large Pizzas + Sides + Drink</td><td>$29.99</td><td><button class="add-btn">Add</button></td></tr>
        <tr><td>Burger Deal</td><td>Buy 2 Get 1 Free</td><td>$18.99</td><td><button class="add-btn">Add</button></td></tr>
        <tr><td>Dessert Special</td><td>Any 2 Desserts + Free Delivery</td><td>$12.99</td><td><button class="add-btn">Add</button></td></tr>
    </table>
</section>

<section class="container" style="padding:120px 0; text-align:center;">
    <h2>About QuickBite</h2>
    <p style="font-size:1.2rem; max-width:800px; margin:30px auto;">
        QuickBite brings your favorite food straight to your door in minutes. 
        Founded in 2025, we partner with the best local restaurants to deliver happiness, one bite at a time!
    </p>
</section>

<section class="social-section">
    <h2>Follow Us</h2>
    <div class="social-icons">
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png"></a>
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png"></a>
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png"></a>
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111646.png"></a>
    </div>
</section>

<footer>
    <div class="container">
        <p>© 2025 QuickBite • Fast & Delicious Food Delivery • All rights reserved.</p>
    </div>
</footer>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById("hamburger");
    const sideMenu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    console.log("JS loaded"); // ✅ check in browser console

    hamburger.onclick = function () {
        console.log("clicked"); // ✅ must appear
        sideMenu.style.left = "0";
        overlay.style.display = "block";
    };

    overlay.onclick = function () {
        sideMenu.style.left = "-300px";
        overlay.style.display = "none";
    };
});
// DARK MODE
const darkToggle = document.getElementById("darkToggle");

// load saved mode
if (localStorage.getItem("darkMode") === "on") {
    document.body.classList.add("dark");
}

if (darkToggle) {
    darkToggle.onclick = function () {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            localStorage.setItem("darkMode", "on");
        } else {
            localStorage.setItem("darkMode", "off");
        }
    };
}
</script>
<script src="script.js"></script>
</body>
</html>