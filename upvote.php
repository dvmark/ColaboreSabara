<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['ocorrencia_id'])) {
    $ocorrencia_id = $_POST['ocorrencia_id'];
    $usuario_id = $_SESSION['user']['id'];
    
    try {
        // Verificar se já votou
        $stmt_check = $pdo->prepare("SELECT id FROM votos WHERE usuario_id = ? AND ocorrencia_id = ?");
        $stmt_check->execute([$usuario_id, $ocorrencia_id]);
        $ja_votou = $stmt_check->fetch();
        
        if ($ja_votou) {
            // Já votou - remover voto
            $stmt_remove_voto = $pdo->prepare("DELETE FROM votos WHERE usuario_id = ? AND ocorrencia_id = ?");
            $stmt_remove_voto->execute([$usuario_id, $ocorrencia_id]);
            
            // Decrementar curtidas
            $stmt_dec = $pdo->prepare("UPDATE ocorrencias SET curtidas = curtidas - 1 WHERE id = ?");
            $stmt_dec->execute([$ocorrencia_id]);
        } else {
            // Ainda não votou - adicionar voto
            $stmt_add_voto = $pdo->prepare("INSERT INTO votos (usuario_id, ocorrencia_id) VALUES (?, ?)");
            $stmt_add_voto->execute([$usuario_id, $ocorrencia_id]);
            
            // Incrementar curtidas
            $stmt_inc = $pdo->prepare("UPDATE ocorrencias SET curtidas = curtidas + 1 WHERE id = ?");
            $stmt_inc->execute([$ocorrencia_id]);
        }
        
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>