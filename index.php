<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Snapury Grocery Store</title>
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

 <!-- searchbar.php or inside any page -->
<form action="search.php" method="GET" class="bg-white shadow p-4 flex justify-center">
  <div class="w-full max-w-2xl flex rounded-full border border-gray-300 overflow-hidden">
    <input type="text" name="query" placeholder="Search products..." class="w-full px-4 py-2 focus:outline-none" required />
    <button type="submit" class="bg-green-600 text-white px-6 hover:bg-green-700">Search</button>
  </div>
</form>

  <!-- Hero Section with Video -->
  <section class="relative flex flex-col items-center justify-center text-center py-32 md:py-40 overflow-hidden">
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
      <source src="WhatsApp Video 2025-07-23 at 5.57.00 PM.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
    <div class="relative z-10 px-4">
      <br>
      <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white drop-shadow-[2px_2px_6px_rgba(0,0,0,0.8)]">SNAPURY</h1>
      <p class="mb-6 text-base md:text-lg text-gray-200 drop-shadow-[1px_1px_4px_rgba(0,0,0,0.7)]">Groceries Delivered Fast, Fresh, and Easy.</p>
      <a href="products.php" class="bg-blue-600 text-white px-6 py-3 rounded-full text-lg hover:bg-blue-700 transition shadow-lg">Shop Now</a>
    </div>
  </section>

  <!-- Featured Categories -->
  <section class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-green-800">Featured Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <a href="fruits.php" class="bg-white rounded-lg shadow p-4 flex flex-col items-center hover:bg-green-50 transition">
        <img src="https://img.icons8.com/color/96/000000/apple.png" alt="Fruits" class="mb-2">
        <span class="font-semibold">Fruits</span>
      </a>
      <a href="vegetables.php" class="bg-white rounded-lg shadow p-4 flex flex-col items-center hover:bg-green-50 transition">
        <img src="https://img.icons8.com/color/96/000000/broccoli.png" alt="Vegetables" class="mb-2">
        <span class="font-semibold">Vegetables</span>
      </a>
      <a href="dairy.php" class="bg-white rounded-lg shadow p-4 flex flex-col items-center hover:bg-green-50 transition">
        <img src="https://img.icons8.com/color/96/000000/milk-bottle.png" alt="Dairy" class="mb-2">
        <span class="font-semibold">Dairy</span>
      </a>
      <a href="bakery.php" class="bg-white rounded-lg shadow p-4 flex flex-col items-center hover:bg-green-50 transition">
        <img src="https://img.icons8.com/color/96/000000/bread.png" alt="Bakery" class="mb-2">
        <span class="font-semibold">Bakery</span>
      </a>
    </div>
  </section>

  <!-- Popular Products -->
  <section class="text-gray-600 body-font">
    <div class="container px-5 py-12 mx-auto">
      <h2 class="text-2xl font-bold mb-6 text-green-800">Popular Products</h2>
      <div class="flex flex-wrap -m-4">
        <!-- Product 1 -->
        <div class="lg:w-1/4 md:w-1/2 p-4 w-full">
          <a class="block relative h-48 rounded overflow-hidden">
            <img alt="Apples" class="object-cover object-center w-full h-full block" src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?auto=format&fit=crop&w=400&q=80">
          </a>
          <div class="mt-4">
            <h3 class="text-green-500 text-xs tracking-widest title-font mb-1">FRUITS</h3>
            <h2 class="text-gray-900 title-font text-lg font-medium">Fresh Apples</h2>
         
          </div>
        </div>
        <!-- Product 2 -->
        <div class="lg:w-1/4 md:w-1/2 p-4 w-full">
          <a class="block relative h-48 rounded overflow-hidden">
            <img alt="Broccoli" class="object-cover object-center w-full h-full block" src="WhatsApp Image 2025-07-23 at 10.01.23 AM.jpeg">
          </a>
          <div class="mt-4">
            <h3 class="text-green-500 text-xs tracking-widest title-font mb-1">VEGETABLES</h3>
            <h2 class="text-gray-900 title-font text-lg font-medium">Organic Broccoli</h2>
          
          </div>
        </div>
        <!-- Product 3 -->
        <div class="lg:w-1/4 md:w-1/2 p-4 w-full">
          <a class="block relative h-48 rounded overflow-hidden">
            <img alt="Milk Pack" class="object-cover object-center w-full h-full block" style="width: 220px; height: 190px;" src="WhatsApp Image 2025-07-23 at 10.01.24 AM.jpeg">
          </a>
          <div class="mt-4">
            <h3 class="text-green-500 text-xs tracking-widest title-font mb-1">DAIRY</h3>
            <h2 class="text-gray-900 title-font text-lg font-medium">Fresh Milk</h2>
            
          </div>
        </div>
        <!-- Product 4 -->
        <div class="lg:w-1/4 md:w-1/2 p-4 w-full">
          <a class="block relative h-48 rounded overflow-hidden">
            <img alt="Bread" class="object-cover object-center w-full h-full block" style="width: 320px; height: 190px;" src="Whole Wheat Bread.webp">
          </a>
          <div class="mt-4">
            <h3 class="text-green-500 text-xs tracking-widest title-font mb-1">BAKERY</h3>
            <h2 class="text-gray-900 title-font text-lg font-medium">Whole Wheat Bread</h2>
         
          </div>
        </div>
      </div>
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
