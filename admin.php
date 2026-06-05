<?php
session_start();
include "bd.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: loggin.php");
    exit();
}

// ADD FOOD
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    mysqli_query($conn, "INSERT INTO food_items (name, description, price, image, category)
    VALUES ('$name','$description','$price','$image','$category')");
}

// DELETE FOOD
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM food_items WHERE id=$id");
}

// UPDATE FOOD
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    mysqli_query($conn, "
    UPDATE food_items 
    SET name='$name', description='$description', price='$price', image='$image', category='$category'
    WHERE id=$id
    ");
}

// FETCH FOOD
$result = mysqli_query($conn, "SELECT * FROM food_items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#111;
    color:white;
    padding:30px;
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:30px;
    font-size:40px;
    color:#ff5733;
}

h3{
    margin:20px 0;
    color:#fff;
}

/* ADD FORM */
.add-form{
    background:#1c1c1c;
    padding:20px;
    border-radius:15px;
    margin-bottom:40px;
    display:grid;
    gap:15px;
}

.add-form input{
    padding:12px;
    border:none;
    border-radius:8px;
    background:#2a2a2a;
    color:white;
    font-size:16px;
}

.add-form button{
    background:#ff5733;
    color:white;
    border:none;
    padding:12px;
    border-radius:10px;
    cursor:pointer;
    font-size:17px;
    font-weight:bold;
    transition:0.3s;
}

.add-form button:hover{
    background:#e74c3c;
}

/* FOOD GRID */
.food-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(320px,1fr));
    gap:20px;
}

/* CARD */
.food-card{
    background:#1c1c1c;
    padding:15px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.4);
}

.food-card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:15px;
}

.food-card input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:none;
    border-radius:8px;
    background:#2a2a2a;
    color:white;
}

/* BUTTONS */
.btn-group{
    display:flex;
    gap:10px;
}

.update-btn{
    flex:1;
    background:#2ecc71;
    color:white;
    border:none;
    padding:10px;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.delete-btn{
    flex:1;
    background:#e74c3c;
    color:white;
    text-align:center;
    padding:10px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

.update-btn:hover{
    background:#27ae60;
}

.delete-btn:hover{
    background:#c0392b;
}

</style>
</head>

<body>

<h2>🍔 Admin Dashboard</h2>

<h3>Add Food</h3>

<form method="POST" class="add-form">

    <input name="name" placeholder="Food Name" required>

    <input name="description" placeholder="Description" required>

    <input name="price" placeholder="Price" required>

    <input name="image" placeholder="Image URL" required>

    <input name="category" placeholder="Category" required>

    <button name="add">➕ Add Food</button>

</form>

<h3>All Food Items</h3>

<div class="food-grid">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<form method="POST" class="food-card">

    <img src="<?php echo $row['image']; ?>" alt="">

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input name="name" value="<?php echo $row['name']; ?>">

    <input name="description" value="<?php echo $row['description']; ?>">

    <input name="price" value="<?php echo $row['price']; ?>">

    <input name="image" value="<?php echo $row['image']; ?>">

    <input name="category" value="<?php echo $row['category']; ?>">

    <div class="btn-group">

        <button name="update" class="update-btn">
            Update
        </button>

        <a class="delete-btn"
        href="admin.php?delete=<?php echo $row['id']; ?>">
            Delete
        </a>

    </div>

</form>

<?php } ?>

</div>

</body>
</html>