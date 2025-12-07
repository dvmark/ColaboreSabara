<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$usuario = $_SESSION['user'];
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<style>
    .sidebar-wrapper {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: linear-gradient(180deg, #1b7e78 0%, #158a84 100%);
        color: white;
        padding: 0;
        overflow-y: auto;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 15px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        flex-shrink: 0;
    }

    .sidebar-logo {
        max-width: 100%;
        width: 210px;
        height: auto;
        margin: 0 auto;
        display: block;
    }

    .sidebar-user {
        text-align: center;
        padding: 20px;
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        margin: 20px;
        flex-shrink: 0;
    }

    .sidebar-user p {
        font-size: 12px;
        color: rgba(255,255,255,0.7);
        margin: 0 0 8px 0;
    }

    .sidebar-user strong {
        font-size: 14px;
        color: white;
        display: block;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-menu li {
        margin: 0;
    }

    .sidebar-menu a {
        display: block;
        padding: 14px 20px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .sidebar-menu a:hover {
        background: rgba(255,255,255,0.1);
        border-left-color: #4dd0e1;
    }

    .sidebar-menu a.active {
        background: rgba(255,255,255,0.15);
        border-left-color: #4dd0e1;
    }

    .sidebar-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 15px 20px;
    }

    .content-wrapper {
        margin-left: 250px;
        min-height: 100vh;
    }

    @media (max-width: 768px) {
        .sidebar-wrapper {
            width: 200px;
        }
        .content-wrapper {
            margin-left: 200px;
        }
        .sidebar-logo {
            width: 160px;
        }
    }
</style>

<div class="sidebar-wrapper">
    <div class="sidebar-header">
        <img src="CSLOGO.png" alt="Logo Colabore" class="sidebar-logo">
    </div>

    <div class="sidebar-user">
        <p>Logado como</p>
        <strong><?php echo htmlspecialchars(substr($usuario['nome'], 0, 12)); ?></strong>
    </div>

    <ul class="sidebar-menu">
        <li><a href="dashboard.php" <?php echo ($current_page == 'dashboard') ? 'class="active"' : ''; ?>>📰 Feed</a></li>
        <li><a href="ocorrencias.php" <?php echo ($current_page == 'ocorrencias') ? 'class="active"' : ''; ?>>📍 Reportar Ocorrência</a></li>
        <li><a href="minhasocorrencias.php" <?php echo ($current_page == 'minhasocorrencias') ? 'class="active"' : ''; ?>>📋 Minhas Ocorrências</a></li>
        <li><a href="perfil.php" <?php echo ($current_page == 'perfil') ? 'class="active"' : ''; ?>>👤 Perfil</a></li>
        <li><hr class="sidebar-divider"></li>
        <a href="logout.php" style="margin-right: 15px; text-decoration: none; font-weight: bold; color: white; background: #dc3545; padding: 10px 20px; border-radius: 4px;">Sair</a>
    </ul>
</div>

<div class="content-wrapper">