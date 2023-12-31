<?php
include __DIR__ . "/../components/header/header.php";
?>

    <head>
        <title>Mercado Fácil | Registro</title>
        <link rel="stylesheet" href="/trabFinal/src/style/register.style.css">
    </head>

    <body>
    <div class="register-container">
        <form method="POST" class="register-form">
            <h2 class="register-title">Crie uma nova conta</h2>
            <div class="form-group">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Digite seu nome aqui" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Digite seu email aqui"
                       required>
            </div>
            <div class="form-group">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-input" placeholder="Digite seu CPF aqui"
                       required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Telefone:</label>
                <input type="text" id="phone" name="phone" class="form-input" placeholder="Digite seu telefone aqui"
                       required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="Digite sua senha aqui" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrar" class="form-button">
            </div>
            <div class="form-group">
                <p id="error-message" class="error-message"></p>
            </div>
            <div class="form-group">
                <a href="/trabFinal/src/pages/login.php" class="form-link">Já possui uma conta? Entre aqui</a>
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

            fetch('../../../trabFinal/src/usecases/register.php', {
                method: 'POST',
                body: new FormData(form),
            })
                .then(async response => {
                    if (!response.ok) {
                        const data = await response.json();
                        errorMessage(data.message);
                        return;
                    }

                    if (response.redirected)
                        window.location.href = response.url;
                })
                .catch(error => {
                    errorMessage(error);
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