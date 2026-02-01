<?php
// --- PHP Code to Handle Form Submission ---
$conn = mysqli_connect("localhost", "root", "", "snapury_suyash");
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $category = $_POST['category'];

  $imagePath = '';
  if (!empty($_FILES["image"]["name"])) {
    $targetDir = "uploads/";
    $imagePath = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
  }

  $sql = "INSERT INTO $category (name, description, price, image_url) 
          VALUES ('$name', '$description', '$price', '$imagePath')";
  mysqli_query($conn, $sql);
  echo "<script>alert('Product added to $category!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Product - Snapury</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 py-10">
  <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-xl">
    <h2 class="text-3xl font-bold mb-6 text-center text-green-700">Upload Product</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <select name="category" required class="w-full border px-4 py-2 rounded">
        <option value="">-- Select Category --</option>
        <option value="bakery">Bakery</option>
        <option value="dairy">Dairy</option>
        <option value="fruits">Fruits</option>
        <option value="vegetables">Vegetables</option>
        <option value="products">Groceries</option>
      </select>
      <input type="text" name="name" placeholder="Product Name" required class="w-full border px-4 py-2 rounded">
      <textarea name="description" placeholder="Description" required class="w-full border px-4 py-2 rounded"></textarea>
      <input type="number" name="price" placeholder="Price" required class="w-full border px-4 py-2 rounded">
      <input type="file" name="image" accept="image/*" required class="w-full border px-4 py-2 rounded">
      <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800 w-full">
        Upload Product
      </button>
    </form>
  </div>
</body>
</html>
