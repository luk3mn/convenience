<?php
require('conexao.php');

# Inicia uma sessão
session_start();

$visible = false;
/* ========= CLIENTS ========= */
# CREATE
if (isset($_POST['register-client'])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

  if (($name) && ($address) && ($phone)) {
    
    # motagem da string sql
    $sql = "INSERT INTO clientes (nome_cli, end_cli, fone_cli) VALUES (?, ?, ?)";

    # prepara a string 
    $stmt = $conexao->prepare($sql);

    # atribui os valores dos campos do formulario
    $stmt->bindValue(1, $_POST['name']);
    $stmt->bindValue(2, $_POST['address']);
    $stmt->bindValue(3, $_POST['phone']);

    # valida e executa a insersão no banco da dados
    if ($stmt->execute() > 0) {
      #grava uma mensagem na sessão e redireciona
      $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
      header('location: ../clients.php');
    } else {
      #grava uma mensagem na sessão e redireciona
      $_SESSION['error'] = 'Cliente não cadastrado!';
      header('location: ../clients.php');
    }
    
  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error'] = 'Preecha todos os campos';
    header('location: ../clients.php');
  }
}

# UPADTE
global $alter;
$alter=false;
if (isset($_POST['confirme-alter'])) {
  
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
  $codecli = intval(filter_input(INPUT_POST, 'codcli', FILTER_SANITIZE_STRING));

  if (($name) && ($address) && ($phone)) {
    
    # prepara a conexao com a instrução sql
    $stmt = $conexao->prepare("UPDATE clientes SET nome_cli=?, end_cli=?, fone_cli=? WHERE cod_cli=?");

    # atribui os valores dos campos a string sql
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $address);
    $stmt->bindValue(3, $phone);
    $stmt->bindValue(4, $codecli);

    if ($stmt->execute() > 0) {
      $_SESSION['success'] = 'Dados alterado com sucesso!';
    } else {
      $_SESSION['error'] = 'Ocorreu um erro, tente novamente!';
    }
    $alter=true; // coloca pra true antes de gravar uma sessão
    header('location: ../clients.php');
  } else {
    # grava uma sessão de erro
    $_SESSION['error'] = 'Preencha todos os campos!';
    header("location: ../clients.php?edit=$codecli");
  }
  
}

# DELETE
if (isset($_GET['del'])) {
  # recebe a variável
  $id = $_GET['del'];

  # montando a string sql
  $sql = "DELETE FROM clientes WHERE cod_cli = ?";

  # prepara a string
  $stmt = $conexao->prepare($sql);

  # atribui os valores a string
  $stmt->bindValue(1, $id);

  # testa e executa a query
  if ($stmt->execute() > 0) {
    # grava uma sessão
    $_SESSION['success'] = "Cliente deletado com sucesso!";
    header('location: ../clients.php');
  } else {
    # grava uma sessão
    $_SESSION['error'] = "Cliente não foi deletado!";
    header('location: ../clients.php');
  }
}

if (isset($_POST['back-clients'])) {
  header('location: ../clients.php');
}

