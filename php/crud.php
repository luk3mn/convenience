<?php
require('conexao.php');

# Inicia uma sessão
session_start();

/* ========= CLIENTS ========= */
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

# REMOVE
if (isset($_POST['remove-cli'])) {
  # montando a string sql
  $sql = "DELETE FROM clientes WHERE cod_cli = ?";

  # prepara a string
  $stmt = $conexao->prepare($sql);

  # atribui os valores a string
  // $stmt->bindValue(1, )
  // echo $del;
}

// if (isset($_POST['search-client'])) {

//   $search = intval(filter_input(INPUT_POST, 'field_search', FILTER_SANITIZE_STRING));

//   if ($search) {
    
//     # motagem da string sql
//     $sql = "SELECT * FROM clientes WHERE cod_cli = ?";

//     # prepara a string 
//     $stmt = $conexao->prepare($sql);
    
//     # atribui os valores a string
//     $stmt->bindValue(1, $search);

//     # executa a query
//     $stmt->execute();
    
//     # testa se o codigo existe no banco de dados
//     if ($stmt->rowCount() > 0) {
//     $rs = $stmt->fetch(PDO::FETCH_ASSOC);
      
//       // $_SESSION['rs'] = $rs;

//       // echo $rs['cod_cli'] . "<br>" .  $rs['nome_cli'] . "<br>" . $rs['end_cli'];
//       // header('location: ../read-clients.php');
//       // print_r($rs);
      

//       // foreach ($rs as $assoc) {
//       //   echo $assoc;
//       // }
//     } else {
//       # grava uma sesaõ com uma mensagem de erro
//       $_SESSION['error-search'] = 'Codigo de cliente não existe!';
//       header('location: ../clients.php');  
//     }

//   } else {
//     # grava uma sesaõ com uma mensagem de erro
//     $_SESSION['error-search'] = 'Preecha o campo de pesquisa corretamente!';
//     header('location: ../clients.php');
//   }

//   header('location: ../read-clients.php');
// }

if (isset($_POST['back-clients'])) {
  header('location: ../clients.php');
}

/* ========= PRODUCTS ========= */

/* ========= CATEGORIES ========= */

/* ========= BUY ========= */

/* ========= SALE ITEMS ========= */

/* CANCELA E VOLTA PRA HOME */
if (isset($_POST['cancel'])) {
  header('location: ../home.php');
}