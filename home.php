<?php
session_start(); // inicia uma sessão

// Testa se a sessão loginok não existe
if (!isset($_SESSION['loginok'])) {
  header('location: index.php'); // redireciona para o index novamente
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Convenience - Home</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="css/modal.css">
</head>
<body>
  <header class="bg">
    <div class="navbar">
      <img src="assets/open-nav.svg" alt="abrir menu" id="open">
      <div class="options">
        <div><a href="sale-items.php">Sale Items</a></div>
        <div><a href="categories.php">Categories</a></div>
        <div class="buy" id="open-buy">Buy</div>
      </div>
    </div>
  </header>
  <main>
    <div class="container">
      <section class="content">
        <div>
          <h4>Welcome</h4>
          <h1>This is your convenience store!</h1>
        </div>
        <div class="box-buttons">
          <a href="clients.php" class="add">
            <button>Add Clients</button>
            <img src="assets/client.svg" alt="cliente">
          </a>
          <a href="products.php" class="add">
            <button>Add Products</button>
            <img src="assets/buy2.svg" alt="produtos">
          </a>
        </div>
      </section>
      <section class="apresentation">
        <div class="msg-return">
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

          <?php if(isset($_SESSION['success'])) { ?>
            <div class="msg-ok">
              <?php
                # exibe a mensagem de erro da sessão
                echo $_SESSION['success'];
                # apaga a sessão
                unset($_SESSION['success']);
              ?>
            </div>
          <?php } ?>
        </div>

        <img src="assets/buy-ilustration.svg" alt="">
      </section>  
    </div>
  </main>
  <div class="box-modal">
    <div class="head-modal">
      <img src="assets/close.svg" alt="close" id="close">
    </div>
    <div class="content-modal">
      <div>
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="php/logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="modal hidden">
    <div class="head-modal">
      <img src="assets/close.svg" alt="close" id="close-buy">
    </div>
    <div class="content-modal sale-info">
      <form class="form-modal" action="php/crud.php" method="POST">
        <label class="sr-only" for="client-code">Client code</label>
        <input type="text" name="codcli" placeholder="Client Code">
        <button name="register-sale">Register</button>
      </form>
    </div>
  </div>
  <script src="scripts/modal.js"></script>
</body>
</html>