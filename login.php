<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['name'] = $row['name'];
      header("Location: profile.php");

      exit();
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "User not found.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Snapury</title>
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

  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
      <h2 class="text-2xl font-bold mb-6 text-green-700 text-center">Login to Snapury</h2>
      <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <div>
          <label for="email" class="block text-gray-700 mb-1">Email</label>
          <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2" />
        </div>
        <div>
          <label for="password" class="block text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2" />
        </div>
        <div>
          <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700">Login</button>
        </div>
      </form>
      <p class="text-center text-gray-600 mt-4 text-sm">
        Don't have an account?
        <a href="signup.php" class="text-green-600 hover:underline">Sign up</a>
      </p>
    </div>
  </div>

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
