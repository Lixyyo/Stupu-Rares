<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$activePage = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare('SELECT id_us, parola FROM User WHERE username = ?');
  $stmt->execute([$username]);
  $u = $stmt->fetch();

  if (!$u || !password_verify($password, $u['parola'])) {
    $error = 'Username sau parolă greșite.';
  } else {
    $_SESSION['user_id'] = (int)$u['id_us'];
    header('Location: Home.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="login-section">
    <h2>Autentificare</h2>

    <?php if ($error): ?>
      <p class="form-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="login-form" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Log In">
    </form>

    <p class="signup-text">Nu ai cont? <a href="SignUp.php">Creează unul</a>.</p>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
