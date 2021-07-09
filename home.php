<?php include('php/conexao.php'); ?>
<?php
# inicia a sessao;
session_start();

// Testa se a sessão loginok não existe
if (!isset($_SESSION['loginok'])) {
  header('location: index.php'); // redireciona para o index novamente
}

# READ
if (isset($_POST['search-sale'])) {

  $search = intval(filter_input(INPUT_POST, 'field_search', FILTER_SANITIZE_STRING));
  if ($search) {
    
    $sql = '
      SELECT * FROM itens_vendidos iv 
      INNER JOIN vendas v ON iv.num_venda=v.num_venda 
      INNER JOIN produtos p ON iv.cod_prod=p.cod_prod 
      INNER JOIN categorias c ON p.codigo_cat=c.cod_cat 
      INNER JOIN clientes cl ON cl.cod_cli=v.cod_cli 
      WHERE cod_item=?
    ';
    
    # prepara a string
    $stmt = $conexao->prepare($sql);
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
  <main>
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
            <label class="sr-only" for="txt-search">Pesquise o código da venda</label>
            <input type="text" name="field_search" id="txt-search" placeholder="Search for sale code">
            <button name="search-sale" id="img-search" onclick="return search()"></button>
          </form>
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
        
        <?php if (isset($_POST['search-sale']) && ($search) && ($stmt->rowCount() > 0)) { ?>
          <div class="listing">
            <div class="title-result">
              <h3>Search Result</h3>
            </div>
            <div class="result-search">
              <div class="result-list">
                <ul>
                  <li>Client name: <?php echo $rs['nome_cli']; ?> </li>
                  <li>Sale number: <?php echo $rs['num_venda']; ?> </li>
                  <li>Product: <?php echo $rs['nome_pro']; ?> </li>
                  <li>Category: <?php echo $rs['nome_cat']; ?> </li>
                  <li>Sale date: <?php echo $rs['data_venda']; ?> </li>
                  <li>Quantity: <?php echo $rs['qtde_item_vend']; ?> </li>
                  <li>Amount: <?php echo ' R$'.$rs['qtde_item_vend']*$rs['preco_pro'];   ?> </li>
                </ul>  
              </div>
            </div>
            <div class="btn-result">
              <a href="home.php">Close</a>
            </div>
          </div>
        <?php } else { ?>
          <img src="assets/buy-ilustration.svg" alt="compras">
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