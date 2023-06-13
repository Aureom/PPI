<?php
require_once __DIR__ . "/../components/header/header.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../service/UserService.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../database/MySQLConnector.php";

$user = Auth::user();

if ($user === null) {
    header("Location: /trabFinal/src/pages/login.php");
    exit;
}
?>

    <head>
        <title>Mercado Fácil | Editando perfil</title>
        <link rel="stylesheet" href="/trabFinal/src/style/edit-profile.style.css">
    </head>

    <body>
    <div class="edit-profile-container">
        <form method="POST" class="edit-profile-form">
            <h2 class="edit-profile-title">Edite seus dados</h2>
            <div class="form-group">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Digite seu nome aqui"
                       value="<?php echo $user->getName() ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Digite seu email aqui"
                          value="<?php echo $user->getEmail() ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-input" placeholder="Digite seu CPF aqui"
                          value="<?php echo $user->getCpf() ?>" required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Telefone:</label>
                <input type="text" id="phone" name="phone" class="form-input" placeholder="Digite seu telefone aqui"
                            value="<?php echo $user->getPhone() ?>" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="Digite sua senha aqui" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Editar" class="form-button">
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

            fetch('../../../trabFinal/src/usecases/profile.php/update', {
                method: 'POST',
                body: new FormData(form),
            })
                .then(async response => {
                    if (response.ok) {
                        window.location.href = '/trabFinal/src/pages/home.php';
                        return;
                    }

                    const data = await response.json();
                    errorMessage(data.message);
                })
                .catch(error => {
                    errorMessage("Erro ao fazer edit-profile");
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