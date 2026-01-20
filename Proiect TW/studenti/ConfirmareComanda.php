<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$activePage = '';
$idGrup = $_GET['id_grup'] ?? ($_SESSION['last_order_group'] ?? '');
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmare Comandă | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="confirmare-section">
    <div class="confirmare-card">
      <h2>Comanda a fost plasată cu succes! 🎉</h2>
      <p>Vă mulțumim pentru încredere!</p>

      <?php if ($idGrup !== ''): ?>
        <div class="order-details-box">
          <p><b>ID Comandă:</b> #<?= htmlspecialchars((string)$idGrup) ?></p>
          <p><b>Status:</b> În procesare</p>
          <p><b>Livrare estimată:</b> 1–3 zile lucrătoare</p>
        </div>
      <?php endif; ?>

      <div class="buttons-area">
        <a href="Home.php" class="btn-primary">Înapoi la pagina principală</a>
        <a href="Tutun.php" class="btn-secondary">Continuă cumpărăturile</a>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
