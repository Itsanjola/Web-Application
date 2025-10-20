<?php
include 'db.php';
$order_id = $_GET['order_id'];

$order = $conn->query("SELECT * FROM orders WHERE id=$order_id")->fetch_assoc();
$items = $conn->query("SELECT p.name, oi.quantity, oi.subtotal 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id=$order_id");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/confirmation.css">
    <title>Order Confirmation</title></head>
<body>
<h2>✅ Order #<?= $order_id ?> Confirmed</h2>
<p>Total: $<?= $order['total'] ?></p>

<table border="1" cellpadding="5">
<tr><th>Product</th><th>Qty</th><th>Subtotal</th></tr>
<?php while($row = $items->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td>$<?= $row['subtotal'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<p>Thank you for your purchase!</p>
<a href="index.php">Back to Store 🛍</a>
</body>
</html>