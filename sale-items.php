<?php include('php/crud.php'); ?>

<?php
// Testa se a sessão loginok não existe
if (!isset($_SESSION['loginok'])) {
  header('location: index.php'); // redireciona para o index novamente
}

# READ
if (isset($_POST['search-saleItems'])) {

  $search = intval(filter_input(INPUT_POST, 'field_search', FILTER_SANITIZE_STRING));

  if ($search) {
    
    # prepara a string
    $stmt = $conexao->prepare($sql = "SELECT * FROM itens_vendidos WHERE cod_item = ?");
    $stmt->bindValue(1, $search);
    $stmt->execute();
    
    # testa se o codigo existe no banco de dados
    if ($stmt->rowCount() > 0) {
      $rs = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $_SESSION['error-search'] = 'Código de item vendido não existe!';
    }

  } else {
    $_SESSION['error-search'] = 'Preecha o campo de pesquisa corretamente!';
  }
}


# READ -> Lê os dados para a alteração
if (isset($_GET['itemEdit'])) {
  # recebe a variável
  $edit = $_GET['itemEdit'];
  $visible = true;

  # preparando a string
  $stmt = $conexao->prepare("SELECT * FROM itens_vendidos WHERE cod_cat = ?");
  $stmt->bindValue(1, $edit);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Convenience - Itens</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="css/modal.css">
  <link rel="stylesheet" href="css/registers.css">
  <link rel="stylesheet" href="css/view-result.css">
</head>
<body>
  <header class="bg">
    <div class="navbar">
      <img src="assets/open-nav.svg" alt="abrir menu" id="open">
      <div class="options">
        <div><a href="sale-items.php">Sale Items</a></div>
        <div><a href="sale-items.php">Categories</a></div>
        <div class="buy" id="open-buy">Buy</div>
      </div>
    </div>
  </header>
  
  <main class="display-box">
    <div class="container">
      <section class="content">
        <div>
          <h4>Welcome</h4>
          <h1>This is your convenience store!</h1>
        </div>

        <!-- sessão de erro -->
        <div class="msg-return">
          <?php if(isset($_SESSION['error-search'])) { ?>
            <div class="msg-error">
              <?php
                # exibe a mensagem de erro da sessão
                echo $_SESSION['error-search'];
                # apaga a sessão
                unset($_SESSION['error-search']);
              ?>
            </div>
          <?php } ?>
        </div>

        <div class="box-buttons">
          <?php if (!$visible) { ?>
            <form class="form-search" action="" method="POST">
              <label class="sr-only" for="txt-search">Pesquise o código do categorias</label>
              <input type="text" name="field_search" id="txt-search" placeholder="Search customer code">
              <button name="search-saleItems" id="img-search" onclick="return search()"></button>
            </form>
          <?php } ?>
          <a href="clients.php">
            <button>Add Clients</button>
            <img src="assets/client.svg" alt="Produtos">
          </a>
          <a href="products.php">
            <button>Add Products</button>
            <img src="assets/buy2.svg" alt="Produtos">
          </a>  
        </div>
      </section>
      <section class="apresentation">

        <!-- sessão de erro -->
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
        
        <!-- Formulario de cadastro ou lista os dados pesquisados -->
          
          <?php if (isset($_POST['search-saleItems']) && ($search) && ($stmt->rowCount() > 0)) { ?>
            <div class="listing">
              <div class="title-result">
                <h3>Search Result</h3>
              </div>
              <div class="result-search">
                <div class="result-list">
                  <ul>
                    <li>Sale number: <?php echo $rs['nun_venda']; ?> </li>  
                    <li>Product code: <?php echo $rs['cod_prod']; ?> </li>
                    <li>Quantity of items: <?php echo $rs['qtde_item_vend']; ?> </li>
                  </ul>  
                </div>
              </div>
              <div class="btn-result">
                <a href="sale-items.php?itemEdit=<?php echo $rs['cod_item']; ?>">Alter</a>
                <a href="php/crud.php?itemDel=<?php echo $rs['cod_item']; ?>">Remove</a>
                <a href="sale-items.php">Back</a>
              </div>
            </div>
          <?php } else { ?>
          <?php if (($visible) && (!$alter)) { ?>
            <div class="alter-title">
              <h3>Altere seus dados</h3>
            </div>
          <?php } ?>
          <form class="form-register" action="php/crud.php" method="POST">
            <input type="hidden" name="coditem" value="<?php if ($visible) echo $rs['cod_item']; ?>">
            <label class="sr-only" for="quantity">Quantity of items sold</label>
            <input type="text" id="quantity" name="quantity" placeholder="Quantity of items sold" value="<?php if ($visible) echo $rs['qtde_item_vend']; ?>">
            <label class="sr-only" for="sale-number">Sale number</label>
            <input type="text" id="sale-number" name="sale-number" placeholder="Sale number" value="<?php if ($visible) echo $rs['num_venda']; ?>">
            <label class="sr-only" for="codprod">Product code</label>
            <input type="text" id="codprod" name="codprod" placeholder="Product code" value="<?php if ($visible) echo $rs['cod_prod']; ?>">
            <div class="btn-register">
            <?php if (!$visible) { ?>
              <button name="register-saleItems">Register</button>
              <button name="cancel">Cancelar</button>
            <?php } else { ?>
              <button name="alter-saleItem" style="background: rgba(62, 235, 105, 0.568)">Confirme</button>
              <button name="back-saleItems" style="background: rgba(72, 166, 228, 0.87); color:#fff;">Cancelar</button>
            <?php } ?>
            </div>
          </form>
        <?php } ?>
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