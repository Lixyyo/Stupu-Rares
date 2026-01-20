<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: Home.php');
  exit;
}

$cart = get_cart();
$cart[$id] = ($cart[$id] ?? 0) + 1;
set_cart($cart);

header('Location: Cos.php');
exit;
