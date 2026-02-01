<?php
session_start();
include 'db.php';

// Set default user_id if not logged in
$_SESSION['user_id'] = $_SESSION['user_id'] ?? 1; // For testing

// Fetch products
$products = mysqli_query($conn, "SELECT * FROM bakery");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  
  <header class="bg-green-700 text-white">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
      <a href="#" class="flex items-center mb-4 md:mb-0">
        <img src="WhatsApp Image 2025-07-23 at 10.01.21 AM (1).jpeg" alt="Snapury Logo" class="w-12 h-12 rounded mr-2" />
        <span class="text-xl font-bold">Snapury </span>
      </a>
      <nav class="md:ml-auto flex flex-wrap items-center text-base space-x-4">
        <a href="index.php" class="hover:text-gray-300">Home</a>
        <a href="products.php" class="hover:text-gray-300">Products</a>
        <a href="cart.php" class="hover:text-gray-300">Cart</a>
        <a href="login.php" class="hover:text-gray-300">Login</a>
      </nav>
    </div>
  </header>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Bakery Products</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while($p = mysqli_fetch_assoc($products)) { ?>
        <div class="bg-white shadow p-4 rounded">
            <img src="<?php echo htmlspecialchars($p['image_url']); ?>" class="w-full h-40 object-cover rounded mb-4">
            <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($p['name']); ?></h2>
            <p class="text-gray-700 mb-2">₹<?php echo number_format($p['price'], 2); ?></p>
            <form method="POST" action="cart.php">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($p['name']); ?>">
                <input type="hidden" name="price" value="<?php echo $p['price']; ?>">
                <input type="hidden" name="image" value="<?php echo htmlspecialchars($p['image_url']); ?>">
                <input type="number" name="quantity" value="1" min="1" class="border p-1 w-16 mb-2">
                <button type="submit" name="add_to_cart" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800">
                    Add to Cart
                </button>
            </form>
        </div>
        <?php } ?>
    </div>
</div>
<section class="bg-red-50 py-12 px-6">
  <h3 class="text-3xl font-bold text-center mb-8">Explore Categories</h3>
  <div class="flex flex-wrap justify-center gap-6">
    <a href="bakery.php" class="bg-white px-6 py-4 rounded-lg shadow-md hover:bg-red-100 cursor-pointer">Bakery Section</a>
    <a href="dairy.php" class="bg-white px-6 py-4 rounded-lg shadow-md hover:bg-red-100 cursor-pointer">Dairy Section</a>
    <a href="vegetables.php" class="bg-white px-6 py-4 rounded-lg shadow-md hover:bg-red-100 cursor-pointer">Vegetables Section</a>
    <a href="fruits.php" class="bg-white px-6 py-4 rounded-lg shadow-md hover:bg-red-100 cursor-pointer">Fruits Section</a>
    <a href="products.php" class="bg-white px-6 py-4 rounded-lg shadow-md hover:bg-red-100 cursor-pointer">Grocery Section</a>
  </div>
</section>

  <!-- Footer -->
  <footer class="bg-green-700 text-white">
    <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
      <a class="flex title-font font-medium items-center md:justify-start justify-center text-white">
        <img src="WhatsApp Image 2025-07-23 at 10.01.21 AM (1).jpeg" alt="Snapury Logo" class="w-12 h-12 rounded mr-2" />
        <span class="text-xl font-bold">Snapury </span>
      </a>
      <p class="text-sm text-gray-200 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">
        © 2025 Snapury Grocery — <a href="mailto:snapury@grocery.in" class="text-white ml-1">snapury@grocery.in</a>
      </p>
    </div>
  </footer>

</body>
</html>
