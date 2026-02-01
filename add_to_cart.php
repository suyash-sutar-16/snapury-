<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  $type = $_POST['type'];
  $image = $_POST['image'];
  $quantity = 1;

  $item = [
    'id' => $id,
    'name' => $name,
    'price' => $price,
    'quantity' => $quantity,
    'image' => $image, // example: '3.jpg'
    'type' => $type     // example: 'bakery'
  ];

  $_SESSION['cart'][] = $item;

  header("Location: cart.php");
  exit;
}
?>
