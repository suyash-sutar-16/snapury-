<?php
session_start();
include 'db.php'; // MySQL connection file

// Check login user_id (default 0 if not logged in)
$user_id = $_SESSION['user_id'] ?? 0;

// ================= ADD TO CART =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image']);

    // Check if already in DB cart
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Update existing quantity
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + $quantity;
        mysqli_query($conn, "UPDATE cart SET quantity='$new_quantity' WHERE id='{$row['id']}'");
    } else {
        // Insert new product
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, name, price, quantity, image_url)
                             VALUES ('$user_id', '$product_id', '$name', '$price', '$quantity', '$image_url')");
    }
    header("Location: cart.php");
    exit;
}

// ================= REMOVE FROM CART =================
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE id='$remove_id' AND user_id='$user_id'");
    header("Location: cart.php");
    exit;
}

// ================= FETCH CART ITEMS =================
$total = 0;
$items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cart - Snapury</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Navbar -->
<header class="bg-green-700 text-white">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
      <a href="#" class="flex items-center mb-4 md:mb-0">
        <img src="WhatsApp Image 2025-07-23 at 10.01.21 AM (1).jpeg" alt="Snapury Logo" class="w-12 h-12 rounded mr-2" />
        <span class="text-xl font-bold">Snapury</span>
      </a>
      <nav class="md:ml-auto flex flex-wrap items-center text-base space-x-4">
        <a href="index.php" class="hover:text-gray-300">Home</a>
        <a href="products.php" class="hover:text-gray-300">Products</a>
        <a href="cart.php" class="hover:text-gray-300">Cart</a>
        <a href="login.php" class="hover:text-gray-300">Login</a>
      </nav>
    </div>
</header>

<!-- Cart Section -->
<section class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-blue-900">Your Cart</h2>
  <div class="bg-white shadow rounded p-6">
    <?php if (mysqli_num_rows($items) > 0) { ?>
      <?php while ($item = mysqli_fetch_assoc($items)) {
          $subtotal = $item['quantity'] * $item['price'];
          $total += $subtotal;
      ?>
      <div class="flex justify-between items-center mb-4 border-b pb-4">
        <div class="flex items-center space-x-4">
          <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="w-16 h-16 object-cover rounded" alt="">
          <div>
            <h3 class="font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
            <p>Qty: <?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['price'], 2); ?></p>
          </div>
        </div>
        <div class="text-right">
          <p class="font-bold text-blue-700">₹<?php echo number_format($subtotal, 2); ?></p>
          <a href="cart.php?remove=<?php echo $item['id']; ?>" class="text-red-600 text-sm hover:underline">Remove</a>
        </div>
      </div>
      <?php } ?>
      <div class="mt-6 text-right">
        <p class="text-xl font-bold">Total: ₹<?php echo number_format($total, 2); ?></p>
        <a href="checkout.php" class="inline-block bg-green-700 text-white mt-4 px-6 py-2 rounded hover:bg-green-800">
          Proceed to Checkout
        </a>
      </div>
    <?php } else { ?>
      <p class="text-gray-500">Your cart is empty.</p>
    <?php } ?>
  </div>
</section>

</body>
</html>
