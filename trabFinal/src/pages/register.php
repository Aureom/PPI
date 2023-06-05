<!DOCTYPE html>
<html>

<?php include '../components/header.php'; ?>

  <body>
        <form id="registerForm" action="../../../trabFinal/src/usecases/register.php" method="POST">
            <h2>Crie sua conta:</h2>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="Digite seu email aqui" required>
            </div>

            <div>
                <label for="name">Nome:</label>
                <input type="text" name="name" placeholder="Digite seu nome aqui" required>
            </div>

            <div>
                <label for="password">Senha:</label>
                <input type="password" name="password" placeholder="Digite sua senha aqui" required>
            </div>

            <div>
                <input type="submit" value="Entrar" onclick="register()">
            </div>
        </form>
      
      
  </body>

    <script>
        function registerRequest(form) {
            fetch("../../../trabFinal/src/usecases/register.php", {
                method: "POST",
                body: new FormData(form)
            }).then(function(response) {
                return response.json();
            }).then(function(data) {
                console.log(data);
            });
        }

        function register() {
            const form = document.getElementById("registerForm");

            const response = registerRequest(form);
            console.log(response)
        }

    </script>

  <?php include '../components/footer.php'; ?>

</html>