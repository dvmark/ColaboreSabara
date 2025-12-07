<?php
require 'conexao.php';
session_start();

// VERIFICAR O QUE TEM NA SESSÃO
echo '<pre>';
var_dump($_SESSION['user']);
echo '</pre>';
exit;
?>
