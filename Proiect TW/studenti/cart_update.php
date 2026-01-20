<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$cart = get_cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['qty'] ?? [] as $id => $qty) {
    $id = (int)$id;
    $qty = (int)$qty;
    if ($id <= 0) continue;
    if ($qty <= 0) {
      unset($cart[$id]);
    } else {
      $cart[$id] = $qty;
    }
  }
  set_cart($cart);
}

header('Location: Cos.php');
exit;
