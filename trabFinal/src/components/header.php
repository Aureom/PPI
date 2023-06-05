<?php
include '../model/User.php';
session_start();
?>

<!DOCTYPE html>

<head>
  <title>Mercado Fácil</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
  </style>
  <link rel="stylesheet" href="/trabFinal/src/style/global.css">
  <link rel="stylesheet" href="/trabFinal/src/components/header.style.css">
</head>

<header class="header-container">
  <a href="/trabFinal/src/pages/home.php">
    <h1 class="header-text-logo">Mercado Fácil</h1>
  </a>

  <?php
  $user = unserialize($_SESSION['user']);
  $userName = $user->getName();

  if ($user) {
    echo <<<HTML
          <div>
            <h2>Olá, $userName</h2>
            <div>
              <a href="/trabFinal/src/pages/cart.php">Carrinho</a>
              <a href="/trabFinal/src/pages/profile.php">Perfil</a>
              <a href="/trabFinal/src/pages/logout.php">Sair</a>
            </div>
          </div>
        HTML;
  } else {
    echo <<<HTML
          <div>
            <a href="/trabFinal/src/pages/login.php">Ja é de casa? Entre aqui</a>
            <a href="/trabFinal/src/pages/register.php">Cadastre-se aqui, leva só 1 minutinho</a>
          </div>
        HTML;
  }
  ?>
</header>