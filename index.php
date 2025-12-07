<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = trim($_POST['cpf'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($cpf) || empty($senha)) {
        $error = 'CPF e senha são obrigatórios.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE cpf = ?');
            $stmt->execute([$cpf]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['user'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'cpf' => $cpf
                ];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'CPF ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $error = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Colabore Sabará</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 style= "text-align: center;">Login - Colabore Sabará</h2>
        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <div style="text-align: center; margin-top: 16px;">
            <p>Não tem conta? <a href="cadastro.php">Criar uma agora</a></p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
