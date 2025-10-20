<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $conn->query("INSERT INTO customers (name, email, address) VALUES ('$name','$email','$address')");
    $customer_id = $conn->insert_id;

    $total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $price = $conn->query("SELECT price FROM products WHERE id=$id")->fetch_assoc()['price'];
        $total += $price * $qty;
    }

    $conn->query("INSERT INTO orders (customer_id, total) VALUES ($customer_id, $total)");
    $order_id = $conn->insert_id;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $price = $conn->query("SELECT price FROM products WHERE id=$id")->fetch_assoc()['price'];
        $subtotal = $price * $qty;
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, subtotal)
                      VALUES ($order_id, $id, $qty, $subtotal)");
    }

    $_SESSION['cart'] = [];
    header("Location: confirmation.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/checkout.css">
    <title>Checkout</title></head>
<body>
<h2>Checkout</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <textarea name="address" placeholder="Address" required></textarea><br><br>
    <button type="submit">Place Order</button>
</form>
</body>
</html>