<?php
$conn = mysqli_connect("localhost", "root", "", "food_delivery");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>