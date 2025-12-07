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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_ocorrencia'])) {
    $categoria = $_POST['categoria'] ?? '';
    $latitude = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    
    if (empty($categoria) || empty($latitude) || empty($longitude) || empty($descricao)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } elseif (strlen($descricao) > 300) {
        $erro = "Descrição não pode ter mais de 300 caracteres!";
    } else {
        $lat = floatval($latitude);
        $lng = floatval($longitude);
        $south = -20.08;
        $north = -19.78;
        $west = -44.02;
        $east = -43.58;
        
        if ($lat < $south || $lat > $north || $lng < $west || $lng > $east) {
            $erro = "⚠️ Selecione um local dentro da área delimitada!";
        } else {
            $foto_url = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $arquivo = $_FILES['foto'];
                $tamanho_max = 5 * 1024 * 1024;
                
                if ($arquivo['size'] > $tamanho_max) {
                    $erro = "Foto muito grande! Máximo 5MB";
                } else {
                    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($arquivo['type'], $tipos_permitidos)) {
                        $erro = "Formato de imagem inválido!";
                    } else {
                        $pasta = 'uploads/fotos/';
                        if (!is_dir($pasta)) mkdir($pasta, 0755, true);
                        
                        $nome_arquivo = uniqid() . '_' . basename($arquivo['name']);
                        $caminho = $pasta . $nome_arquivo;
                        
                        if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                            $foto_url = $caminho;
                        }
                    }
                }
            }
            
            if (empty($erro)) {
                try {
                    $sql = "INSERT INTO ocorrencias (usuario_id, categoria, latitude, longitude, endereco, descricao, foto_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$usuario_id, $categoria, $latitude, $longitude, $endereco, $descricao, $foto_url]);
                    $sucesso = "✅ Ocorrência reportada com sucesso!";
                    $_POST = [];
                } catch (PDOException $e) {
                    $erro = "Erro ao salvar: " . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Ocorrência - Colabore</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <h1>📍 Reportar Ocorrência</h1>

    <?php if ($erro): ?>
        <div style="color: #c3212b; background: #f8d7da; padding: 12px; border-radius: 4px; margin-bottom: 15px;">⚠️ <?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div style="color: #155724; background: #d4edda; padding: 12px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="max-width: 600px;">
        <div style="margin-bottom: 20px;">
            <label for="categoria" style="display: block; font-weight: bold; margin-bottom: 8px;"><strong>Categoria *</strong></label>
            <select name="categoria" id="categoria" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Selecione uma categoria --</option>
                <option value="Iluminação Pública">💡 Iluminação Pública</option>
                <option value="Segurança">🛡️ Segurança</option>
                <option value="Coleta de Lixo">🗑️ Coleta de Lixo</option>
                <option value="Infraestrutura">🏗️ Infraestrutura</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;"><strong>Localização * (Clique no mapa)</strong></label>
            <div id="map" style="width: 100%; height: 400px; border-radius: 8px; margin: 15px 0; border: 2px solid #ddd;"></div>
            <input type="hidden" name="latitude" id="latitude" required>
            <input type="hidden" name="longitude" id="longitude" required>
            <small id="coordenadas" style="color: #0b6e4f; font-weight: bold;">Clique no mapa para selecionar a localização</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="endereco" style="display: block; font-weight: bold; margin-bottom: 8px;"><strong>Endereço (opcional)</strong></label>
            <input type="text" name="endereco" id="endereco" placeholder="Rua, Número, Complemento" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="descricao" style="display: block; font-weight: bold; margin-bottom: 8px;"><strong>Descrição * (máx. 300 caracteres)</strong></label>
            <textarea name="descricao" id="descricao" maxlength="300" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"></textarea>
            <div style="font-size: 12px; color: #999; margin-top: 5px;"><span id="charCount">0</span>/300 caracteres</div>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="foto" style="display: block; font-weight: bold; margin-bottom: 8px;"><strong>Foto (opcional - máx. 5MB)</strong></label>
            <input type="file" name="foto" id="foto" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="enviar_ocorrencia" style="background: #007bff; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;">📤 Enviar Ocorrência</button>
    </form>

    </div>
    <script>
        const AREA_BOUNDS = [[-20.08, -44.02], [-19.78, -43.58]];
        const map = L.map('map').setView([-19.930, -43.800], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '© OpenStreetMap'}).addTo(map);
        L.rectangle(AREA_BOUNDS, {color: "#0b6e4f", weight: 2, opacity: 0.5, fill: false, dashArray: '5, 5'}).addTo(map);
        map.setMaxBounds(AREA_BOUNDS);
        let marcador = null;
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            if (lat < AREA_BOUNDS[0][0] || lat > AREA_BOUNDS[1][0] || lng < AREA_BOUNDS[0][1] || lng > AREA_BOUNDS[1][1]) {
                alert('⚠️ Selecione um local DENTRO da área delimitada!');
                return;
            }
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            document.getElementById('coordenadas').textContent = `✅ Latitude: ${lat.toFixed(6)} | Longitude: ${lng.toFixed(6)}`;
            if (marcador) map.removeLayer(marcador);
            marcador = L.marker([lat, lng]).addTo(map);
        });
        document.getElementById('descricao').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html