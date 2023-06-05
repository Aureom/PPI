<?php include($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/components/header.php'); ?>

<body>
      <form method="POST">
          <h2>Entre com seu email e senha</h2>
          <div>
              <label for="email">Email:</label>
              <input name="email" placeholder="Digite seu email aqui" required>
          </div>

          <div>
              <label for="password">Senha:</label>
              <input type="password" name="password" placeholder="Digite sua senha aqui" required>
          </div>

          <div>
              <input type="submit" value="Entrar">
          </div>
      </form>
    
    
</body>

<script>
  const form = document.querySelector('form');

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const email = document.getElementsByName('email')[0].value;
    const password = document.getElementsByName('password')[0].value;

    fetch('../../../trabFinal/src/usecases/login.php', {
      method: 'POST',
      body: new FormData(form),
    })
    .then(response => {
      console.log(response);
      
      if(response.redirected)
        window.location.href = response.url;
    })
    .catch(error => {
      console.error(error);
    });
  });
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/components/footer.php'); ?>
