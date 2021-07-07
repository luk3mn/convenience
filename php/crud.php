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
$false=false;
if (isset($_POST['confirme-alter'])) {
  
  $alter=true; // coloca pra true antes de gravar uma sessão
  header('location: ../clients.php');
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

/* ========= CATEGORIES ========= */

/* ========= BUY ========= */

/* ========= SALE ITEMS ========= */

/* CANCELA E VOLTA PRA HOME */
if (isset($_POST['cancel'])) {
  header('location: ../home.php');
}