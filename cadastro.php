<?php
require_once 'config.php';

$error = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = trim($_POST['cpf'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $conf_senha = trim($_POST['conf_senha'] ?? '');

    if (empty($cpf) || empty($nome) || empty($email) || empty($senha)) {
        $error = 'Todos os campos são obrigatórios.';
    } elseif (strlen($cpf) !== 11) {
        $error = 'CPF inválido (deve ter 11 dígitos).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido.';
    } elseif ($senha !== $conf_senha) {
        $error = 'As senhas não coincidem.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE cpf = ?');
            $stmt->execute([$cpf]);
            if ($stmt->fetch()) {
                $error = 'CPF já cadastrado.';
            } else {
                $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Email já cadastrado.';
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO usuarios (cpf, nome, email, senha) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$cpf, $nome, $email, $senha_hash]);
                    $sucesso = 'Cadastro realizado com sucesso! Redirecionando para login...';
                }
            }
        } catch (PDOException $e) {
            $error = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Colabore Sabará</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Criar Conta</h2>
        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div style="background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
            <script>setTimeout(() => window.location.href = 'index.php', 2000);</script>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="cpf" placeholder="CPF (11 dígitos)" maxlength="11" required>
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="conf_senha" placeholder="Confirmar Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <div style="text-align: center; margin-top: 16px;">
            <p>Já tem conta? <a href="index.php">Fazer login</a></p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
