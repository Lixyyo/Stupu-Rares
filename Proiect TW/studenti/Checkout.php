<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

require_login();
$activePage = 'cos';

$cart = get_cart();
if (!$cart) {
  header('Location: Cos.php');
  exit;
}

$ids = array_keys($cart);
$products = fetch_products_by_ids($pdo, $ids);

$subtotal = 0.0;
foreach ($cart as $pid => $qty) {
  if (!isset($products[$pid])) continue;
  $subtotal += ((float)$products[$pid]['pret']) * (int)$qty;
}

$transport = 15.0;
$total = $subtotal + $transport;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $oras = trim($_POST['oras'] ?? '');
  if ($oras === '') {
    $error = 'Completează orașul.';
  } else {
    try {
      $pdo->beginTransaction();
      $idGrup = generate_order_group_id();
      $userId = (int)$_SESSION['user_id'];

      // Verify stock & decrease + insert items
      foreach ($cart as $pid => $qty) {
        $pid = (int)$pid;
        $qty = (int)$qty;
        if ($pid <= 0 || $qty <= 0) continue;

        // Lock and decrease stock (from Magazie)
        decrease_stock($pdo, $pid, $qty, $oras);

        $ins = $pdo->prepare('INSERT INTO Comanda (id_us, id_produs, oras_c, id_grup, cantitate) VALUES (?,?,?,?,?)');
        $ins->execute([$userId, $pid, $oras, $idGrup, $qty]);
      }

      $pdo->commit();
      set_cart([]);
      $_SESSION['last_order_group'] = $idGrup;
      header('Location: ConfirmareComanda.php?id_grup=' . urlencode((string)$idGrup));
      exit;
    } catch (Exception $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $error = 'Eroare: ' . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Finalizare Comandă | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="checkout-section">
    <h2>Finalizare Comandă</h2>

    <?php if ($error): ?>
      <p class="form-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="checkout-form" method="post">
      <h3>Oraș livrare</h3>
      <label for="oras">Oraș:</label>
      <input id="oras" name="oras" type="text" placeholder="Ex: București" required>

      <h3>Sumar comandă</h3>
      <div class="order-summary">
        <?php foreach ($cart as $pid => $qty): ?>
          <?php if (!isset($products[$pid])) continue; ?>
          <p><?= htmlspecialchars($products[$pid]['nume_produs'] ?? '') ?> × <?= (int)$qty ?> — <b><?= number_format((float)$products[$pid]['pret'] * (int)$qty, 2) ?> lei</b></p>
        <?php endforeach; ?>
        <hr>
        <p>Subtotal: <b><?= number_format($subtotal, 2) ?> lei</b></p>
        <p>Transport: <b><?= number_format($transport, 2) ?> lei</b></p>
        <p>Total: <b><?= number_format($total, 2) ?> lei</b></p>
      </div>

      <button class="checkout-btn" type="submit">Plasează comanda</button>
    </form>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
