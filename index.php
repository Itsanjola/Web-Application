<?php
session_start();
include 'db.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

$query = "SELECT * FROM products WHERE name LIKE '%$search%'";

if ($category != '') {
    $query .= " AND category='$category'";
}

if ($sort == 'asc') {
    $query .= " ORDER BY price ASC";
} elseif ($sort == 'desc') {
    $query .= " ORDER BY price DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Mini Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;      }
        h2 {
            text-align: center;
            color: black;
        }
        h2 span {
            color: purple;
        }
        form {
            padding: 10px;
            text-align: center;
        }
        .name {
            display: inline-block;
            vertical-align: top;
        }
        .product-card {
            margin: 10px;
            border: 1px solid #090808ff;
            padding: 10px;
            width: 200px;
            text-align: center;
            background: #bbacacff;
            border-radius: 10px;
        }
        .product-card img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .product-card button {
            background: purple;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .product-card button:hover {
            background: black;
        }
    </style>
</head>
<body>
<h2>ZY'S <span>GAGETS</span></h2>

<form method="GET" style="background-color: black;">
    <input type="text" name="search" placeholder="Search..." value="<?= $search ?>">
    <select name="category">
        <option value="">All Categories</option>
        <option value="Phone" <?= $category == 'Phone' ? 'selected' : '' ?>>Phone</option>
        <option value="Tablet" <?= $category == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
        <option value="Laptop" <?= $category == 'Laptop' ? 'selected' : '' ?>>Laptop</option>
        <option value="Accessory" <?= $category == 'Accessory' ? 'selected' : '' ?>>Accessory</option>
    </select>
    <select name="sort">
        <option value="">Sort</option>
        <option value="asc" <?= $sort == 'asc' ? 'selected' : '' ?>>Price: Low → High</option>
        <option value="desc" <?= $sort == 'desc' ? 'selected' : '' ?>>Price: High → Low</option>
    </select>
    <button type="submit">Filter</button>
</form>

<hr>

<?php while($row = $result->fetch_assoc()): ?>
    <div class="name">
        <div class="product-card">
            <!-- 🖼 Image Display -->
            <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
            <h3><?= $row['name'] ?></h3>
            <p>Category: <?= $row['category'] ?></p>
            <p>Price: $<?= $row['price'] ?></p>
            <form action="cart.php" method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="number" name="qty" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </div>
<?php endwhile; ?>

<a href="cart.php">🛒 View Cart</a>
</body>
</html>