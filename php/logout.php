<?php
/* PARA SAIR DA SESSÃO DE LOGIN */
session_start(); // inicia uma sessão
session_unset(); // exclui a sessão
session_destroy(); // destroi a sessão

# redireciona para a página de login
header('location: ../index.php');