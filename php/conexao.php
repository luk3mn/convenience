<?php
try {
  # Realiza a conexão com o banco de dados
  $conexao = new PDO("mysql:host=localhost;dbname=convenience","root","");  

  # criação das tabelas do sistema
  $tbusuarios = '
    CREATE TABLE usuarios (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(15) NOT NULL,
      password VARCHAR(32) NOT NULL
    )
  ';

  $tbclientes = '
    CREATE TABLE CLIENTES (
      cod_cli	INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      nome_cli	VARCHAR(50),
      end_cli	VARCHAR(50),
      fone_cli	CHAR(15)
    )
  ';
  
  # executa as queries
  $conexao->exec($tbusuarios);
  $conexao->exec($tbclientes);

} catch (PDOException $e) {
  echo "Code: " . $e->getCode() . " ||| Mensagem: " . $e->getMessage();
}