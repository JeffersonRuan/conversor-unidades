<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Página protegida</title>
  <!-- <link rel="stylesheet" href="/css/style.css"> -->
</head>
<body>
  <h1>Bem-vindo, <?php echo $_SESSION['usuario']['nome']; ?>!</h1>
  <p>Você está logado como: <?php echo $_SESSION['usuario']['email']; ?></p>

  <a href="logout.php">Sair</a>
</body>
</html>