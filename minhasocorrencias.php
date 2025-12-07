<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['user'];
$usuario_id = $usuario['id'];
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar_id'])) {
    $deletar_id = $_POST['deletar_id'];
    $stmt_check = $pdo->prepare("SELECT usuario_id FROM ocorrencias WHERE id = ?");
    $stmt_check->execute([$deletar_id]);
    $ocor_check = $stmt_check->fetch();
    
    if ($ocor_check && $ocor_check['usuario_id'] == $usuario_id) {
        try {
            $stmt_delete = $pdo->prepare("DELETE FROM ocorrencias WHERE id = ?");
            $stmt_delete->execute([$deletar_id]);
            $sucesso = "✅ Ocorrência deletada com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao deletar: " . $e->getMessage();
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE usuario_id = ? ORDER BY criado_em DESC");
$stmt->execute([$usuario_id]);
$minhas_ocorrencias = $stmt->fetchAll();
?>
<?php include 'sidebar.php'; ?>

    <h1>📋 Minhas Ocorrências</h1>

    <?php if ($erro): ?>
        <div style="color: #c3212b; background: #f8d7da; padding: 12px; border-radius: 4px; margin-bottom: 15px;">⚠️ <?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div style="color: #155724; background: #d4edda; padding: 12px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <a href="ocorrencias.php" style="text-decoration: none; font-weight: bold; color: #0b6e4f; background: #f0f0f0; padding: 8px 16px; border-radius: 4px; display: inline-block;">📍 Reportar Nova Ocorrência</a>
    </div>

    <?php if (empty($minhas_ocorrencias)): ?>
        <div style="text-align: center; color: #999; padding: 30px; background: white; border-radius: 8px;">
            <p>Você ainda não reportou nenhuma ocorrência.</p>
            <p><a href="ocorrencias.php" style="color: #0b6e4f; font-weight: bold;">→ Reportar uma agora</a></p>
        </div>
    <?php else: ?>
        <?php foreach ($minhas_ocorrencias as $ocor): ?>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #007bff;">
                <span style="display: inline-block; padding: 8px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #fff3cd; color: #856404; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($ocor['categoria']); ?>
                </span>
                
                <?php if ($ocor['foto_url']): ?>
                    <img src="<?= htmlspecialchars($ocor['foto_url']) ?>" alt="Foto" style="max-width: 200px; border-radius: 4px; margin-bottom: 10px; display: block;">
                <?php endif; ?>
                
                <p><strong>📍 Local:</strong> <?= htmlspecialchars($ocor['endereco'] ?? 'Não informado') ?></p>
                <p><strong>Coordenadas:</strong> <?= htmlspecialchars($ocor['latitude']) ?>, <?= htmlspecialchars($ocor['longitude']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($ocor['descricao']) ?></p>
                <p><strong>👍 Útil:</strong> <?= $ocor['curtidas'] ?> pessoas acharam útil</p>
                <small style="color: #999; display: block; margin-top: 5px;">📅 <?= date('d/m/Y H:i', strtotime($ocor['criado_em'])) ?></small>
                
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="deletar_id" value="<?= $ocor['id'] ?>">
                    <button type="submit" onclick="return confirm('Tem certeza que deseja deletar esta ocorrência?')" style="background: #dc3545; color: white; padding: 6px 12px; font-size: 12px; border: none; border-radius: 4px; cursor: pointer;">🗑️ Deletar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>