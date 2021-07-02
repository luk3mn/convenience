<?php
require('conexao.php');

# inicia a sessao
session_start();

try {
  
  # CREATE USER

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
      $stmt->bindValue(2, $_POST['password']);

      # executa a consulta
      $stmt->execute();

      # valida o usuario
      if ($stmt->rowCount() > 0) { // se encontrou algum registro
        # cria uma sessão de logado
        $_SESSION['loginok'] = 'login OK';
        header('location: ../home.html');
      } else {
        # cria uma sessão de erro para o login
        $_SESSION['loginerr'] = 'Login Inválido';
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