/* ========= PRODUCTS ========= */
# CREATE -> Products
if (isset($_POST['register-product'])) {
  $codeCat = intval(filter_input(INPUT_POST, 'category-code', FILTER_SANITIZE_STRING));
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $inventory = intval(filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING));
  $unity = filter_input(INPUT_POST, 'unity', FILTER_SANITIZE_STRING);
  $price = floatval(filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING));

  if (($codeCat) && ($name) && ($inventory) && ($unity) && ($price)) {

    // TESTAR ANTES SE O CODIGO DA CATEGORIA EXISTE NA TABELA DE CATEGORIAS
    $stmt = $conexao->prepare("SELECT * FROM categorias WHERE cod_cat = ?");
    $stmt->bindValue(1, $codeCat);
    $stmt->execute();

    # valida a existencia do registro
    if ($stmt->rowCount() > 0) {

      # prepara a string                                                                    // FAZER INSERT DA TB DE CATEGORIAS           
      $stmt = $conexao->prepare("INSERT INTO produtos (codigo_cat, nome_pro, est_pro, unid_pro, preco_pro) VALUES ((SELECT cod_cat FROM categorias WHERE cod_cat = ?), ?, ?, ? , ?)");

      # atribui os valores dos campos do formulario
      $stmt->bindValue(1, $codeCat);
      $stmt->bindValue(2, $name);
      $stmt->bindValue(3, $inventory);
      $stmt->bindValue(4, $unity);
      $stmt->bindValue(5, $price);

      # valida e executa a insersão no banco da dados
      if ($stmt->execute() > 0) {
        #grava uma mensagem na sessão e redireciona
        $_SESSION['success'] = 'Produto cadastrado com sucesso!';
      } else {
        #grava uma mensagem na sessão e redireciona
        $_SESSION['error'] = 'Aconteceu um erro!';
      }

    } else {
      $_SESSION['error'] = "Código de categoria não existe!";
    }

  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error'] = 'Preecha todos os campos corretamente!';
  }

  # redireciona
  header('location: ../products.php');
}

# UPADTE -> Products
if (isset($_POST['alter-prod'])) {
  
  $codProd = intval(filter_input(INPUT_POST, 'codprod', FILTER_SANITIZE_STRING));
  $codeCat = intval(filter_input(INPUT_POST, 'category-code', FILTER_SANITIZE_STRING));
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $inventory = intval(filter_input(INPUT_POST, 'inventory', FILTER_SANITIZE_STRING));
  $unity = filter_input(INPUT_POST, 'unity', FILTER_SANITIZE_STRING);
  $price = floatval(filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING));

  if (($codeCat) && ($name) && ($inventory) && ($unity) && ($price)) {
    
    # prepara a conexao com a instrução sql
    $stmt = $conexao->prepare("UPDATE produtos SET codigo_cat=?, nome_pro=?, est_pro=?, unid_pro=?, preco_pro=? WHERE cod_prod=?");

    # atribui os valores dos campos a string sql
    $stmt->bindValue(1, $codeCat);
    $stmt->bindValue(2, $name);
    $stmt->bindValue(3, $inventory);
    $stmt->bindValue(4, $unity);
    $stmt->bindValue(5, $price);
    $stmt->bindValue(6, $codProd);

    if ($stmt->execute() > 0) {
      $_SESSION['success'] = 'Dados alterado com sucesso!';
    } else {
      $_SESSION['error'] = 'Ocorreu um erro, tente novamente!';
    }
    $alter=true; // coloca pra true antes de gravar uma sessão
    header('location: ../products.php');
  } else {
    # grava uma sessão de erro
    $_SESSION['error'] = 'Preencha todos os campos!';
    header("location: ../products.php?proEdit=$codProd");
  }
 
}

# DELETE -> Products
if (isset($_GET['proDel'])) {
  # recebe a variável
  $id = $_GET['proDel'];

  # prepara a string
  $stmt = $conexao->prepare("DELETE FROM produtos WHERE cod_prod = ?");
  $stmt->bindValue(1, $id);

  if ($stmt->execute() > 0) {
    # grava uma sessão
    $_SESSION['success'] = "Produto deletado com sucesso!";
  } else {
    # grava uma sessão
    $_SESSION['error'] = "Ocorreu um erro! produto não deletado!";
  }

  # Redireciona para produtos
  header('location: ../products.php');
}

if (isset($_POST['back-product'])) {
  header('location: ../products.php');
}

/* ========= CATEGORIES ========= */
# CREATE -> Category
if (isset($_POST['register-category'])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

  if ($name) {
    # prepara a string 
    $stmt = $conexao->prepare("INSERT INTO categorias (nome_cat) VALUES (?)");

    # atribui os valores dos campos do formulario
    $stmt->bindValue(1, $name);

    # valida e executa a insersão no banco da dados
    if ($stmt->execute() > 0) {
      $_SESSION['success'] = 'Categoria cadastrada com sucesso!';
    } else {
      $_SESSION['error'] = 'Cliente não cadastrado!';
    }
    
  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error'] = 'Preecha todos os campos';
  }

  # redirciona para categorias
  header('location: ../categories.php');
}

