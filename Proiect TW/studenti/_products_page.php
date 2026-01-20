<?php
// Reusable product listing page.
// Variables required: $category, $pageTitle, $pageSubtitle
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Load products for category. Requires column Produse.categorie.
$stmt = $pdo->prepare("SELECT id_produs, nume_produs, pret, categorie, cantitatep FROM Produse WHERE categorie = ? ORDER BY nume_produs");
$stmt->execute([$category]);
$produse = $stmt->fetchAll();

// Stock per product (from Magazie sum, fallback to Produse.cantitatep)
$stoc = [];
foreach ($produse as $p) {
  $pid = (int)$p['id_produs'];
  $stoc[$pid] = fetch_stock($pdo, $pid);
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="intro">
    <h2><?= htmlspecialchars($pageSubtitle) ?></h2>
    <p>Produse afișate direct din baza de date. Stocul este calculat din tabelul <b>Magazie</b>.</p>
  </section>

  <section class="product-grid">
    <?php if (!$produse): ?>
      <p style="grid-column: 1 / -1; text-align:center;">Nu există produse în această categorie încă.</p>
    <?php endif; ?>

    <?php foreach ($produse as $p):
      $pid = (int)$p['id_produs'];
      $isOut = ($stoc[$pid] ?? 0) <= 0;
    ?>
      <div class="product-card">
        <img src="https://via.placeholder.com/400x300?text=<?= urlencode($p['nume_produs'] ?? 'Produs') ?>" alt="<?= htmlspecialchars($p['nume_produs'] ?? 'Produs') ?>">
        <h3><?= htmlspecialchars($p['nume_produs'] ?? '') ?></h3>
        <p><b>Stoc:</b> <?= (int)($stoc[$pid] ?? 0) ?></p>
        <span class="price"><?= number_format((float)$p['pret'], 2) ?> RON</span>

        <?php if ($isOut): ?>
          <button class="btn-buy" disabled style="opacity:.6; cursor:not-allowed;">Stoc epuizat</button>
        <?php else: ?>
          <a class="btn-buy" href="cart_add.php?id=<?= $pid ?>" style="display:inline-block; text-decoration:none;">Adaugă în coș</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
