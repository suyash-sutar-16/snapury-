<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $location = $_POST['location'];
  $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

  $imagePath = $user['profile_image'] ?? '';
  if (!empty($_FILES['profile_image']['name'])) {
    $targetDir = "uploads/";
    $imagePath = $targetDir . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $imagePath);
  }

  $update = "UPDATE users SET name='$name', email='$email', mobile='$mobile', location='$location', password='$password', profile_image='$imagePath' WHERE id=$user_id";
  mysqli_query($conn, $update);
  header("Location: profile.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Snapury - Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-white min-h-screen flex flex-col">

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
        <a href="logout.php" class="text-red-500 hover:underline">Logout</a>

      </nav>
    </div>
  </header>
  <main class="flex-1 flex items-center justify-center px-4 py-8">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-2xl border border-gray-100">
      <h2 class="text-2xl font-semibold text-green-700 text-center mb-6">Edit Your Profile</h2>
      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <!-- Profile Image -->
        <div class="flex flex-col items-center">
          <div class="relative">
            <img src="<?= $user['profile_image'] ?? 'uploads/default.jpg' ?>" class="w-32 h-32 rounded-full object-cover border-4 border-green-200 shadow-lg hover:shadow-2xl transition" id="preview">
            <label class="absolute bottom-0 right-0 bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded-full text-sm cursor-pointer shadow-md">
              <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)" class="hidden">
              Edit
            </label>
          </div>
        </div>

        <!-- Inputs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="text" name="name" value="<?= $user['name'] ?>" placeholder="Name" class="border border-gray-300 px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
          <input type="email" name="email" value="<?= $user['email'] ?>" placeholder="Email" class="border border-gray-300 px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
          <input type="text" name="mobile" value="<?= $user['mobile'] ?>" placeholder="Mobile" class="border border-gray-300 px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
          <input type="text" name="location" value="<?= $user['location'] ?? '' ?>" placeholder="Location" class="border border-gray-300 px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
          <input type="password" name="password" placeholder="New Password (leave blank to keep current)" class="md:col-span-2 border border-gray-300 px-4 py-2 rounded focus:ring-2 focus:ring-green-500">
        </div>

        <div class="text-center">
          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-full transition shadow-lg hover:shadow-xl">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById('preview').src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
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