# UPDATE -> Categories
if (isset($_POST['alter-cat'])) {
  $codcat = filter_input(INPUT_POST, 'codcat', FILTER_SANITIZE_STRING);
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

  if ($name) {
    # prepara a conexao com a instrução sql
    $stmt = $conexao->prepare("UPDATE categorias SET nome_cat=? WHERE cod_cat=?");
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $codcat);

    if ($stmt->execute() > 0) {
      $_SESSION['success'] = 'Dados alterado com sucesso!';
    } else {
      $_SESSION['error'] = 'Ocorreu um erro, tente novamente!';
    }
    $alter=true; // coloca pra true antes de gravar uma sessão
    header('location: ../categories.php');
  } else {
    # grava uma sessão de erro
    $_SESSION['error'] = 'Preencha todos os campos!';
    header("location: ../categories.php?catEdit=$codcat");
  }
}

# DELETE -> Categories
if (isset($_GET['catDel'])) {
  # recebe a variável
  $id = $_GET['catDel'];

  # prepara a string
  $stmt = $conexao->prepare("DELETE FROM categorias WHERE cod_cat = ?");
  $stmt->bindValue(1, $id);

  if ($stmt->execute() > 0) {
    # grava uma sessão
    $_SESSION['success'] = "Categoria deletado com sucesso!";
  } else {
    # grava uma sessão
    $_SESSION['error'] = "Ocorreu um erro! Tente novamente!";
  }

  # Redireciona para produtos
  header('location: ../categories.php');
}

if (isset($_POST['back-product'])) {
  header('location: ../categories.php');
}

/* ========= BUY ========= */
# CREATE -> Buy
if (isset($_POST['register-sale'])) {
  $codcli = intval(filter_input(INPUT_POST, 'codcli', FILTER_SANITIZE_STRING));
  
  if ($codcli) {

    #valida o codigo do cliente
    $stmt = $conexao->prepare("SELECT cod_cli FROM clientes WHERE cod_cli=?");
    $stmt->bindValue(1, $codcli);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      # prepara a string 
      $stmt = $conexao->prepare("INSERT INTO vendas (cod_cli) VALUES ((SELECT cod_cli FROM clientes WHERE cod_cli=?))");
      $stmt->bindValue(1, $codcli);
  
      # valida e executa a insersão no banco da dados
      if ($stmt->execute() > 0) {
        $_SESSION['success'] = 'Venda cadastrada com sucesso!';
      } else {
        $_SESSION['error'] = 'Ocorreu um erro! Tente novamente';
      }
    } else {
      $_SESSION['error'] = "Código de cliente não existe!";
    }
    
  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error'] = 'Preecha todos os campos corretamente!';
  }

  # redirciona para categorias
  header('location: ../home.php');
}

