<?php
// Common header (keeps same look across pages)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<header>
  <div class="header-top">
    <h1 class="site-title">La Butoiu' cu Tutun</h1>

    <div class="auth-buttons">
      <?php if ($isLoggedIn): ?>
        <a href="Logout.php" class="auth-btn">Log out</a>
      <?php else: ?>
        <a href="SignUp.php" class="auth-btn">Sign Up</a>
        <a href="Login.php" class="auth-btn">Log In</a>
      <?php endif; ?>
    </div>
  </div>

  <h2 class="slogan">Oriunde în România și la orice oră!</h2>

  <nav class="navbar">
    <a href="Home.php" class="<?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">Acasă</a>
    <a href="Tutun.php" class="<?= ($activePage ?? '') === 'tutun' ? 'active' : '' ?>">Tutun</a>
    <a href="Filtre.php" class="<?= ($activePage ?? '') === 'filtre' ? 'active' : '' ?>">Filtre</a>
    <a href="Foite.php" class="<?= ($activePage ?? '') === 'foite' ? 'active' : '' ?>">Foițe</a>
    <a href="Detalii.php" class="<?= ($activePage ?? '') === 'detalii' ? 'active' : '' ?>">Detalii</a>
    <a href="Cos.php" class="<?= ($activePage ?? '') === 'cos' ? 'active' : '' ?>">Coș</a>
  </nav>
</header>
