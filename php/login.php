<?php
require('conexao.php');

# inicia a sessao
session_start();

try {
  
  # CREATE USER
  if (isset($_POST['create'])) {
    
    # pegando os campos do formulario
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    # Valida os campos do formulario
    if (($username) && ($password)) {

      # Criando a string da query e prepara a conexão
      $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE username = ?");

      # atribui os valores do formulario na string
      $stmt->bindValue(1, $_POST['username']);

      # executa a query
      $stmt->execute();

      # Verifica se o username ja existe
      if ($stmt->rowCount() == 0) {
        # controi a string sql
        $sql = "INSERT INTO usuarios (username,password) VALUES (?, ?)";
  
        # prepa a conexao
        $stmt = $conexao->prepare($sql);
  
        #coloca os valores em cada posição da consulta
        $stmt->bindValue(1, $_POST['username']);
        $stmt->bindValue(2, md5($_POST['password']));
  
        # exexuta a query
        $stmt->execute();

        # cria uma sessão de erro para o login
        $_SESSION['created'] = 'Conta criada com sucesso!';
        header('location: ../create.php');
      } else {
        # cria uma sessão de erro para o login
        $_SESSION['loginerr'] = 'Esse usuario já existe!';
        header('location: ../create.php');
      }


    } else {
      # grava uma sessão indicando o erro
      $_SESSION['error'] = 'Preencha todos os campos!';
      header('location: ../create.php');
    }

  }

  # READ USER
  if (isset($_POST['continue'])) {
    
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    # testa se o input está vavio
    if (($username) && ($password)) {
      # faz a consulta no banco de dados
      $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";

      # prepara a conexão com DB
      $stmt = $conexao->prepare($sql);

      # atribui os em cada posição
      $stmt->bindValue(1, $_POST['username']);
      $stmt->bindValue(2, md5($_POST['password']));

      # executa a consulta
      $stmt->execute();

      # valida o usuario
      if ($stmt->rowCount() > 0) { // se encontrou algum registro
        # cria uma sessão de logado
        $_SESSION['loginok'] = 'login OK';
        header('location: ../home.php');
      } else {
        # cria uma sessão de erro para o login
        $_SESSION['loginerr'] = 'Usuário e/ou senha inválidos!';
        header('location: ../index.php');
      }

    } else {
      # cria uma sessão de erro
      $_SESSION['error'] = 'Preencha todos os campos';
      header('location: ../index.php');
    }
  }

} catch (PDOException $e) {
  echo "Code: " . $e->getCode() . " ||| Mensagem: " . $e->getMessage();
}