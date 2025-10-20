<?php
session_start();
include 'db.php';

// ADD TO CART
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $qty;
    } else {
        $_SESSION['cart'][$id] += $qty;
    }
}

// DELETE ITEM
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    unset($_SESSION['cart'][$delete_id]);
}

$total = 0;
$cart_items = [];

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;
        $cart_items[] = [
            'product' => $row,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: black;
            padding: 20px;
            color: black;
        }
        h2 {
            text-align: center;
            background: purple;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
            box-shadow: 0px 2px 6px purple;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: purple;
            color: white;
        }
         a{
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        tr{
            color: black;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .btn-delete {
            background: purple;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-delete:hover {
            background: darkred;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            display: inline;
        }
    </style>
</head>
<body>
<h2>🛒 Your Cart</h2>

<?php if(empty($cart_items)): ?>
    <p>No items in cart</p>
<?php else: ?>
<table>
    <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>
    <?php foreach($cart_items as $item): ?>
    <tr>
        <td><?= $item['product']['name'] ?></td>
        <td><?= $item['qty'] ?></td>
        <td>$<?= $item['product']['price'] ?></td>
        <td>$<?= $item['subtotal'] ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="delete_id" value="<?= $item['product']['id'] ?>">
                <button type="submit" class="btn-delete">❌ Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h3 style="color:white";>Total: $<?= $total ?></h3>
<a style="color:white"; href="checkout.php">Proceed to Checkout</a>
<?php endif; ?>

<a style="color:white"; href="index.php">⬅ Back to Products</a>
</body>
</html>