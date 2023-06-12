<?php
include __DIR__ . "/../../model/User.php";
include __DIR__ . "/../../security/Auth.php";
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
    <link rel="stylesheet" href="/trabFinal/src/components/header/header.style.css">
    <link rel="stylesheet" href="/trabFinal/src/components/footer/footer.style.css">
</head>

<header class="header-container">
    <div class="header-logo-container">
        <img class="header-logo"
             src="https://cdn.discordapp.com/attachments/1115267711753277524/1117584648923512894/DALL_E_2023-06-11_19.34.02_-_Create_a_marketplace_logo__with_the_letter__M__with_a_transparent_backgroud__modern_logo__darkmode__attractive_design__no_additional_text-removebg-preview.png"
             alt="Logo">
        <a href="/trabFinal/src/pages/home.php">
            <h1 class="header-text-logo">MERCADO FÁCIL</h1>
        </a>
    </div>

    <?php
    if (Auth::check()) {
        $user = Auth::user();
        $userName = $user->getName();

        echo <<<HTML
          <div class="header-user-info">
            <h2>Olá, $userName</h2>
            <div class="header-user-options">
              <a href="/trabFinal/src/pages/profile.php">Perfil</a>
              <a href="/trabFinal/src/pages/createProduct.php">Cadastrar produto</a>
              <a href="/trabFinal/src/usecases/logout.php">Sair</a>
            </div>
          </div>
        HTML;
    } else {
        echo <<<HTML
          <div class="header-user-info">
            <div>
                <a href="/trabFinal/src/pages/login.php">
                    <button class="sign-in-button">Entrar</button>
                </a>
                <a href="/trabFinal/src/pages/register.php">
                    <button class="sing-up-button">Cadastrar</button>
                </a>
            </div>
          </div>
        HTML;
    }
    ?>
</header>