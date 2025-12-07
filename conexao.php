<?php

$host = 'db';
$dbname = 'colabore_sabara';
$user = 'colabore';
$password = 'colabore123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

?>
