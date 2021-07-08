<?php include('php/crud.php'); ?>

<?php
// Testa se a sessão loginok não existe
if (!isset($_SESSION['loginok'])) {
  header('location: index.php'); // redireciona para o index novamente
}

# READ
if (isset($_POST['search-product'])) {

  $search = intval(filter_input(INPUT_POST, 'field_search', FILTER_SANITIZE_STRING));

  if ($search) {
    
    # prepara a string
    $stmt = $conexao->prepare($sql = "SELECT * FROM produtos WHERE cod_prod = ?");
    $stmt->bindValue(1, $search);
    $stmt->execute();
    
    # testa se o codigo existe no banco de dados
    if ($stmt->rowCount() > 0) {
      $rs = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $_SESSION['error-search'] = 'Codigo de produto não existe!';
    }

  } else {
    $_SESSION['error-search'] = 'Preecha o campo de pesquisa corretamente!';
  }
}


# READ -> Lê os dados para a alteração
if (isset($_GET['proEdit'])) {
  # recebe a variável
  $edit = $_GET['proEdit'];
  $visible = true;

  # preparando a string
  $stmt = $conexao->prepare("SELECT * FROM produtos WHERE cod_prod = ?");
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
  <title>Convenience - Products</title>
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
        <div><a href="categories.php">Categories</a></div>
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
              <label class="sr-only" for="txt-search">Pesquise o código do produtos</label>
              <input type="text" name="field_search" id="txt-search" placeholder="Search customer code">
              <button name="search-product" id="img-search" onclick="return search()"></button>
            </form>
          <?php } ?>
          <a href="clients.php">
            <button>Add Clients</button>
            <img src="assets/client.svg" alt="Produtos">
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
          
          <?php if (isset($_POST['search-product']) && ($search) && ($stmt->rowCount() > 0)) { ?>
            <div class="listing">
              <div class="title-result">
                <h3>Search Result</h3>
              </div>
              <div class="result-search">
                <div class="result-list">
                  <ul>
                    <li>Code: <?php echo $rs['cod_prod']; ?> </li>
                    <li>Code category: <?php echo $rs['codigo_cat']; ?> </li>
                    <li>Name: <?php echo $rs['nome_pro']; ?> </li>
                    <li>Inventory: <?php echo $rs['est_pro']; ?> </li>
                    <li>Unity: <?php echo $rs['unid_pro']; ?> </li>
                    <li>Price: <?php echo ' R$'.$rs['preco_pro']; ?> </li>
                  </ul>  
                </div>
              </div>
              <div class="btn-result">
                <a href="products.php?proEdit=<?php echo $rs['cod_prod']; ?>">Alter</a>
                <a href="php/crud.php?proDel=<?php echo $rs['cod_prod']; ?>">Remove</a>
                <a href="products.php">Back</a>
              </div>
            </div>
          <?php } else { ?>
          <?php if (($visible) && (!$alter)) { ?>
            <div class="alter-title">
              <h3>Altere seus dados</h3>
            </div>
          <?php } ?>
          <form class="form-register" action="php/crud.php" method="POST">
            <input type="hidden" name="codprod" value="<?php if ($visible) echo $rs['cod_prod']; ?>">
            <label class="sr-only" for="category">Category code</label>
            <input type="text" name="category-code" placeholder="Category code" value="<?php if ($visible) echo $rs['codigo_cat']; ?>">
            <label class="sr-only" for="name">Product name</label>
            <input type="text" name="name" placeholder="Product name" value="<?php if ($visible) echo $rs['nome_pro']; ?>">
            <label class="sr-only" for="inventory">Product inventory</label>
            <input type="text" name="inventory" placeholder="Product inventory" value="<?php if ($visible) echo $rs['est_pro']; ?>">
            <label class="sr-only" for="unity">Unity</label>
            <input type="text" name="unity" placeholder="Unity" value="<?php if ($visible) echo $rs['unid_pro']; ?>">
            <label class="sr-only" for="price">Price</label>
            <input type="text" name="price" placeholder="Price" value="<?php if ($visible) echo $rs['preco_pro']; ?>">
            <div class="btn-register">
            <?php if (!$visible) { ?>
              <button name="register-product">Register</button>
              <button name="cancel">Cancelar</button>
            <?php } else { ?>
              <button name="alter-prod" style="background: rgba(62, 235, 105, 0.568)">Confirme</button>
              <button name="back-product" style="background: rgba(72, 166, 228, 0.87); color:#fff;">Cancelar</button>
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
</body>
</html>