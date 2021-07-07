<?php include('php/crud.php'); ?>

<?php
// Testa se a sessão loginok não existe
if (!isset($_SESSION['loginok'])) {
  header('location: index.php'); // redireciona para o index novamente
}

# READ
if (isset($_POST['search-client'])) {

  $search = intval(filter_input(INPUT_POST, 'field_search', FILTER_SANITIZE_STRING));

  if ($search) {
    
    # motagem da string sql
    $sql = "SELECT * FROM clientes WHERE cod_cli = ?";

    # prepara a string 
    $stmt = $conexao->prepare($sql);
    
    # atribui os valores a string
    $stmt->bindValue(1, $search);

    # executa a query
    $stmt->execute();
    
    # testa se o codigo existe no banco de dados
    if ($stmt->rowCount() > 0) {
      $rs = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      # grava uma sesaõ com uma mensagem de erro
      $_SESSION['error-search'] = 'Codigo de cliente não existe!';
    }

  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error-search'] = 'Preecha o campo de pesquisa corretamente!';
  }
}


# READ -> Lê os dados para a alteração
if (isset($_GET['edit'])) {
  # recebe a variável
  $edit = $_GET['edit'];
  $visible = true;

  # montando a string para alterar o cliente
  $sql = "SELECT * FROM clientes WHERE cod_cli = ?";

  # preparando a string
  $stmt = $conexao->prepare($sql);

  # atribuindo o valor do cod_cli
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
  <title>Convenience - Clients</title>
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
        <div><a href="sale-items.html">Sale Items</a></div>
        <div><a href="categories.html">Categories</a></div>
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
          <form class="form-search" action="" method="POST">
            <label class="sr-only" for="txt-search">Pesquise o código do cliente</label>
            <input type="text" name="field_search" id="txt-search" placeholder="Search customer code">
            <button name="search-client" id="img-search" onclick="return search()"></button>
          </form>
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
          
          <?php if (isset($_POST['search-client']) && ($search) && ($stmt->rowCount() > 0)) { ?>
            <div class="listing">
              <div class="title-result">
                <h3>Search Result</h3>
              </div>
              <div class="result-search">
                <div class="result-list">
                  <ul>
                    <li>Code: <?php echo $rs['cod_cli']; ?> </li>
                    <li>Name: <?php echo $rs['nome_cli']; ?> </li>
                    <li>Address: <?php echo $rs['end_cli']; ?> </li>
                    <li>Phone: <?php echo $rs['fone_cli']; ?> </li>
                  </ul>  
                </div>
              </div>
              <div class="btn-result">
                <!-- <form class="form-result" action="php/crud.php" method="POST"> -->
                <a href="clients.php?edit=<?php echo $rs['cod_cli']; ?>">Alter</a>
                <a href="clients.php?del=<?php echo $rs['cod_cli']; ?>">Remove</a>
                <a href="clients.php">Back</a>
                <!-- </form> -->
              </div>
            </div>
          <?php } else { ?>
          <?php if (($visible) && ($alter==false)) { ?>
            <div class="alter-title">
              <h3>Altere seus dados</h3>
            </div>
          <?php } ?>
          <form class="form-register" action="php/crud.php" method="POST">
            <label class="sr-only" for="name">Client name</label>
            <input type="text" id="name" name="name" placeholder="Client name" value="<?php if ($visible) echo $rs['nome_cli']; ?>">
            <label class="sr-only" for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Address" value="<?php if ($visible) echo $rs['end_cli']; ?>">
            <label class="sr-only" for="phone">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="Phone" value="<?php if ($visible) echo $rs['fone_cli']; ?>">
            <div class="btn-register">
            <?php if (!$visible) { ?>
              <button name="register-client">Register</button>
              <button name="cancel">Cancelar</button>
            <?php } else { ?>
              <button name="confirme-alter" style="background: rgba(62, 235, 105, 0.568)">Confirme</button>
              <button name="back-clients" style="background: rgba(72, 166, 228, 0.87); color:#fff;">Cancelar</button>
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
            <li><a href="home.html">Home</a></li>
            <li><a href="php/logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="buy-modal hidden">
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