<?php
$activePage = 'home';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="intro">
    <h2>Bine ați venit!</h2>
    <p>Descoperă tutunul de cea mai bună calitate, livrat rapid oriunde în țară. Produse premium pentru pasionații adevărați.</p>
    <p style="margin-top:14px;">
      <a href="Tutun.php" class="btn-buy" style="display:inline-block; text-decoration:none;">Vezi Tutun</a>
      <a href="Cos.php" class="btn-buy" style="display:inline-block; text-decoration:none; margin-left:10px;">Vezi Coș</a>
    </p>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
