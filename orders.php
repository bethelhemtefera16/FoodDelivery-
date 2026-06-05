<?php
session_start();
include "bd.php";

/* ONLY CUSTOMER */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: loggin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* GET ORDERS */
$orders = mysqli_query($conn, "
SELECT * FROM orders
WHERE user_id = $user_id
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>My Orders - QuickBite</title>

<link rel="stylesheet" href="stylec.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:#f3f4f8;
    min-height:100vh;
}

/* HEADER */
.topbar{
    background:linear-gradient(black,black,black);
    color:white;
    padding:30px 50px;
    font-size:30px;
    font-weight:700;
    letter-spacing:.5px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
    position:sticky;
    top:0;
    z-index:100;
}

/* CONTAINER */
.orders-container{
    max-width:1100px;
    margin:50px auto;
    padding:20px;
}

/* CARD */
.order-card{
    background:white;
    border-radius:24px;
    padding:30px;
    margin-bottom:35px;
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    transition:.3s ease;
    border:1px solid #eee;
}

.order-card:hover{
    transform:translateY(-5px);
    box-shadow:0 18px 40px rgba(0,0,0,.12);
}

/* TOP */
.order-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.order-title{
    font-size:24px;
    font-weight:700;
    color:#222;
}

/* STATUS */
.status{
    padding:10px 20px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    color:white;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.pending{
    background:#f39c12;
}

.accepted{
    background:#3498db;
}

.delivered{
    background:#2ecc71;
}

.rejected{
    background:#e74c3c;
}

/* ITEMS */
.items-box{
    margin-top:15px;
}

.item{
    display:grid;
    grid-template-columns:1fr auto auto;
    gap:20px;
    align-items:center;
    padding:18px 0;
    border-bottom:1px solid #f0f0f0;
}

.item:last-child{
    border-bottom:none;
}

.food-name{
    font-weight:600;
    color:#333;
    font-size:17px;
}

.qty{
    background:#fff1ed;
    color:#ff5733;
    padding:6px 12px;
    border-radius:10px;
    font-weight:600;
    font-size:14px;
}

.price{
    font-weight:700;
    color:#222;
    font-size:16px;
}

/* TOTAL */
.total{
    margin-top:25px;
    text-align:right;
    font-size:24px;
    font-weight:700;
    color:#ff5733;
}

/* EMPTY */
.empty{
    background:white;
    padding:80px 30px;
    border-radius:25px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.empty h2{
    font-size:34px;
    margin-bottom:10px;
    color:#333;
}

.empty p{
    color:#777;
    font-size:18px;
}

/* RESPONSIVE */
@media(max-width:768px){

    .topbar{
        font-size:24px;
        padding:20px;
        text-align:center;
    }

    .order-card{
        padding:22px;
    }

    .item{
        grid-template-columns:1fr;
        gap:10px;
    }

    .total{
        text-align:left;
    }

}

</style>
</head>

<body>
!-- HEADER -->
<header class="ue-header">

    <div class="ue-top">

        <span id="hamburger">☰</span>

        <h1>QuickBite</h1>

    </div>

</header>

<!-- SIDE MENU -->
<nav id="sideMenu">

    <a href="home.php">🏠 Home</a>

    <a href="menu.php">📋 Menu</a>

    <a href="cart.php">🛒 Cart</a>

    <a href="profile.php">👤 Profile</a>

    <a href="payment.php">💳 Payment</a>
	
    <a href="orders.php">💳 orders</a>

</nav>

<!-- OVERLAY -->
<div id="menuOverlay"></div>

<div class="topbar">
    🍔 QuickBite - My Orders
</div>

<div class="orders-container">

<?php if(mysqli_num_rows($orders) > 0){ ?>

<?php while($order = mysqli_fetch_assoc($orders)) { ?>

<?php
$status = strtolower(trim($order['status']));
?>

<div class="order-card">

    <div class="order-top">

        <div class="order-title">
            Order #<?php echo $order['id']; ?>
        </div>

        <div class="status <?php echo $status; ?>">
            <?php echo strtoupper($status); ?>
        </div>

    </div>

    <div class="items-box">

    <!-- ITEMS -->
    <?php
    $order_id = $order['id'];

    $items = mysqli_query($conn, "
        SELECT oi.*, f.name
        FROM order_items oi
        JOIN food_items f ON oi.food_id = f.id
        WHERE oi.order_id = $order_id
    ");

    while($item = mysqli_fetch_assoc($items)) {
    ?>

    <div class="item">

        <div class="food-name">
            🍽 <?php echo $item['name']; ?>
        </div>

        <div class="qty">
            x<?php echo $item['quantity']; ?>
        </div>

        <div class="price">
            $<?php echo $item['price'] * $item['quantity']; ?>
        </div>

    </div>

    <?php } ?>

    </div>

    <div class="total">
        Total: $<?php echo $order['total_price']; ?>
    </div>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty">

    <h2>🛒 No Orders Yet</h2>

    <p>
        Looks like you haven’t ordered anything yet.
    </p>

</div>

<?php } ?>

</div>
<script>

/* =========================
   HAMBURGER MENU
========================= */

const hamburger = document.getElementById("hamburger");
const sideMenu = document.getElementById("sideMenu");
const overlay = document.getElementById("menuOverlay");

hamburger.addEventListener("click", function(){

    sideMenu.style.left = "0";
    overlay.style.display = "block";

});

overlay.addEventListener("click", function(){

    sideMenu.style.left = "-300px";
    overlay.style.display = "none";

});

/* =========================
   AUTO HIDE MESSAGE
========================= */

const message = document.querySelector(".cart-message");

if(message){

    setTimeout(function(){

        message.style.display = "none";

    }, 2000);

}

</script>
<script src="script.js"></script>
</body>
</html>