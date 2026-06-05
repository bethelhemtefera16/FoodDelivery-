<?php
session_start();
include "bd.php";

$isLoggedIn = isset($_SESSION['user_id']);

// ONLY CUSTOMER CAN ACCESS
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: loggin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET CART ITEMS
$result = mysqli_query($conn, "
SELECT c.*, f.name, f.price, f.image
FROM cart c
JOIN food_items f ON c.food_id = f.id
WHERE c.user_id = $user_id
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart</title>

<link rel="stylesheet" href="stylec.css">

<style>
/* CART GRID SIDE BY SIDE */
.menu-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));
    gap:15px;
    margin-top:30px;
}

/* CART CARD SMALL */
.menu-card{
    background:#111;
    color:white;
    padding:10px;
    border-radius:12px;
    text-align:center;
}

/* IMAGE */
.menu-card img{
    width:100%;
    height:120px;
    object-fit:cover;
    border-radius:10px;
}

/* BUTTONS */
.menu-card button{
    padding:5px 8px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

/* ACTION BUTTONS */
.actions{
    display:flex;
    justify-content:center;
    gap:8px;
    margin-top:8px;
}

/* TOTAL */
.total-box{
    text-align:right;
    margin-top:20px;
    font-size:20px;
    font-weight:bold;
    color:blue;
}

/* PAY BUTTON */
.btn{
    background:#800020;
    color:white;
    padding:10px 20px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
</style>

</head>

<body>

<header class="ue-header">
    <div class="ue-top">
        <span id="hamburger">☰</span>
        <h1>QuickBite</h1>

        <?php if($isLoggedIn): ?>
            <a href="profile.php" class="login-btn">My Account</a>
        <?php else: ?>
            <a href="loggin.php" class="login-btn">Login</a>
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
    <a href="payment.php">💳 Payment</a>
	    <a href="orders.php">💳 orders</a>

    <?php if($isLoggedIn): ?>
        <a href="logout.php">🚪 Sign Out</a>
    <?php endif; ?>
</nav>

<div id="menuOverlay"></div>

<section class="menu-section">
    <h2>🛒 Your Cart</h2>

    <div class="menu-grid">

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $itemTotal = $row['price'] * $row['quantity'];
                $total += $itemTotal;
            ?>

            <div class="menu-card">

                <img src="<?php echo $row['image']; ?>" alt="">

                <h3><?php echo $row['name']; ?></h3>

                <p>
                    $<?php echo $row['price']; ?> × 
                    <?php echo $row['quantity']; ?>
                </p>

                <p><b>$<?php echo $itemTotal; ?></b></p>

                <div class="actions">

                    <a href="cart_action.php?action=plus&id=<?php echo $row['id']; ?>">
                        <button>➕</button>
                    </a>

                    <a href="cart_action.php?action=minus&id=<?php echo $row['id']; ?>">
                        <button>➖</button>
                    </a>

                    <a href="cart_action.php?action=remove&id=<?php echo $row['id']; ?>">
                        <button style="color:red;">❌</button>
                    </a>

                </div>

            </div>

            <?php } ?>

        <?php } else { ?>
            <p>Your cart is empty 🛒</p>
        <?php } ?>

    </div>

    <div class="total-box">
        Total: $<?php echo $total; ?>
    </div>

    <div style="text-align:right; margin-top:15px;">
        <a href="payment.php">
            <button class="btn">💳 Pay Now</button>
        </a>
    </div>

</section>

<script>
// hamburger menu
document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById("hamburger");
    const sideMenu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    hamburger.onclick = () => {
        sideMenu.style.left = "0";
        overlay.style.display = "block";
    };

    overlay.onclick = () => {
        sideMenu.style.left = "-300px";
        overlay.style.display = "none";
    };
});

// dark mode
const darkToggle = document.getElementById("darkToggle");

if (localStorage.getItem("darkMode") === "on") {
    document.body.classList.add("dark");
}

if (darkToggle) {
    darkToggle.onclick = function () {
        document.body.classList.toggle("dark");
        localStorage.setItem(
            "darkMode",
            document.body.classList.contains("dark") ? "on" : "off"
        );
    };
}
</script>

</body>
</html>