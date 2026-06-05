<?php
session_start();
include "bd.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: loggin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== GET CART ITEMS ===== */
$result = mysqli_query($conn, "
SELECT c.quantity, c.food_id, f.name, f.price
FROM cart c
JOIN food_items f ON c.food_id = f.id
WHERE c.user_id = $user_id
");

/* ===== TOTAL ===== */
$query = mysqli_query($conn, "
SELECT SUM(c.quantity * f.price) AS total
FROM cart c
JOIN food_items f ON c.food_id = f.id
WHERE c.user_id = $user_id
");

$data = mysqli_fetch_assoc($query);
$total = $data['total'] ?? 0;

/* ===== DELIVERY FEE ===== */
$deliveryFee = 2;

/* ===== SUCCESS FLAG ===== */
$paid = false;

/* ===== PAYMENT PROCESS ===== */
if (isset($_POST['pay'])) {

    $city = $_POST['city'] ?? '';
    $bank = $_POST['bank'] ?? 'Unknown';

    /* DELIVERY FEES */
    if ($city == "Mekanisa") {
        $deliveryFee = 3;
    } elseif ($city == "Jemo") {
        $deliveryFee = 5;
    } elseif ($city == "Alem Bank") {
        $deliveryFee = 4;
    }

    $finalTotal = $total + $deliveryFee;

    /* ===== INSERT ORDER ===== */
    mysqli_query($conn, "
    INSERT INTO orders(user_id, total_price)
    VALUES($user_id, '$finalTotal')
    ");

    $order_id = mysqli_insert_id($conn);

    /* ===== INSERT ORDER ITEMS ===== */
    $cartItems = mysqli_query($conn, "
    SELECT * FROM cart WHERE user_id = $user_id
    ");

    while ($item = mysqli_fetch_assoc($cartItems)) {

        $food_id = $item['food_id'];
        $quantity = $item['quantity'];

        $food = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT price FROM food_items WHERE id = $food_id
        "));

        $price = $food['price'];

        mysqli_query($conn, "
        INSERT INTO order_items(order_id, food_id, quantity, price)
        VALUES($order_id, $food_id, $quantity, '$price')
        ");
    }

    /* ===== INSERT PAYMENT (NEW FIX) ===== */
    mysqli_query($conn, "
    INSERT INTO payments(order_id, amount, payment_method)
    VALUES($order_id, '$finalTotal', '$bank')
    ");

    /* ===== CLEAR CART ===== */
    mysqli_query($conn, "
    DELETE FROM cart WHERE user_id = $user_id
    ");

    $paid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - QuickBite</title>
<link rel="stylesheet" href="stylec.css">

<style>
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f5f5f5;
}

.checkout-box{
    min-height:100vh;
    display:grid;
    grid-template-columns:1.2fr 1fr;
}

.checkout-left{
    background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1600&auto=format&fit=crop');
    background-size:cover;
    background-position:center;
    padding:70px;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.checkout-left h1{
    font-size:4rem;
}

.checkout-right{
    background:white;
    padding:50px;
}

.box, .summary-box{
    background:#fff;
    border:1px solid #eee;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

input, select, textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #ddd;
}

.btn{
    width:100%;
    padding:15px;
    background:#800020;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

.btn:hover{
    background:#5c0011;
}

.total{
    font-size:22px;
    font-weight:bold;
    color:#800020;
    text-align:right;
}

.success{
    position:fixed;
    top:20px;
    right:20px;
    background:#2ecc71;
    color:white;
    padding:15px 20px;
    border-radius:10px;
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
<?php if ($paid) { ?>
<div class="success">
    🎉 Payment Successful! Thanks for choosing us ❤️
</div>
<?php } ?>

<div class="checkout-box">

<!-- LEFT -->
<div class="checkout-left">
    <h1>QuickBite</h1>
    <p>Fresh food. Fast delivery.</p>
</div>

<!-- RIGHT -->
<div class="checkout-right">

<h2>Checkout</h2>

<form method="POST">

<div class="box">

<h3>Delivery</h3>

<select name="city" required>
    <option value="">Select City</option>
    <option>Mekanisa(3 birr)</option>
    <option>Jemo(4 birr)</option>
    <option>Alem Bank(5 birr)</option>
</select>

</div>

<div class="box">

<h3>Payment</h3>

<select name="bank" required>
    <option value="">Select Bank</option>
    <option>CBE</option>
    <option>Awash</option>
    <option>BOA</option>
    <option>Zemen</option>
</select>

</div>

<div class="summary-box">

<h3>Order Summary</h3>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<div>
    <?php echo $row['name']; ?> x <?php echo $row['quantity']; ?>
</div>
<?php } ?>

<div class="total">
    Total: $<?php echo $total + $deliveryFee; ?>
</div>

</div>

<button class="btn" name="pay">Pay Now</button>

</form>

</div>
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