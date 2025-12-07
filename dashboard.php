<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$usuario = $_SESSION['user'];

try {
    $stmt = $pdo->prepare('SELECT p.id, p.titulo, p.conteudo, p.curtidas, p.criado_em, u.nome FROM posts p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.criado_em DESC');
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

try {
    $stmt_ocor = $pdo->prepare("SELECT * FROM ocorrencias ORDER BY curtidas DESC LIMIT 10");
    $stmt_ocor->execute();
    $ocorrencias = $stmt_ocor->fetchAll();
} catch (PDOException $e) {
    $ocorrencias = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Colabore</title>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2>Bem-vindo! 👋</h2>
    </div>
    
    <h3>Posts Recentes</h3>
    <?php if (empty($posts)): ?>
        <p>Nenhum post ainda. Seja o primeiro a compartilhar!</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div style="background: #fff; padding: 16px; margin-bottom: 16px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px; color: #666;">
                    <span><strong><?php echo htmlspecialchars($post['nome']); ?></strong></span>
                    <span><?php echo date('d/m/Y H:i', strtotime($post['criado_em'])); ?></span>
                </div>
                <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;"><?php echo htmlspecialchars($post['titulo']); ?></div>
                <div style="color: #333; line-height: 1.5; margin-bottom: 12px;"><?php echo htmlspecialchars($post['conteudo']); ?></div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button style="padding: 6px 12px; font-size: 12px; background: linear-gradient(90deg,#0b6e4f,#0b84c6); color: #fff; border: none; border-radius: 4px; cursor: pointer;">❤ Curtir (<?php echo $post['curtidas']; ?>)</button>
                    <button style="padding: 6px 12px; font-size: 12px; background: linear-gradient(90deg,#0b6e4f,#0b84c6); color: #fff; border: none; border-radius: 4px; cursor: pointer;">💬 Comentar</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr style="margin: 40px 0; border: none; border-top: 1px solid #ddd;">

    <h3>🚨 Ocorrências Recentes da Comunidade</h3>
    
    <?php if (empty($ocorrencias)): ?>
        <p>Nenhuma ocorrência reportada ainda.</p>
    <?php else: ?>
        <?php foreach ($ocorrencias as $ocor): ?>
            <div style="background: #fff; padding: 16px; margin-bottom: 16px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; background: #fff3cd; color: #856404;">
                        <?php echo htmlspecialchars($ocor['categoria']); ?>
                    </span>
                    <span style="font-size: 12px; color: #666; "><?= date('d/m/Y H:i', strtotime($ocor['criado_em'])); ?></span>
                </div>
                
                <?php if ($ocor['foto_url']): ?>
                    <img src="<?= htmlspecialchars($ocor['foto_url']) ?>" alt="Foto" style="max-width: 200px; border-radius: 4px; margin-bottom: 10px;">
                <?php endif; ?>
                
                <div style="color: #333; line-height: 1.5; margin-bottom: 12px;">
                    <strong>📍 <?= htmlspecialchars($ocor['endereco'] ?? 'Não informado') ?></strong><br>
                    <?= htmlspecialchars(substr($ocor['descricao'], 0, 150)) ?>...
                </div>
                
                <div>
                    <form method="POST" action="upvote.php" style="display: inline;">
                        <input type="hidden" name="ocorrencia_id" value="<?= $ocor['id'] ?>">
                        <button type="submit" style="padding: 8px 16px; font-size: 14px; background: #17a2b8; color: #fff; border: none; border-radius: 4px; cursor: pointer;">👍 Útil (<?= $ocor['curtidas'] ?>)</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>