<?php
require 'conexao.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['user'];
?>
<?php include 'sidebar.php'; ?>

    <h1>👤 Meu Perfil</h1>

    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 600px;">
        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #0b6e4f 0%, #0b84c6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px;">👤</div>
        
        <h2 style="color: #0b6e4f; margin-bottom: 25px; font-size: 22px;">Informações da Conta</h2>
        
        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">📝 Nome Completo</label>
            <p style="font-size: 16px; color: #333; font-weight: 500;"><?php echo htmlspecialchars($usuario['nome']); ?></p>
        </div>

        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">📧 Email</label>
            <p style="font-size: 16px; color: #333; font-weight: 500;"><?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>

        <div style="margin-bottom: 20px; padding-bottom: 0; border-bottom: none;">
            <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">🆔 CPF</label>
            <p style="font-size: 16px; color: #333; font-weight: 500;">
                <?php 
                    $cpf = $usuario['cpf'];
                    $cpf_formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
                    echo htmlspecialchars($cpf_formatado);
                ?>
            </p>
        </div>
    </div>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>