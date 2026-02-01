<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $mobile = $_POST['mobile'];
  $address = $_POST['address'];
  $total = $_POST['total'];

  // Here you can store order to DB if needed

  // Clear cart
  unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">

  <div class="bg-white p-8 rounded shadow text-center">
    <h1 class="text-3xl font-bold text-green-700 mb-4">🎉 Order Confirmed!</h1>
    <p class="text-lg font-medium text-gray-800">Thank you, <?= htmlspecialchars($name) ?>!</p>
    <p class="mt-2 text-gray-700">Your order of <strong>₹<?= number_format($total, 2) ?></strong> has been placed successfully.</p>
    <p class="mt-2">📍 Delivery Address: <?= htmlspecialchars($address) ?></p>

    <a href="index.php" class="mt-6 inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Back to Home</a>
  </div>

</body>
</html>
