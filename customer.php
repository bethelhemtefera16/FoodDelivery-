<?php
session_start();
include "bd.php";

$user_id = $_SESSION['user_id'];

// GET CART
$cart = mysqli_query($conn, "
SELECT c.*, f.price 
FROM cart c 
JOIN food_items f ON c.food_id = f.id
WHERE c.user_id = $user_id
");

$total = 0;

while($item = mysqli_fetch_assoc($cart)) {
    $total += $item['price'] * $item['quantity'];
}

// CREATE ORDER
mysqli_query($conn, "
INSERT INTO orders (user_id, total_price, status)
VALUES ($user_id, $total, 'pending')
");

$order_id = mysqli_insert_id($conn);

// INSERT ORDER ITEMS
$cart = mysqli_query($conn, "
SELECT * FROM cart WHERE user_id = $user_id
");

while($item = mysqli_fetch_assoc($cart)) {
    mysqli_query($conn, "
    INSERT INTO order_items (order_id, food_id, quantity)
    VALUES ($order_id, {$item['food_id']}, {$item['quantity']})
    ");
}

// CLEAR CART
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

echo "Order placed successfully!";