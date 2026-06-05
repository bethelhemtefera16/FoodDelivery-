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
$id = $_GET['id'];
$action = $_GET['action'];

if ($action == "plus") {
    mysqli_query($conn, "
        UPDATE cart 
        SET quantity = quantity + 1 
        WHERE id=$id AND user_id=$user_id
    ");
}

if ($action == "minus") {
    mysqli_query($conn, "
        UPDATE cart 
        SET quantity = quantity - 1 
        WHERE id=$id AND user_id=$user_id
    ");

    mysqli_query($conn, "
        DELETE FROM cart 
        WHERE quantity <= 0 AND user_id=$user_id
    ");
}

if ($action == "remove") {
    mysqli_query($conn, "
        DELETE FROM cart 
        WHERE id=$id AND user_id=$user_id
    ");
}

header("Location: cart.php");
exit();
/* =========================
   GET FOOD ID
========================= */
if (!isset($_GET['id'])) {
    header("Location: menu.php");
    exit();
}

$food_id = (int) $_GET['id'];

/* =========================
   CHECK IF ALREADY IN CART
========================= */
$check = mysqli_query($conn, "
SELECT * FROM cart 
WHERE user_id = $user_id 
AND food_id = $food_id
");

/* =========================
   IF EXISTS → INCREASE QTY
========================= */
if (mysqli_num_rows($check) > 0) {

    mysqli_query($conn, "
    UPDATE cart 
    SET quantity = quantity + 1
    WHERE user_id = $user_id 
    AND food_id = $food_id
    ");

} 
/* =========================
   ELSE → INSERT NEW ITEM
========================= */
else {

    mysqli_query($conn, "
    INSERT INTO cart (user_id, food_id, quantity)
    VALUES ($user_id, $food_id, 1)
    ");
}

/* =========================
   BACK TO CART
========================= */
header("Location: cart.php");
exit();
?>