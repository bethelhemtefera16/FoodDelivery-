<?php
session_start();
include "bd.php";

/* =========================
   CHECK LOGIN
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: loggin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = false;

/* =========================
   ADD TO CART
========================= */
if (isset($_POST['add_to_cart'])) {

    $food_id = $_POST['food_id'];

    // CHECK IF FOOD ALREADY EXISTS
    $check = mysqli_query($conn, "
        SELECT * FROM cart
        WHERE user_id = $user_id
        AND food_id = $food_id
    ");

    if (mysqli_num_rows($check) > 0) {

        mysqli_query($conn, "
            UPDATE cart
            SET quantity = quantity + 1
            WHERE user_id = $user_id
            AND food_id = $food_id
        ");

    } else {

        mysqli_query($conn, "
            INSERT INTO cart (user_id, food_id, quantity)
            VALUES ($user_id, $food_id, 1)
        ");
    }

    $success = true;
}

/* =========================
   GET FOOD ITEMS
========================= */
$result = mysqli_query($conn, "SELECT * FROM food_items");

/* =========================
   CART COUNT
========================= */
$countQuery = mysqli_query($conn, "
    SELECT SUM(quantity) AS total
    FROM cart
    WHERE user_id = $user_id
");

$countData = mysqli_fetch_assoc($countQuery);

$cartCount = $countData['total'];

if (!$cartCount) {
    $cartCount = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Menu - QuickBite</title>

<link rel="stylesheet" href="stylec.css">

<style>

/* SUCCESS MESSAGE */
.cart-message{
    position:fixed;
    top:20px;
    right:20px;
    background:#2ecc71;
    color:white;
    padding:25px 25px;
    border-radius:10px;
    font-weight:bold;
    z-index:99999;
    animation:fadeIn 0.3s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateX(20px);
    }

    to{
        opacity:1;
        transform:translateX(0);
    }
}

/* FLOATING CART */
.cart-float{
    position:fixed;
    bottom:20px;
    right:20px;
    background:#800020;
    color:white;
    padding:15px 22px;
    border-radius:50px;
    text-decoration:none;
    font-weight:bold;
    box-shadow:0 5px 15px rgba(0,0,0,0.3);
    z-index:9999;
}

.cart-float:hover{
    background:#e74c3c;
}

/* CART COUNT */
.cart-count{
    background:white;
    color:#ff5733;
    padding:3px 8px;
    border-radius:50%;
    margin-left:8px;
    font-size:14px;
}

/* SIDE MENU */
#sideMenu{
    position:fixed;
    top:0;
    left:-300px;
    width:260px;
    height:100vh;
    background:white;
    transition:0.3s;
    z-index:9999;
    padding-top:70px;
}

#sideMenu a{
    display:block;
    padding:15px 20px;
    text-decoration:none;
    color:black;
    border-bottom:1px solid #eee;
}

#sideMenu a:hover{
    background:#f5f5f5;
}

#menuOverlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100vh;
    background:rgba(0,0,0,0.5);
    display:none;
    z-index:9998;
}

#hamburger{
    cursor:pointer;
    font-size:30px;
}

/* HEADER */
.ue-header{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    background:none;
    color:white;
    z-index:10000;
}

.ue-top{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px 20px;
}

/* MENU SECTION */
.menu-section{
    padding:120px 20px;
}

</style>

</head>

<body>

<!-- HEADER -->
<header class="ue-header">

    <div class="ue-top">

        <span id="hamburger">☰</span>

        <h1>QuickBite</h1>

    </div>

</header>

<!-- SIDE MENU -->
<nav id="sideMenu">
     <h1>QuickBite</h1>
	 

    <a href="home.php">🏠 Home</a>

    <a href="menu.php">📋 Menu</a>

    <a href="cart.php">🛒 Cart</a>

    <a href="profile.php">👤 Profile</a>

    <a href="payment.php">💳 Payment</a>
	
    <a href="orders.php">💳 orders</a>

</nav>

<!-- OVERLAY -->
<div id="menuOverlay"></div>

<!-- SUCCESS MESSAGE -->
<?php if($success) { ?>

<div class="cart-message">
    ✅ Food added to cart!
</div>

<?php } ?>

<!-- MENU -->
<section class="menu-section">

<h2>🍽 Full Menu</h2>

<div class="menu-grid">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="menu-card">

    <img src="<?php echo $row['image']; ?>" 
    alt="<?php echo $row['name']; ?>">

    <h3><?php echo $row['name']; ?></h3>

    <p>$<?php echo $row['price']; ?></p>

    <form method="POST">

        <input 
        type="hidden"
        name="food_id"
        value="<?php echo $row['id']; ?>">

        <button 
        type="submit"
        name="add_to_cart"
        class="add-btn">

            Add to Cart

        </button>

    </form>

</div>

<?php } ?>

</div>

</section>

<!-- FLOATING CART BUTTON -->
<a href="cart.php" class="cart-float">

    🛒 Cart

    <span class="cart-count">
        <?php echo $cartCount; ?>
    </span>

</a>

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