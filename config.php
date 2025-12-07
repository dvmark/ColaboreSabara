<?php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'colabore';
$db_password = getenv('DB_PASSWORD') ?: 'colabore123';
$db_name = getenv('DB_NAME') ?: 'colabore_sabara';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Erro de conexão com banco de dados: ' . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
