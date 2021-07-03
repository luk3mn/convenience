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
        <div><a href="sale-items.html">Sale Items</a></div>
        <div><a href="categories.html">Categories</a></div>
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
        <div>
          <a href="clients.php" class="add">
            <button>Add Clients</button>
            <img src="assets/client.svg" alt="cliente">
          </a>
          <a href="products.html" class="add">
            <button>Add Products</button>
            <img src="assets/buy2.svg" alt="produtos">
          </a>
        </div>
      </section>
      <section class="apresentation">
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
          <li><a href="home.html">Home</a></li>
          <li><a href="#">About Us</a></li>
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
      <form action="" method="POST">
        <label class="sr-only" for="num">Sale number</label>
        <input type="text" id="num" name="sale-number" placeholder="Sale Number">
        <label class="sr-only" for="client-code">Client code</label>
        <input type="text" id="client-code" name="client-code" placeholder="Client Code">
        <input type="button" name="register-sale" value="Register">
      </form>
    </div>
  </div>
    <script src="scripts/modal.js"></script>
    <script src="scripts/main.js"></script>
</body>
</html>