/* ========= SALE ITEMS ========= */
# CREATE -> Sale items
if (isset($_POST['register-saleItems'])) {
  $quantity = intval(filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING));
  $saleNum = intval(filter_input(INPUT_POST, 'sale-number', FILTER_SANITIZE_STRING));
  $codprod = intval(filter_input(INPUT_POST, 'codprod', FILTER_SANITIZE_STRING));
  
  if (($quantity) && ($saleNum) && ($codprod)) {

    # consulta sql na tabela de vendas
    $stmt = $conexao->prepare("SELECT * FROM vendas WHERE num_venda=?");
    $stmt->bindValue(1, $saleNum);
    $stmt->execute();

    # valida se o registro foi encontrado
    if ($stmt->rowCount() > 0) {

      # consulta sql na tabela de produtos
      $stmt = $conexao->prepare("SELECT * FROM produtos WHERE cod_prod=?");
      $stmt->bindValue(1, $codprod);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $rs=$stmt->fetch(PDO::FETCH_ASSOC);

        # valida a quantida de itens do produto em estoque
        if ($rs['est_pro'] >= $quantity) {
          # prepara a string 
          $stmt = $conexao->prepare("INSERT INTO itens_vendidos (num_venda,cod_prod,qtde_item_vend) VALUES ((SELECT num_venda FROM vendas WHERE num_venda=?), (SELECT cod_prod FROM produtos WHERE cod_prod=?), ?)");
          $stmt->bindValue(1, $saleNum);
          $stmt->bindValue(2, $codprod);
          $stmt->bindValue(3, $quantity);
      
          # valida e executa a insersão no banco da dados
          if ($stmt->execute() > 0) {
            # realizar update no estoque de produtos
            $stmt = $conexao->prepare("UPDATE produtos SET est_pro= (est_pro - ?) WHERE cod_prod=?");
            $stmt->bindValue(1, $quantity);
            $stmt->bindValue(2, $codprod);
            $stmt->execute();     

            $_SESSION['success'] = 'Item vendido!';
          } else {
            $_SESSION['error'] = 'Ocorreu um erro! Tente novamente';
          }
        } else {
          $_SESSION['error'] = 'Produto em estoque é insufuciente!';
        }
      } else {
        $_SESSION['error'] = 'Produto não existe';
      }
    } else {
      $_SESSION['error'] = "Venda não existe!";
    }
  } else {
    # grava uma sesaõ com uma mensagem de erro
    $_SESSION['error'] = 'Preecha todos os campos corretamente!';
  }

  # redirciona para categorias
  header('location: ../sale-items.php');
}

# UPDATE -> Sale Items
if (isset($_POST['alter-saleItem'])) {

  # pega os valore do formulário
  $dbQuantItem = intval(filter_input(INPUT_POST, 'dbquantity', FILTER_SANITIZE_STRING));
  $codItem = filter_input(INPUT_POST, 'coditem', FILTER_SANITIZE_STRING);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
  $saleNum = filter_input(INPUT_POST, 'sale-number', FILTER_SANITIZE_STRING);
  $codProd = filter_input(INPUT_POST, 'codprod', FILTER_SANITIZE_STRING);

  if (($quantity) && ($saleNum) && ($codProd)) {
    
    # consulta sql na tabela de produtos
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE cod_prod=?");
    $stmt->bindValue(1, $codProd);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $rs=$stmt->fetch(PDO::FETCH_ASSOC);
      
      # testa a quantidade passada com a quantidade em estoque
      if ($rs['est_pro'] >= $quantity) {
        # prepara a string sql
        $stmt = $conexao->prepare("UPDATE itens_vendidos SET num_venda=?, cod_prod=?, qtde_item_vend=? WHERE cod_item=?");
        $stmt->bindValue(1, $saleNum);
        $stmt->bindValue(2, $codProd);
        $stmt->bindValue(3, $quantity);
        $stmt->bindValue(4, $codItem);
    
        if ($stmt->execute() > 0) {
          $_SESSION['success'] = 'Item de venda alterado com sucesso!';
        } else {
          $_SESSION['error'] = 'Ocorreu um erro! Tente novamente';
        }
      } else {
        $_SESSION['error'] = 'Produto em estoque é insuficiente!';
      }

    } else {
      $_SESSION['error'] = 'Nenhum registro encontrado!';
    }

    
  } else {
    $_SESSION['error'] = 'Preencha todos os campos corretamente';
  }

  header('location: ../sale-items.php');
}

#DELETE -> Sale Items
if (isset($_GET['itemDel'])) {
  $id = $_GET['itemDel'];

  $stmt = $conexao->prepare("DELETE FROM itens_vendidos WHERE cod_item=?");
  $stmt->bindValue(1, $id);
  
  # testa se executou
  if ($stmt->execute() > 0) {
    $_SESSION['success'] = 'Item deletado!';
  } else {
    $_SESSION['error'] = 'Ocorreu um erro! Tente novamente';
  }

  # redireciona para sale-itmes.php
  header('location: ../sale-items.php');
}

/* CANCELA E VOLTA PRA HOME */
if (isset($_POST['cancel'])) {
  header('location: ../home.php');
}