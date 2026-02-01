<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$tables = ['bakery', 'dairy', 'fruits', 'products', 'vegetables'];
$results = [];

// ADD TO CART
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image']);

    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $new_qty = $row['quantity'] + $quantity;
        mysqli_query($conn, "UPDATE cart SET quantity='$new_qty' WHERE id='{$row['id']}'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, name, price, quantity, image_url) 
                             VALUES ('$user_id', '$product_id', '$name', '$price', '$quantity', '$image_url')");
    }
    header("Location: cart.php");
    exit;
}

// SEARCH
if ($query != '') {
    foreach ($tables as $table) {
        $sql = "SELECT id, name, description, price, image_url, '$table' AS category 
                FROM $table 
                WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $img = $row['image_url'];
            if (!preg_match("/^https?:\/\//", $img)) {
                if (file_exists("uploads/$table/" . $img)) {
                    $img = "uploads/$table/" . $img;
                } elseif (file_exists("uploads/" . $img)) {
                    $img = "uploads/" . $img;
                } else {
                    $img = "https://via.placeholder.com/300x200?text=No+Image";
                }
            }
            $row['image_url'] = $img;
            $results[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Search</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- SEARCH FORM -->
<div class="p-6 bg-green-700 text-white text-center">
    <form action="" method="GET" class="flex justify-center gap-2">
        <input type="text" name="query" placeholder="Search products..." 
               value="<?= htmlspecialchars($query) ?>" class="p-2 rounded text-black">
        <button type="submit" class="bg-white text-green-700 px-4 py-2 rounded">Search</button>
    </form>
</div>

<!-- RESULTS -->
<div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
<?php if ($query == ''): ?>
    <p class="col-span-full text-center text-lg text-gray-500">Enter a search term.</p>
<?php elseif (count($results) == 0): ?>
    <p class="col-span-full text-center text-lg text-red-500">No results found.</p>
<?php else: ?>
    <?php foreach ($results as $r): ?>
        <div class="bg-white p-4 shadow rounded">
            <img src="<?= htmlspecialchars($r['image_url']) ?>" class="w-full h-40 object-cover rounded">
            <h2 class="font-bold mt-2"><?= htmlspecialchars($r['name']) ?></h2>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($r['description']) ?></p>
            <p class="text-green-700 font-bold mt-1">₹<?= $r['price'] ?></p>
            <form method="POST" class="mt-2">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($r['name']) ?>">
                <input type="hidden" name="price" value="<?= $r['price'] ?>">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="image" value="<?= htmlspecialchars($r['image_url']) ?>">
                <button type="submit" name="add_to_cart" class="bg-green-700 text-white px-4 py-1 rounded w-full">
                    Add to Cart
                </button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>
