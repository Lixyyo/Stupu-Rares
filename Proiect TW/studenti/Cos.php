<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$activePage = 'cos';
$cart = get_cart();
$ids = array_keys($cart);
$products = fetch_products_by_ids($pdo, $ids);

$total = 0.0;
foreach ($cart as $pid => $qty) {
  if (!isset($products[$pid])) continue;
  $total += ((float)$products[$pid]['pret']) * (int)$qty;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coș | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="cart-section">
    <h2>Coșul tău de cumpărături</h2>

    <?php if (!$cart): ?>
      <p style="text-align:center;">Coșul este gol.</p>
      <div class="cart-actions">
        <a href="Tutun.php" class="continue-btn">Începe cumpărăturile</a>
      </div>
    <?php else: ?>
      <form method="post" action="cart_update.php">
        <table class="cart-table">
          <thead>
            <tr>
              <th>Produs</th>
              <th>Cantitate</th>
              <th>Preț</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $pid => $qty): ?>
              <?php if (!isset($products[$pid])) continue; ?>
              <tr>
                <td><?= htmlspecialchars($products[$pid]['nume_produs'] ?? '') ?></td>
                <td>
                  <input type="number" name="qty[<?= (int)$pid ?>]" value="<?= (int)$qty ?>" min="1">
                </td>
                <td><?= number_format((float)$products[$pid]['pret'], 2) ?> RON</td>
                <td><?= number_format((float)$products[$pid]['pret'] * (int)$qty, 2) ?> RON</td>
                <td><a href="cart_remove.php?id=<?= (int)$pid ?>" class="cart-remove">Șterge</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
              <td><strong><?= number_format($total, 2) ?> RON</strong></td>
              <td></td>
            </tr>
          </tfoot>
        </table>

        <div class="cart-actions">
          <button type="submit" class="continue-btn">Actualizează coșul</button>
          <a href="Checkout.php" class="checkout-btn">Finalizează comanda</a>
        </div>
      </form>
    <?php endif; ?>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
