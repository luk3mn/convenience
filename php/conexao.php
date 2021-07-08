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

  $tbprodutos = '
    CREATE TABLE PRODUTOS (
      cod_prod    INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      codigo_cat  SMALLINT NOT NULL,
      nome_pro	  VARCHAR(30) NOT NULL,
      est_pro	    SMALLINT NOT NULL,
      unid_pro	  CHAR(10) NOT NULL,
      preco_pro	  DECIMAL (8,2) NOT NULL
    )
  ';

  $tbcategorias = '
    CREATE TABLE CATEGORIAS (
      cod_cat	SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      nome_cat	CHAR(25) NOT NULL
    )
  ';
  
  # executa as queries
  $conexao->exec($tbusuarios);
  $conexao->exec($tbclientes);
  $conexao->exec($tbprodutos);
  $conexao->exec($tbcategorias);

} catch (PDOException $e) {
  echo "Code: " . $e->getCode() . " ||| Mensagem: " . $e->getMessage();
}