<?php require('php/login.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/create.css">
</head>
<body>
  <header class="bg">
    <img src="assets/background-sr-create.svg" alt="">
  </header>
  <main>
    <div class="container">
      <section class="area-login">
        <div>
          <h4>Hi there!</h4>
          <h1>Let´s Get Started</h1>
        </div>

        <!-- MENSAGENS DE LOGIN -->
        <div class="msg-return">
          <!-- Menasgem de erro da sessao -->
          <?php if(isset($_SESSION['error'])) { ?>
            <div class="msg-error">
              <?php
                # exibe a mensagem de erro da sessão
                echo $_SESSION['error'];
                # apaga a sessão
                unset($_SESSION['error']);
              ?>
            </div>
          <?php } ?>
          <!-- Menasgem de erro da sessao -->
          <?php if(isset($_SESSION['loginerr'])) { ?>
            <div class="msg-error">
              <?php
                # exibe a mensagem de erro da sessão
                echo $_SESSION['loginerr'];
                # apaga a sessão
                unset($_SESSION['loginerr']);
              ?>
            </div>
          <?php } ?>
          <!-- Menasgem de erro da sessao -->
          <?php if(isset($_SESSION['created'])) { ?>
            <div class="msg-ok">
              <?php
                # exibe a mensagem de erro da sessão
                echo $_SESSION['created'];
                # apaga a sessão
                unset($_SESSION['created']);
              ?>
            </div>
          <?php } ?>
        </div>

        <form class="fields-form" action="php/login" method="POST">
          <div>
            <label class="sr-only" for="user">Entre com usuário</label>
            <input type="text" name="username" id="user" placeholder="Username">
            <img src="assets/user.svg" alt="user image" id="img-user">
          </div>
          <div>
            <label class="sr-only" for="pass">Entre com a senha</label>
            <input type="password" name="password" id="pass" placeholder="Password">
            <img src="assets/key.svg" alt="key image" id="img-pass">
          </div>
          <button name="create">
            Create an Account
          </button>
        </form>
      </section>

      <div class="separator">
        <div></div>
        <div>Or</div>
        <div></div>
      </div>

      <section class="create-account">
        <a href="index.php">
          <button name="create">
            Login
          </button>
        </a>
      </section>
    </div>
  </main>
</body>
</html>