<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <div class="max-w-xl mx-auto py-10 px-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">🧾 Checkout</h2>

    <form action="order_success.php" method="POST" class="space-y-4">
      <div>
        <label>Name</label>
        <input type="text" name="name" required class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label>Mobile</label>
        <input type="text" name="mobile" required class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label>Address</label>
        <textarea name="address" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>

      <input type="hidden" name="total" value="<?= $total ?>">

      <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
        Pay ₹<?= number_format($total, 2) ?>
      </button>
    </form>
  </div>

</body>
</html>
