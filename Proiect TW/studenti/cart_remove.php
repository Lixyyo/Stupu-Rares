<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cart = get_cart();
if ($id > 0 && isset($cart[$id])) {
  unset($cart[$id]);
  set_cart($cart);
}

header('Location: Cos.php');
exit;
