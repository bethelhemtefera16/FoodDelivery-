<?php
session_start();
include 'bd.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    // CHECK USER
    $check = $conn->query("SELECT * FROM users WHERE name='$name'");

    if ($check->num_rows > 0) {

        // LOGIN
        $sql = "SELECT * FROM users WHERE name='$name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // VERIFY ENCRYPTED PASSWORD
            if (password_verify($password, $user['password'])) {

                // SESSION
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // ROLE REDIRECT
                if ($user['role'] == 'admin') {

                    header("Location: admin.php");

                } elseif ($user['role'] == 'delivery') {

                    header("Location: delivery.php");

                } else {

                    header("Location: menu.php");
                }

                exit();

            } else {

                $message = "Invalid password!";
            }

        } else {

            $message = "User not found!";
        }

    } else {

        // ENCRYPT PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // REGISTER
        $sql = "INSERT INTO users
        (name,email,password,phone,role)
        VALUES
        ('$name','$email','$hashed_password','$phone','$role')";

        if ($conn->query($sql) === TRUE) {

            $message = "Registered successfully! Now login.";

        } else {

            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - QuickBite</title>
	 <link rel="stylesheet" href="stylec.css">

<style>

/* ================= LOGIN PAGE NEW DESIGN ================= */

.login-section{
    min-height:100vh !important;
    display:grid !important;
    grid-template-columns:1.2fr 1fr !important;
    padding:0 !important;
    background:#f5f5f5 !important;
}

/* LEFT SIDE */

.login-left{
    background:
    linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1600&auto=format&fit=crop');

    background-size:cover;
    background-position:center;

    display:flex;
    align-items:center;
    justify-content:center;
    padding:70px;
    color:white;
}

.login-left-content h1{
    font-size:5rem;
    margin-bottom:20px;
    color:white;
}

.login-left-content p{
    font-size:1.2rem;
    line-height:1.9;
    max-width:600px;
}

/* FOOD CARDS */

.food-preview{
    display:flex;
    gap:20px;
    margin-top:40px;
    flex-wrap:wrap;
}

.food-card{
    width:170px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    border-radius:20px;
    overflow:hidden;
    transition:.3s;
}

.food-card:hover{
    transform:translateY(-8px);
}

.food-card img{
    width:100%;
    height:140px;
    object-fit:cover;
}

.food-card span{
    display:block;
    padding:12px;
    text-align:center;
    font-weight:600;
}

/* RIGHT SIDE */

.login-right{
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:50px;
}

.login-box{
    width:100% !important;
    max-width:500px !important;
    background:transparent !important;
    box-shadow:none !important;
    padding:0 !important;
}

.login-box h2{
    font-size:3rem;
    color:#800020;
    margin-bottom:10px;
}

.subtitle{
    color:#666;
    margin-bottom:30px;
}

/* MESSAGE */

.login-message{
    background:#ffe5ea;
    color:#800020;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
}

/* FORM */

.login-box form{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.double-input{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.login-box input,
.login-box select{
    width:100%;
    padding:15px;
    border:2px solid #eee;
    border-radius:14px;
    font-size:15px;
    background:#fafafa;
    outline:none;
    transition:.3s;
}

.login-box input:focus,
.login-box select:focus{
    border-color:#800020;
    background:white;
}

.login-btn-submit{
    background:#800020;
    color:white;
    border:none;
    padding:16px;
    border-radius:15px;
    font-size:17px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

.login-btn-submit:hover{
    background:#5c0011;
    transform:translateY(-2px);
}

/* RESPONSIVE */

@media(max-width:950px){

    .login-section{
        grid-template-columns:1fr !important;
    }

    .login-left{
        min-height:400px;
        padding:40px;
    }

    .login-left-content h1{
        font-size:3rem;
    }

    .double-input{
        grid-template-columns:1fr;
    }

    .login-right{
        padding:40px 25px;
    }
}

</style>

</head>
<body>
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
<section class="login-section">

    <!-- LEFT -->

    <div class="login-left">

        <div class="login-left-content">

            <h1>QuickBite</h1>

            <p>
                Delicious food delivered fast to your doorstep.
                Order burgers, pizza, pasta and more anytime.
            </p>

            <div class="food-preview">

                <div class="food-card">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=800&auto=format&fit=crop">
                    <span>Burger</span>
                </div>

                <div class="food-card">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=800&auto=format&fit=crop">
                    <span>Pizza</span>
                </div>

                <div class="food-card">
                    <img src="https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?q=80&w=800&auto=format&fit=crop">
                    <span>Pasta</span>
                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="login-right">

        <div class="login-box">

            <h2>Login / Register</h2>

            <p class="subtitle">
                Continue with your QuickBite account
            </p>

            <?php if($message != ""): ?>
                <div class="login-message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="loggin.php">

                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="customer">Customer</option>
                    <option value="delivery">Delivery Guy</option>
                    <option value="admin">Admin</option>
                </select>

                <div class="double-input">

                    <input type="text" name="name" placeholder="Full Name" required>

                    <input type="tel" name="phone" placeholder="Phone Number" required>

                </div>

                <input type="email" name="email" placeholder="Email Address" required>

                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" class="login-btn-submit">
                    Continue
                </button>

            </form>

        </div>

    </div>

</section>
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