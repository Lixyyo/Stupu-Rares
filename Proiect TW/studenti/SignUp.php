<?php
require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$activePage = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $error = 'Completează username și parola.';
  } else {
    // Check if user exists
    $stmt = $pdo->prepare('SELECT id_us FROM User WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
      $error = 'Username-ul este deja folosit.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins = $pdo->prepare('INSERT INTO User (username, parola) VALUES (?, ?)');
      $ins->execute([$username, $hash]);
      header('Location: Login.php');
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | La Butoiu' cu Tutun</title>
  <link rel="stylesheet" href="Home.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
  <section class="signup-section">
    <h2>Creare cont nou</h2>

    <?php if ($error): ?>
      <p class="form-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="signup-form" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" placeholder="Introduceți username-ul" required>

      <label for="password">Parolă:</label>
      <input type="password" id="password" name="password" placeholder="Introduceți parola" required>

      <input type="submit" value="Creează cont">
    </form>
    <p class="login-text">Aveți deja cont? <a href="Login.php">Autentificați-vă aici</a>.</p>
  </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
