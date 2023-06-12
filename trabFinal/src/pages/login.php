<?php
require_once __DIR__ . "/../components/header/header.php";

if (Auth::check()) {
    header('Location: /trabFinal/src/pages/home.php');
    exit;
}
?>

    <head>
        <title>Mercado Fácil | Login</title>
        <link rel="stylesheet" href="/trabFinal/src/style/login.style.css">
    </head>

    <body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2 class="login-title">Entre com seu email e senha</h2>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Digite seu email aqui"
                       required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="Digite sua senha aqui" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Entrar" class="form-button">
            </div>
            <div class="form-group">
                <p id="error-message" class="error-message"></p>
            </div>
            <div class="form-group">
                <a href="/trabFinal/src/pages/register.php" class="form-link">Não possui uma conta? Registre-se aqui</a>
            </div>
        </form>
    </div>

    </body>

    <script>
        const form = document.querySelector('form');
        const errorElement = document.getElementById('error-message');

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const email = document.getElementsByName('email')[0].value;
            const password = document.getElementsByName('password')[0].value;

            fetch('../../../trabFinal/src/usecases/login.php', {
                method: 'POST',
                body: new FormData(form),
            })
                .then(async response => {
                    if (!response.ok) {
                        const data = await response.json();
                        errorMessage(data.message);
                        return;
                    }

                    console.log(response)
                    console.log(response.redirected)
                    if (response.redirected)
                        window.location.href = response.url;
                })
                .catch(error => {
                    errorMessage("Erro ao fazer login");
                });
        });

        function errorMessage(message) {
            errorElement.innerText = message;

            errorElement.style.opacity = '1';
            errorElement.style.display = 'block';

            // opacity: 1 -> opacity: 0 transition
            setTimeout(() => {
                errorElement.style.opacity = '0';
            }, 3000);

            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 3500);

        }
    </script>

<?php
include __DIR__ . "/../components/footer/footer.php";
?>