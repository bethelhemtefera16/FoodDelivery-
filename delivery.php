<?php
session_start();
include "bd.php";
$message = "";

if (isset($_GET['accept'])) {

    $order_id = $_GET['accept'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Accepted'
    WHERE id = $order_id
    ");

    header("Location: delivery.php?msg=accepted");
    exit();
}

if (isset($_GET['reject'])) {

    $order_id = $_GET['reject'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Rejected'
    WHERE id = $order_id
    ");

    header("Location: delivery.php?msg=rejected");
    exit();
}

if (isset($_GET['deliver'])) {

    $order_id = $_GET['deliver'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Delivered'
    WHERE id = $order_id
    ");

    header("Location: delivery.php?msg=delivered");
    exit();
}
if (isset($_GET['msg'])) {

    if ($_GET['msg'] == "accepted") {
        $message = "✅ Order Accepted Successfully!";
    }

    if ($_GET['msg'] == "rejected") {
        $message = "❌ Order Rejected!";
    }

    if ($_GET['msg'] == "delivered") {
        $message = "🚚 Order Delivered!";
    }
}

/* ONLY DELIVERY GUY */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'delivery') {
    header("Location: loggin.php");
    exit();
}

/* ===== UPDATE STATUS ===== */
if (isset($_GET['deliver'])) {
    $order_id = $_GET['deliver'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Delivered'
    WHERE id = $order_id
    ");
}

if (isset($_GET['accept'])) {
    $order_id = $_GET['accept'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Accepted'
    WHERE id = $order_id
    ");
}

if (isset($_GET['reject'])) {
    $order_id = $_GET['reject'];

    mysqli_query($conn, "
    UPDATE orders
    SET status='Rejected'
    WHERE id = $order_id
    ");
}

/* ===== GET ORDERS ===== */
$orders = mysqli_query($conn, "
SELECT orders.*, users.name
FROM orders
JOIN users ON orders.user_id = users.id
ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Delivery Dashboard</title>

<style>
body{
    margin:0;
    font-family:Poppins,sans-serif;
    background:#f5f5f5;
}

.header{
    background:#800020;
    color:white;
    padding:20px 40px;
    font-size:28px;
    font-weight:bold;
}

.container{
    max-width:1200px;
    margin:40px auto;
    padding:20px;
}

.order-card{
    background:white;
    border-radius:20px;
    padding:25px;
    margin-bottom:30px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.order-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.status{
    padding:10px 18px;
    border-radius:30px;
    color:white;
    font-size:14px;
    font-weight:bold;
}

.pending{ background:#f39c12; }
.delivered{ background:#2ecc71; }
.accepted{ background:#3498db; }
.rejected{ background:#e74c3c; }

.item{
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.total{
    margin-top:15px;
    text-align:right;
    font-size:20px;
    font-weight:bold;
    color:#800020;
}

.btn{
    display:inline-block;
    margin-top:10px;
    padding:10px 15px;
    background:#800020;
    color:white;
    text-decoration:none;
    border-radius:10px;
    font-weight:bold;
}

.btn:hover{
    background:#5c0011;
}

.btn-accept{ background:#2ecc71; }
.btn-reject{ background:#e74c3c; }
.popup-message{
    position:fixed;
    top:20px;
    right:20px;
    background:#2ecc71;
    color:white;
    padding:15px 22px;
    border-radius:12px;
    font-weight:bold;
    z-index:99999;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
    animation:fadeIn .3s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(-10px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>

</head>

<body>
<?php if($message != "") { ?>

<div class="popup-message">
    <?php echo $message; ?>
</div>

<?php } ?>

<div class="header">
    🚚 Delivery Dashboard
</div>

<div class="container">

<?php while($order = mysqli_fetch_assoc($orders)) { ?>

<div class="order-card">

    <div class="order-top">

        <div>
            <h2>Order #<?php echo $order['id']; ?></h2>

            <p>Customer: <b><?php echo $order['name']; ?></b></p>
           
        </div>

        <div>

            <?php if($order['status'] == 'pending') { ?>
                <span class="status pending">Pending</span>

            <?php } elseif($order['status'] == 'accepted') { ?>
                <span class="status accepted">Accepted</span>

            <?php } elseif($order['status'] == 'rejected') { ?>
                <span class="status rejected">Rejected</span>

            <?php } else { ?>
                <span class="status delivered">Delivered</span>
            <?php } ?>

        </div>

    </div>

    <!-- ITEMS -->
    <?php
    $order_id = $order['id'];

    $items = mysqli_query($conn, "
    SELECT order_items.*, food_items.name
    FROM order_items
    JOIN food_items ON order_items.food_id = food_items.id
    WHERE order_items.order_id = $order_id
    ");

    while($item = mysqli_fetch_assoc($items)) {
        $itemTotal = $item['price'] * $item['quantity'];
    ?>

    <div class="item">
        <span><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
        <span>$<?php echo $itemTotal; ?></span>
    </div>

    <?php } ?>

    <div class="total">
        Total: $<?php echo $order['total_price']; ?>
    </div>

   <!-- BUTTONS -->
<?php if($order['status'] != 'Delivered') { ?>

    <a class="btn btn-accept" href="delivery.php?accept=<?php echo $order['id']; ?>">
        Accept
    </a>

    <a class="btn btn-reject" href="delivery.php?reject=<?php echo $order['id']; ?>">
        Reject
    </a>

<?php } ?>

<?php if($order['status'] == 'Accepted') { ?>

    <a class="btn" href="delivery.php?deliver=<?php echo $order['id']; ?>">
        Mark As Delivered
    </a>

<?php } ?>

</div>

<?php } ?>

</div>
<script>

setTimeout(() => {

    const popup = document.querySelector(".popup-message");

    if(popup){
        popup.style.display = "none";
    }

}, 2000);

</script>

</body>
</html>