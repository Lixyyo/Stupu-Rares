<?php
$activePage = 'detalii';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalii - La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="details">
    <h2>Despre noi</h2>
    <p>La Butoiu’ cu Tutun este locul unde pasiunea pentru calitate și tradiție se întâlnesc. Ne dedicăm oferirii celor mai bune produse pentru fumători din România, aducând arome și accesorii atent selecționate.</p>

    <h3>Contact</h3>
    <ul>
      <li><strong>Telefon:</strong> 0723 456 789</li>
      <li><strong>Email:</strong> contact@labutoiucututun.ro</li>
      <li><strong>Adresa:</strong> Str. Tutunului nr. 10, București</li>
    </ul>

    <h3>Program</h3>
    <p>Luni - Vineri: 09:00 - 19:00</p>
    <p>Sâmbătă: 10:00 - 18:00</p>
    <p>Duminică: 10:00 - 16:00</p>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
